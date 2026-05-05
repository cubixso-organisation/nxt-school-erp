<?php

namespace app\modules\exammanagement\controllers;

use Yii;
use app\models\User;
use app\modules\exammanagement\models\MarksheetSetting;
use app\modules\exammanagement\models\search\MarksheetSettingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MarksheetSettingController implements the CRUD actions for MarksheetSetting model.
 */
class MarksheetSettingController extends Controller
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
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
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
     * Lists all MarksheetSetting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MarksheetSettingSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MarksheetSetting model.
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
     * Creates a new MarksheetSetting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MarksheetSetting();

        if ($model->loadAll(Yii::$app->request->post())) {
            $marksheetHeader = \yii\web\UploadedFile::getInstance($model, 'marksheet_header_image');
            if (!empty($marksheetHeader)) {
                $image = Yii::$app->notification->imageKitUpload($marksheetHeader, 'marksheet_settings/marksheet_header_image');
                $model->marksheet_header_image = $image['url'];
            }

            $principal_signature = \yii\web\UploadedFile::getInstance($model, 'principal_signature');
            if (!empty($principal_signature)) {
                $pimage = Yii::$app->notification->imageKitUpload($principal_signature, 'marksheet_settings/principal_signature');
                $model->principal_signature = $pimage['url'];
            }
            $model->campus_id = (new User())->getCampusId();

            $model->save(false);

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MarksheetSetting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {

            $oldImagePrincipal = $model->principal_signature;
            $oldImageHeader = $model->marksheet_header_image;
            $marksheetHeader = \yii\web\UploadedFile::getInstance($model, 'marksheet_header_image');
            if (!empty($marksheetHeader)) {
                $image = Yii::$app->notification->imageKitUpload($marksheetHeader, 'marksheet_settings/marksheet_header_image');
                $model->marksheet_header_image = $image['url'];
            } else {
                $model->marksheet_header_image = $oldImageHeader;
            }

            $principal_signature = \yii\web\UploadedFile::getInstance($model, 'principal_signature');
            if (!empty($principal_signature)) {
                $image = Yii::$app->notification->imageKitUpload($principal_signature, 'marksheet_settings/principal_signature');
                $model->principal_signature = $image['url'];
            } else {
                $model->principal_signature = $oldImagePrincipal;
            }
            $model->campus_id = (new User())->getCampusId();

            $model->save(false);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MarksheetSetting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = MarksheetSetting::STATUS_DELETE;
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
            $model = MarksheetSetting::find()->where([
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
     * Finds the MarksheetSetting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MarksheetSetting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MarksheetSetting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
