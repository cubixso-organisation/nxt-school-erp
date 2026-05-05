<?php

namespace app\modules\admin\controllers;

use app\components\Toast;
use Yii;
use app\models\User;
use app\modules\admin\models\Permissions;
use app\modules\admin\models\RoleHasPermissions;
use app\modules\admin\models\Roles;
use app\modules\admin\models\search\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Role;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RolesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-role-has-permissions'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Roles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RolesSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Roles model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerRoleHasPermissions = new \yii\data\ArrayDataProvider([
            'allModels' => $model->roleHasPermissions,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerRoleHasPermissions' => $providerRoleHasPermissions,
        ]);
    }

    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Roles();
        $permissions = Permissions::find()->where(['status' => Permissions::STATUS_ACTIVE])->all();

        if ($model->loadAll(Yii::$app->request->post())) {

            $defaultRoles = [
                'parent',
                'Student',
                'teacher',
                'Warden',
                'Librarian',
                'ChiefWarden',
                'Staff',
                'Agent',
                'BusCoOrdinator',
                'BusDriver'
            ];

            if (in_array($model->name, $defaultRoles)) {
                Yii::$app->session->setFlash('error', 'You cannot use the default role names');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $checkRoleAlredyCreated = Roles::find()->where(['name' => $model->name])
                ->andWhere(['campus_id' => (new User())->getCampusId()])
                ->one();

            if (!empty($checkRoleAlredyCreated)) {
                (new Toast)->error('Role Already Created');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $model->campus_id = (new User())->getCampusId();
            $model->status = Roles::STATUS_ACTIVE;

            if ($model->save(false)) {
                // Get the selected permissions from the POST data
                $selectedPermissions = Yii::$app->request->post('Permissions', []);
                // Clear existing permissions for the role
                RoleHasPermissions::deleteAll(['role_id' => $model->id]);

                // Assign new permissions to the role
                foreach ($selectedPermissions as $permissionName) {
                    $permission = Permissions::findOne(['name' => $permissionName]);
                    if ($permission) {
                        $rolePermission = new RoleHasPermissions();
                        $rolePermission->role_id = $model->id;
                        $rolePermission->permission_id = $permission->id;
                        $rolePermission->status = RoleHasPermissions::STATUS_ACTIVE;
                        $rolePermission->save(false);
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'permissions' => $permissions
            ]);
        }
    }

    /**
     * Updates an existing Roles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Roles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = Roles::STATUS_DELETE;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (! empty($post['id'])) {
            $model = Roles::find()->where([
                'id' => $post['id'],
            ])->one();
            if (!empty($model)) {

                $model->status = $post['val'];
            }
            if ($model->save(false)) {
                $data['message'] = "Updated";
                $data['id'] = $model->status;
            } else {
                $data['message'] = "Not Updated";
            }
        }
        return $data;
    }


    /**
     * Finds the Roles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Roles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Roles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for RoleHasPermissions
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddRoleHasPermissions()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('RoleHasPermissions');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formRoleHasPermissions', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
