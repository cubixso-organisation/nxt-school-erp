<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\base\Campus;
use app\modules\admin\models\StudentHasAssessment;
use Yii;
use app\models\User;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentAssessment;
use app\modules\admin\models\search\StudentAssessmentSearch;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\SubjectTimetable;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentAssessmentController implements the CRUD actions for StudentAssessment model.
 */
class StudentAssessmentController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-student-has-assessment'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-student-has-assessment'],
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
     * Lists all StudentAssessment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentAssessmentSearch();





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
     * Displays a single StudentAssessment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerStudentHasAssessment = new \yii\data\ArrayDataProvider([
            'allModels' => $model->studentHasAssessments,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerStudentHasAssessment' => $providerStudentHasAssessment,
        ]);
    }

    /**
     * Creates a new StudentAssessment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StudentAssessment();

        if ($model->loadAll(Yii::$app->request->post())) {
            $documentUpload = \yii\web\UploadedFile::getInstance($model, 'document');
            if (!empty($documentUpload)) {
                $image = Yii::$app->notification->imageKitUpload($documentUpload, 'assessment/students');

                $document = $image['url'];
            } else {
                $document = "";
            }

            $date = date("Y-m-d");
            $day_id = date('l', strtotime($date));
// var_dump($day_id);exit;
            foreach ($model->section_id as $sections) {
$subjectTimetable = SubjectTimetable::find()->where(['section_id'=> $sections])->andWhere(['teacher_details_id'=>$model->teacher_details_id])->andWhere(['subject_id'=>$model->subject_id])->andWhere(['day_id'=>$day_id])->one();
               
if(empty($subjectTimetable)) {

    Yii::$app->session->setFlash('error', 'No timetable exists for the teacher for this subject today.');
    return $this->redirect(Yii::$app->request->referrer);
    
}
$assessment =  new StudentAssessment();
                $section = ClassSections::find()->where(['id' => $sections])->one();
                $assessment->section_id = $sections;
                $assessment->class_id = $section->studentClass->id;
                $assessment->campus_id  = $model->campus_id;
                $assessment->teacher_details_id   = $model->teacher_details_id;
                $assessment->academic_year_id    = $model->academic_year_id;
                $assessment->subject_id     = $model->subject_id;
                $assessment->assessment     = $model->assessment;
                $assessment->submission_date     = $model->submission_date;
                $assessment->document     = $document;
                $assessment->status     = StudentAssessment::STATUS_ACTIVE;
                $assessment->subject_timetable_id   = $subjectTimetable->id;
                if ($assessment->save(false)) {
                    $studentDetails = StudentDetails::find()->where(['section_id' => $sections])->all();
                    if (!empty($studentDetails)) {
                        foreach ($studentDetails as $details) {
                            $studentHasAssessment = new StudentHasAssessment();
                            $studentHasAssessment->student_id = $details->id;
                            $studentHasAssessment->student_assessment_id  = $assessment->id;
                            $studentHasAssessment->date  = date('Y-m-d');
                            $studentHasAssessment->is_read  = StudentHasAssessment::is_read_no;
                            $studentHasAssessment->status  = StudentHasAssessment::STATUS_PENDING;
                            if ($studentHasAssessment->save(false)) {

                                $title = 'New Assingment';
                                $body = "New Assingment added by " . (new Campus())->getCampusName() . " please check the assignment section in E-Student App";
                                $type = '';
                                Yii::$app->notification->UserNotification('', $details->parent->user_id, $title, $body, $type, 'student_assessment', $assessment->id);
                            }
                        }
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

    /**
     * Updates an existing StudentAssessment model.
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
     * Deletes an existing StudentAssessment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = StudentAssessment::STATUS_DELETE;
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
            $model = StudentAssessment::find()->where([
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
     * Finds the StudentAssessment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentAssessment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentAssessment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentHasAssessment
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentHasAssessment()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentHasAssessment');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formStudentHasAssessment', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
