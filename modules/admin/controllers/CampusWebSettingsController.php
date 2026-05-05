<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\CampusWebSettings;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\search\CampusWebSettingsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CampusWebSettingsController implements the CRUD actions for CampusWebSettings model.
 */
class CampusWebSettingsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status'],
                        'matchCallback' => function () {
                            return User::isInstituteAdmin();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status'],
                        'matchCallback' => function () {
                            return User::isCampusAdmin();
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
     * Lists all CampusWebSettings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CampusWebSettingsSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CampusWebSettings model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CampusWebSettings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CampusWebSettings();

        if ($model->loadAll(Yii::$app->request->post())) {

            $post = Yii::$app->request->post();
            $type_id = !empty($post['CampusWebSettings']['type_id']) ? $post['CampusWebSettings']['type_id'] : 0;
            $campus_id = !empty($post['CampusWebSettings']['campus_id']) ? $post['CampusWebSettings']['campus_id'] : '';
            $name = $post['CampusWebSettings']['name'];
            $setting_key = $post['CampusWebSettings']['setting_key'];
            $value = $post['CampusWebSettings']['value'];
            $status = $post['CampusWebSettings']['status'];
            if ($type_id == 1) {
                $getCampusByInstituteId = (new Institutes())->getCampusByInstituteId();
                foreach ($getCampusByInstituteId as $getCampusByInstituteIdData) {
                    $campus_web_settings_exist = CampusWebSettings::find()
                        ->where(['setting_key' => $setting_key])
                        ->where(['campus_id' => $getCampusByInstituteIdData])->one();
                    if (!empty($campus_web_settings_exist)) {
                        $modelSave = CampusWebSettings::find()
                            ->where(['setting_key' => $setting_key])
                            ->where(['campus_id' => $getCampusByInstituteIdData])->one();
                        $modelSave->campus_id = $getCampusByInstituteIdData;
                        $modelSave->setting_key = $setting_key;
                        $modelSave->value = $value;
                        $modelSave->name = $name;
                        $modelSave->type_id = $type_id;
                        $modelSave->status = $status;
                        $modelSave->save(false);
                    } else {
                        $modelNew = new CampusWebSettings();
                        $modelNew->campus_id = $getCampusByInstituteIdData;
                        $modelNew->setting_key = $setting_key;
                        $modelNew->value = $value;
                        $modelNew->name = $name;
                        $modelNew->type_id = $type_id;
                        $modelNew->status = $status;
                        $modelNew->save(false);
                    }
                }
            } else {
                $modelCmp = new CampusWebSettings();
                $modelCmp->campus_id = $campus_id;
                $modelCmp->setting_key = $setting_key;
                $modelCmp->value = $value;
                $modelCmp->name = $name;
                $modelCmp->type_id = $type_id;
                $modelCmp->status = $status;
                $modelCmp->save(false);
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CampusWebSettings model.
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
     * Deletes an existing CampusWebSettings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = CampusWebSettings::STATUS_DELETE;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = CampusWebSettings::find()->where([
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
     * Finds the CampusWebSettings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CampusWebSettings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CampusWebSettings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
