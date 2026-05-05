<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\base\StudentHasNotice;
use app\modules\admin\models\StudentNoticeBoards;
use app\modules\admin\models\search\StudentNoticeBoardsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentNoticeBoardsController implements the CRUD actions for StudentNoticeBoards model.
 */
class StudentNoticeBoardsController extends Controller
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
     * Lists all StudentNoticeBoards models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentNoticeBoardsSearch();





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
     * Displays a single StudentNoticeBoards model.
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
     * Creates a new StudentNoticeBoards model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StudentNoticeBoards();

        if ($model->loadAll(Yii::$app->request->post())) {
            $upload_image = \yii\web\UploadedFile::getInstance($model, 'notice_image');
            if (!empty($upload_image)) {
                $image = Yii::$app->notification->imageKitUpload($upload_image);
// var_dump($image);exit;

                $model->notice_image = $image['url'];
            }

            $post = Yii::$app->request->post();
            $section_id = $post['StudentNoticeBoards']['section_id'];
            $is_global = $post['StudentNoticeBoards']['is_global'];

            foreach ($section_id as $section_id_data) {
                $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
                $model = new StudentNoticeBoards();
                $model->loadAll(Yii::$app->request->post());
                $model->section_id = $section_id_data;
                $model->campus_id = $campus_id;

                // Check if notice_image is set, if yes then use it
                if (!empty($upload_image)) {
                    $model->notice_image = $image['url'];
                }

                $model->save();

                if ($model->is_global == StudentNoticeBoards::is_global_no) {   
                    $student_details = StudentDetails::find()->andWhere(['section_id' => $section_id_data])->all();
                    if (!empty($student_details)) {
                        foreach ($student_details as $student_details_id) {
                            $student_id = $student_details_id->id;

                            $StudentHasNoticeCheck = StudentHasNotice::find()->where(['student_id' => $student_id])->andWhere(['student_notice_board_id' => $model->id])->one();
                            if (!empty($StudentHasNoticeCheck)) {
                                $StudentHasNotice = StudentHasNotice::find()->where(['id' => $StudentHasNoticeCheck->id])->one();
                            } else {
                                $StudentHasNotice = new StudentHasNotice();
                            }

                            $StudentHasNotice->student_id = $student_id;
                            $StudentHasNotice->student_notice_board_id = $model->id;
                            $StudentHasNotice->status = StudentHasNotice::STATUS_ACTIVE;
                            $StudentHasNotice->is_read = StudentHasNotice::is_read_yes;

                            $StudentHasNotice->save(false);

                            $student_name = !empty($StudentHasNotice->student->student_name) ? $StudentHasNotice->student->student_name : 'No Name';

                            $title = isset($model->title)?$model->title:"Student Notice";
                            $body = "Dear parent Notice for $student_name";
                            $type = '';
                            $notificationType = "notice_board";
                        Yii::$app->notification->UserNotification('', $StudentHasNotice->student->parent->user_id, $title, $body, $type, $notificationType);

                        }
                    }
                }else{
                    $student_details = StudentDetails::find()->andWhere(['section_id' => $section_id_data])->all();
                    if (!empty($student_details)) {
                        foreach ($student_details as $student_details_id) {
                            $student_id = $student_details_id->id;

                            $StudentHasNoticeCheck = StudentHasNotice::find()->where(['student_id' => $student_id])->andWhere(['student_notice_board_id' => $model->id])->one();
                            if (!empty($StudentHasNoticeCheck)) {
                                $StudentHasNotice = StudentHasNotice::find()->where(['id' => $StudentHasNoticeCheck->id])->one();
                            } else {
                                $StudentHasNotice = new StudentHasNotice();
                            }

                            $StudentHasNotice->student_id = $student_id;
                            $StudentHasNotice->student_notice_board_id = $model->id;
                            $StudentHasNotice->status = StudentHasNotice::STATUS_ACTIVE;
                            $StudentHasNotice->is_read = StudentHasNotice::is_read_yes;

                            $StudentHasNotice->save(false);

                            $student_name = !empty($StudentHasNotice->student->student_name) ? $StudentHasNotice->student->student_name : 'No Name';

                            $title = isset($model->title)?$model->title:"Student Notice";
                            $body = "Dear parent Notice for $student_name";
                            $type = '';
                            $notificationType = "notice_board";
                        Yii::$app->notification->UserNotification('', $StudentHasNotice->student->parent->user_id, $title, $body, $type, $notificationType);

                        }
                    }
                }
            }

            Yii::$app->session->setFlash('successs', 'Selected class or section does not have any students.');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing StudentNoticeBoards model.
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
     * Deletes an existing StudentNoticeBoards model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // Delete related records from the child table
        \Yii::$app->db->createCommand()
        ->delete('student_has_notice', ['student_notice_board_id' => $id])
        ->execute();
    
        // Delete the parent record
        $this->findModel($id)->delete();
    
        return $this->redirect(['index']);
    }
    

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = StudentNoticeBoards::find()->where([
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
     * Finds the StudentNoticeBoards model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentNoticeBoards the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentNoticeBoards::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
