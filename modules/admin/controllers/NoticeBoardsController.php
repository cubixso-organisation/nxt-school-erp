<?php

namespace app\modules\admin\controllers;

use app\components\AuthSettings;
use Yii;
use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\NoticeBoards;
use app\modules\admin\models\ParentDetails;
use app\modules\admin\models\search\NoticeBoardsSearch;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\TeacherDetails;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NoticeBoardsController implements the CRUD actions for NoticeBoards model.
 */
class NoticeBoardsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'index-teacher-notice', 'index-student-notice', 'create-teacher', 'create-student', 'student-update'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'index-teacher-notice', 'index-student-notice', 'create-teacher', 'create-student', 'student-update'],
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
     * Lists all NoticeBoards models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NoticeBoardsSearch();





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
    public function actionIndexTeacherNotice()
    {
        $searchModel = new NoticeBoardsSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminTeacherNoticeSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index_teacher_notice', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIndexStudentNotice()
    {
        $searchModel = new NoticeBoardsSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminStudentNoticeSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index_student_notice', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NoticeBoards model.
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
     * Creates a new NoticeBoards model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NoticeBoards();

        if ($model->load(Yii::$app->request->post())) {
            $upload_image = \yii\web\UploadedFile::getInstance($model, 'notice_image');

            if (!empty($upload_image)) {
                if (!empty($upload_image)) {
                    $imageName = time() . '_' . preg_replace('/\s+/', '_', $upload_image->baseName) . '.' . $upload_image->extension;
                    $uploadPath = Yii::getAlias('@webroot/uploads/') . $imageName;

                    if ($upload_image->saveAs($uploadPath)) {
                        $model->notice_image = '/uploads/' . $imageName;
                    }
                }
            }

            $post = Yii::$app->request->post();
            $title = $post['NoticeBoards']['title'];
            $description = $post['NoticeBoards']['description'];
            $expiry_date = $post['NoticeBoards']['expiry_date'];
            $section_id = $post['NoticeBoards']['section_id'];
            $status = $post['NoticeBoards']['status'];

            foreach ($section_id as $section_id_data) {
                $model = new NoticeBoards();
                $model->section_id = $section_id_data;
                $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
                $model->title = $title;
                $model->description = $description;
                $model->expiry_date = $expiry_date;
                $model->status = $status;

                // Check if notice_image is set, if yes then use it
                if (!empty($upload_image)) {
                    $model->notice_image = Yii::$app->request->hostInfo . Yii::getAlias('@web') . '/uploads/' . $imageName;
                }


                $model->save();


                $studentDetils = StudentDetails::find()->where(['section_id' => $section_id_data])->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->all();

                if (!empty($studentDetils)) {

                    foreach ($studentDetils as $studentDetil) {
                        $parentDetails = ParentDetails::find()->where(['id' => $studentDetil->parent_id])->one();
                        $title = 'New Notice';
                        $body = $model->title;
                        $type = '';
                        Yii::$app->notification->UserNotification('', $parentDetails->user_id, $title, $body, $type, 'student_notice', $model->id);
                    }
                }

                $teacherDetails = TeacherDetails::find()->where(['campus_id' => (new User())->getCampusId()])->andWhere(['status' => TeacherDetails::STATUS_ACTIVE])->all();
                if (!empty($teacherDetails)) {

                    foreach ($teacherDetails as $td) {
                        $model = new NoticeBoards();
                        $model->section_id = $section_id_data;
                        $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
                        $model->title = $title;
                        $model->description = $description;
                        $model->expiry_date = $expiry_date;
                        $model->teacher_id = $td->id;
                        $model->status = $status;

                        // Check if notice_image is set, if yes then use it
                        if (!empty($upload_image)) {
                            $model->notice_image = Yii::$app->request->hostInfo . Yii::getAlias('@web') . '/uploads/' . $imageName;
                        }


                        $model->save(false);
                        $title = 'New Notice';
                        $body = $model->title;
                        $type = '';
                        Yii::$app->notification->UserNotification('', $td->user_id, $title, $body, $type);
                    }
                }
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    public function actionCreateTeacher()
    {
        $model = new NoticeBoards();

        if ($model->load(Yii::$app->request->post())) {
            $upload_image = \yii\web\UploadedFile::getInstance($model, 'notice_image');

            if (!empty($upload_image)) {
                $image = Yii::$app->notification->imageKitUpload($upload_image);
                $model->notice_image = $image['url'];
            }

            $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);

            // Remove saving the notice here

            // Get the selected teacher IDs from the form
            $teacherIds = $model->teacher_id;

            // Iterate over each selected teacher ID
            foreach ($teacherIds as $teacherId) {
                // Create a new NoticeBoards model for each teacher
                $newModel = new NoticeBoards();
                $newModel->attributes = $model->attributes;
                $newModel->teacher_id = $teacherId;

                // Save the notice for the current teacher
                if ($newModel->save()) {
                    $teacherDetails = TeacherDetails::find()->where(['campus_id' => $model->campus_id])->andWhere(['id' => $teacherId])->andWhere(['status' => TeacherDetails::STATUS_ACTIVE])->one();

                    if (!empty($teacherDetails)) {
                        $title = 'New Notice';
                        $body = $newModel->title;
                        $type = '';
                        Yii::$app->notification->UserNotification('', $teacherDetails->user_id, $title, $body, $type, 'teacher_notice', $newModel->id);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save the notice for one or more teachers.');
                }
            }

            return $this->redirect(['index-teacher-notice']);
        }

        return $this->render('_form_teacher_notice', [
            'model' => $model,
        ]);
    }




    public function actionCreateStudent()
    {
        $model = new NoticeBoards();

        if ($model->load(Yii::$app->request->post())) {
            $upload_image = \yii\web\UploadedFile::getInstance($model, 'notice_image');

            if (!empty($upload_image)) {
                $image = Yii::$app->notification->imageKitUpload($upload_image);
                $model->notice_image = $image['url'];
            }

            $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);

            // Remove saving the notice here

            // Get the selected student IDs from the form
            $studentIds = $model->student_id;

            // Iterate over each selected student ID
            foreach ($studentIds as $studentId) {
                $newModel = new NoticeBoards();
                $newModel->attributes = $model->attributes;
                $newModel->student_id = $studentId;
                // Explicitly set notice_image if available

                $newModel->notice_image = $model->notice_image;

                if ($newModel->save()) {
                    $studentDetails = StudentDetails::find()->where(['id' => $studentId])->andWhere(['campus_id' => $model->campus_id])->one();
                    if (!empty($studentDetails)) {
                        $parentDetails = ParentDetails::find()->where(['id' => $studentDetails->parent_id])->one();
                        if (!empty($parentDetails) && !empty($parentDetails->user_id)) {
                            $title = 'New Notice';
                            $body = $newModel->title;
                            $type = '';
                            Yii::$app->notification->UserNotification('', $parentDetails->user_id, $title, $body, $type, 'student_notice', $newModel->id);
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save the notice for one or more students.');
                }
            }

            return $this->redirect(['index-student-notice']);
        }

        return $this->render('_form_student_notice', [
            'model' => $model,
        ]);
    }






    /**
     * Updates an existing NoticeBoards model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    public function actionTeacherUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
            $model->save();
            return $this->redirect(['index-teacher-notice']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    public function actionStudentUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
            $model->save();
            return $this->redirect(['index-student-notice']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing NoticeBoards model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model !== null) { // Ensure the model is found
            $model->delete(); // Delete the model
        }

        return $this->redirect(['index']);
    }


    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = NoticeBoards::find()->where([
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
     * Finds the NoticeBoards model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NoticeBoards the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NoticeBoards::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
