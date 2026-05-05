<?php

namespace app\modules\exammanagement\controllers;

use app\components\Toast;
use app\modules\admin\models\search\ExamsResultSearch;
use app\modules\exammanagement\models\base\ScheduledExamMarksDevision;
use Yii;
use app\models\User;
use app\modules\admin\models\base\Exams;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\base\Subjects;
use app\modules\admin\models\ExamsResult;
use app\modules\childassessment\models\base\ChildMerit;
use app\modules\childassessment\models\base\MeritsAssignedToClass;
use app\modules\childassessment\models\base\StudentMeritMarks;
use app\modules\exammanagement\models\base\ExamHallTicket;
use app\modules\exammanagement\models\base\ExamSchedules as BaseExamSchedules;
use app\modules\exammanagement\models\base\ExamSchedules;
use app\modules\exammanagement\models\base\ExamStudentMarksheet;
use app\modules\exammanagement\models\base\ScheduledExamMarksDevisionResults;
use app\modules\exammanagement\models\MarksDivition;
use app\modules\exammanagement\models\search\ExamSchedulesSearch;
use Exception;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;


/**
 * ExamSchedulesController implements the CRUD actions for ExamSchedules model.
 */
class ExamSchedulesController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'generate-all-hall-tickets-pdf', 'update', 'delete', 'update-status', 'get-section', 'create-time-table', 'index-time-table', 'get-exam-data', 'generate-pdf', 'exam-hall-ticket', 'get-student-data', 'generate-hall-ticket'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'pdf', 'generate-all-hall-tickets-pdf', 'update-status', 'create-time-table', 'index-time-table', 'get-exam-data', 'generate-pdf', 'exam-hall-ticket', 'get-student-data', 'generate-hall-ticket-pdf'],
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
     * Lists all ExamSchedules models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExamSchedulesSearch();





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
    // public function actionIndexTimeTable()
    // {
    //     $searchModel = new ExamSchedulesSearch();





    //     if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
    //         $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    //     } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
    //         $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
    //     } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
    //         $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
    //     } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
    //         $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
    //     }





    //     return $this->render('index-time-table', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }
    public function actionIndexTimeTable($data)
    {
        return $this->render('index-time-table', [
            'data' => $data,
        ]);
    }


    /**
     * Displays a single ExamSchedules model.
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
     * Creates a new ExamSchedules model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();

        // try {
        $model = new ExamSchedules();

        if ($model->load(Yii::$app->request->post())) {
            $subjectIds = Yii::$app->request->post('ExamSchedules')['subject_id'];

            if (!is_array($subjectIds) || empty($subjectIds)) {
                Yii::$app->session->setFlash('error', 'Please select at least one subject.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            foreach ($subjectIds as $subjectId) {
                $newModel = clone $model;
                $newModel->subject_id = $subjectId;

                // Check if the exam is already scheduled
                $checkExamAlreadyScheduled = ExamSchedules::find()
                    ->where([
                        'exam_id' => $newModel->exam_id,
                        'session_id' => $newModel->session_id,
                        'section_id' => $newModel->section_id,
                        'subject_id' => $newModel->subject_id,
                    ])
                    ->one();

                if (!empty($checkExamAlreadyScheduled)) {
                    Yii::$app->session->setFlash('error', 'Exam is already scheduled for subject ID: ' . $subjectId);
                    return $this->redirect(Yii::$app->request->referrer);
                }

                // Calculate total max and min marks before saving
                $totalMaxMarks = 0;
                $totalMinMarks = 0;
                foreach ($newModel['marks_division'] as $index => $division) {
                    $totalMaxMarks += $newModel['max_marks_devision'][$index];
                    $totalMinMarks += $newModel['min_marks_devision'][$index];
                }
                $newModel->max_marks = $totalMaxMarks;
                $newModel->min_marks = $totalMinMarks;

                // Schedule exams total marks
                $sumOfMaxMarks = ExamSchedules::find()
                    ->where([
                        'exam_id' => $newModel->exam_id,
                        'class_id' => $newModel->class_id,
                        'session_id' => $newModel->session_id,
                        'section_id' => $newModel->section_id,
                    ])
                    ->sum('max_marks');

                $exam = Exams::findOne($newModel->exam_id);

                if ((float)$sumOfMaxMarks + (float)$newModel->max_marks > (float)$exam->total_percentage_or_gpa) {
                    Yii::$app->session->setFlash('error', 'If you wish to schedule additional subjects, please adjust the total marks of the exam accordingly, as the current total max-marks for the scheduled exam exceed the allotted total marks.');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                // Save the exam schedule
                if (!$newModel->save(false)) {
                    throw new \Exception('Error saving exam schedule.');
                }

                // Save exam scheduled divisions and other related data
                foreach ($newModel['marks_division'] as $index => $division) {
                    $newScheduledDivision = new ScheduledExamMarksDevision();
                    $newScheduledDivision->marks_devision_id  = $division;
                    $newScheduledDivision->max_marks_devision = $newModel['max_marks_devision'][$index];
                    $newScheduledDivision->min_marks_devision = $newModel['min_marks_devision'][$index];
                    $newScheduledDivision->status = ScheduledExamMarksDevision::STATUS_ACTIVE;
                    $newScheduledDivision->campus_id = (new User())->getCampusId();
                    $newScheduledDivision->exam_schedule_id  = $newModel->id;
                    $newScheduledDivision->save(false);
                }

                $newModel->max_marks = $totalMaxMarks;
                $newModel->min_marks = $totalMinMarks;
                $newModel->save(false);

                // Process exam results for students
                $studentDetails = StudentDetails::find()
                    ->where([
                        'campus_id' => User::getCampusId(Yii::$app->user->identity->id),
                        'academic_year_id' => $newModel->session_id,
                        'student_class_id' => $newModel->class_id,
                        'section_id' => $newModel->section_id,
                    ])->all();

                if (empty($studentDetails)) {
                    Yii::$app->session->setFlash('error', 'Selected class or section does not have any students.');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                foreach ($studentDetails as $studentDetail) {
                    $examResult = new ExamsResult();
                    $examResult->campus_id = $newModel->campus_id;
                    $examResult->user_id = $studentDetail->user_id;
                    $examResult->student_id = $studentDetail->id;
                    $examResult->exam_id = $newModel->exam_id;
                    $examResult->academic_year_id = $newModel->session_id;
                    $examResult->class_id = $newModel->class_id;
                    $examResult->section_id = $newModel->section_id;
                    $examResult->subject_id = $newModel->subject_id;
                    $examResult->exam_scheduled_id = $newModel->id;
                    $examResult->total_marks = $newModel->max_marks;
                    $examResult->min_marks = $newModel->min_marks;
                    $examResult->attandance = ExamsResult::NOT_MARKED;
                    $examResult->status = ExamsResult::Marks_not_Updated;

                    if (!$examResult->save(false)) {
                        throw new \Exception('Error saving exam result.');
                    }
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Exam Scheduled Successfully.');
            return $this->redirect(['index']);
        }
        // } catch (\Exception $e) {
        //     $transaction->rollBack();
        //     Yii::$app->session->setFlash('error', $e->getMessage());
        //     return $this->redirect(Yii::$app->request->referrer);
        // }

        return $this->render('create', ['model' => $model]);
    }


    public function actionCreateTimeTable()
    {
        $model = new ExamSchedules();

        if ($model->loadAll(Yii::$app->request->post())) {
            $existingData = ExamSchedules::findOne([
                'session_id' => $model->session_id,
                'exam_id' => $model->exam_id,
                'class_id' => $model->class_id,
            ]);

            if ($existingData) {
                return $this->render('index-time-table', [
                    'data' => [
                        'session_id' => $existingData->session_id,
                        'exam_id' => $existingData->exam_id,
                        'class_id' => $existingData->class_id,
                        'exam_date' => $existingData->exam_date,
                        'exam_duration' => $existingData->exam_duration,
                    ],
                ]);
            } else {
                // Data doesn't exist, display the form

                return $this->render('_form_create_time_table', [
                    'model' => $model,
                ]);
            }
        }

        // If the request is not a POST request or if the model fails to load, display the form
        return $this->render('_form_create_time_table', [
            'model' => $model,
        ]);
    }

    public function actionGetExamData()
    {
        $post = Yii::$app->request->post();
        $sessionId = $post["ExamSchedules"]["session_id"];
        $examId = $post["ExamSchedules"]["exam_id"];
        $classId = $post["ExamSchedules"]["class_id"];


        $examTimeTable = ExamSchedules::find()->where(['session_id' => (int)$sessionId, 'exam_id' => (int)$examId, 'class_id' => (int)$classId])->all();
        return $this->renderPartial('view-time-table', ['studentTimeTable' => $examTimeTable, 'post' => $post]);
    }




    public function actionGetSection()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $type = $parents[0];
                // var_dump($type);exit;
                $out = [];
                $dat = [];
                $subjectName = [];
                $campusId = User::getCampusId();
                $getSub = (new ExamSchedules)->Section($type);

                // var_dump($out);exit;
                // return $out;
                return  Json::encode($getSub);
            }
        }
    }


    public function actionGetExam()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $type = $parents[0];
                // var_dump($type);exit;
                $out = [];
                $dat = [];
                $subjectName = [];
                $campusId = User::getCampusId();
                $getSub = (new ExamSchedules)->Section($type);

                // var_dump($out);exit;
                // return $out;
                return  Json::encode($getSub);
            }
        }
    }

    /**
     * Updates an existing ExamSchedules model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = ExamSchedules::findOne($id);
            if (!$model) {
                throw new \Exception('Exam Schedule not found.');
            }
            $allDivisions = MarksDivition::find()->where(['campus_id' => (new User())->getCampusId()])->all();
            // Load existing marks divisions data for the exam schedule
            $existingMarksDivisions = ScheduledExamMarksDevision::find()
                ->where(['exam_schedule_id' => $model->id])
                ->all();

            if ($model->load(Yii::$app->request->post())) {
                $subjectIds = Yii::$app->request->post('ExamSchedules')['subject_id'];

                if (!is_array($subjectIds) || empty($subjectIds)) {
                    Yii::$app->session->setFlash('error', 'Please select at least one subject.');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                // Delete old marks divisions (to handle the case of removal)
                ScheduledExamMarksDevision::deleteAll(['exam_schedule_id' => $model->id]);

                foreach ($subjectIds as $subjectId) {
                    $newModel = clone $model;
                    $newModel->subject_id = $subjectId;

                    // Ensure not to schedule duplicate exams
                    $checkExamAlreadyScheduled = ExamSchedules::find()
                        ->where([
                            'exam_id' => $newModel->exam_id,
                            'session_id' => $newModel->session_id,
                            'section_id' => $newModel->section_id,
                            'subject_id' => $newModel->subject_id,
                        ])
                        ->andWhere(['<>', 'id', $newModel->id])  // Exclude current record
                        ->one();

                    if (!empty($checkExamAlreadyScheduled)) {
                        Yii::$app->session->setFlash('error', 'Exam is already scheduled for subject ID: ' . $subjectId);
                        return $this->redirect(Yii::$app->request->referrer);
                    }

                    // Update marks divisions and total marks
                    $totalMaxMarks = 0;
                    $totalMinMarks = 0;

                    foreach ($newModel['marks_division'] as $index => $division) {
                        $scheduledDivision = new ScheduledExamMarksDevision();
                        $scheduledDivision->marks_devision_id  = $division;
                        $scheduledDivision->max_marks_devision = $newModel['max_marks_devision'][$index];
                        $scheduledDivision->min_marks_devision = $newModel['min_marks_devision'][$index];
                        $scheduledDivision->campus_id = (new User())->getCampusId();
                        $scheduledDivision->exam_schedule_id  = $newModel->id;
                        $scheduledDivision->save(false);

                        $totalMaxMarks +=  $newModel['max_marks_devision'][$index];
                        $totalMinMarks +=  $newModel['min_marks_devision'][$index];
                    }

                    $newModel->max_marks = $totalMaxMarks;
                    $newModel->min_marks = $totalMinMarks;
                    $newModel->save(false);

                    // Process exam results for students if any new adjustments were made
                    $studentDetails = StudentDetails::find()
                        ->where([
                            'campus_id' => User::getCampusId(Yii::$app->user->identity->id),
                            'academic_year_id' => $newModel->session_id,
                            'student_class_id' => $newModel->class_id,
                            'section_id' => $newModel->section_id,
                        ])->all();

                    if (empty($studentDetails)) {
                        Yii::$app->session->setFlash('error', 'Selected class or section does not have any students.');
                        return $this->redirect(Yii::$app->request->referrer);
                    }

                    foreach ($studentDetails as $studentDetail) {
                        $examResult = ExamsResult::findOne([
                            'exam_scheduled_id' => $newModel->id,
                            'user_id' => $studentDetail->user_id
                        ]);

                        if (!$examResult) {
                            $examResult = new ExamsResult();
                        }

                        $examResult->campus_id = $newModel->campus_id;
                        $examResult->user_id = $studentDetail->user_id;
                        $examResult->student_id = $studentDetail->id;
                        $examResult->exam_id = $newModel->exam_id;
                        $examResult->academic_year_id = $newModel->session_id;
                        $examResult->class_id = $newModel->class_id;
                        $examResult->section_id = $newModel->section_id;
                        $examResult->subject_id = $newModel->subject_id;
                        $examResult->exam_scheduled_id = $newModel->id;
                        $examResult->total_marks = $newModel->max_marks;
                        $examResult->min_marks = $newModel->min_marks;
                        $examResult->attandance = ExamsResult::NOT_MARKED;
                        $examResult->status = ExamsResult::Marks_not_Updated;

                        if (!$examResult->save(false)) {
                            throw new \Exception('Error saving exam result.');
                        }
                    }
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Exam updated successfully.');
                return $this->redirect(['index']);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(Yii::$app->request->referrer);
        }

        // Render the update form with existing marks division data pre-filled
        return $this->render('update', [
            'model' => $model,
            'existingMarksDivisions' => $existingMarksDivisions,
            'allDivisions' => $allDivisions
        ]);
    }


    /**
     * Deletes an existing ExamSchedules model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {

            ScheduledExamMarksDevisionResults::deleteAll(['exam_schedule_id' => $id]);
            ScheduledExamMarksDevision::deleteAll(['exam_schedule_id' => $id]);

            $examResults = ExamsResult::find()->where(['exam_scheduled_id' => $id])->all();
            foreach ($examResults as $results) {
                ExamStudentMarksheet::deleteAll(['student_id' => $results->student_id]);
            }
            ExamsResult::deleteAll(['exam_scheduled_id' => $id]);
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = ExamSchedules::find()->where([
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
     * Finds the ExamSchedules model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExamSchedules the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExamSchedules::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }






    public function actionGeneratePdf()
    {

        $get = Yii::$app->request->get();
        // Fetch data for the PDF (similar to what you have in your loop)
        $studentTimeTable  = ExamSchedules::find()->where(['session_id' => (int)$get['academic_year'], 'exam_id' => (int)$get['exam_id'], 'class_id' => (int)$get['class']])->all();

        // $content = $this->renderPartial('time-table-pdf', ['studentTimeTable' => $studentTimeTable]);


        $content = $this->renderPartial('time-table-pdf', ['studentTimeTable' => $studentTimeTable]);

        // Set up PDF configuration
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // This is important for proper encoding
            'content' => $content,
            'options' => [
                'title' => 'Exam Time Table',
            ],
        ]);

        return $pdf->render();
    }
    public function actionExamHallTicket()
    {

        $model = new ExamSchedules();

        if ($model->loadAll(Yii::$app->request->post())) {
            $existingData = ExamSchedules::findOne([
                'session_id' => $model->session_id,
                'exam_id' => $model->exam_id,
                'class_id' => $model->class_id,
            ]);

            if ($existingData) {
                return $this->render('index-time-table', [
                    'data' => [
                        'session_id' => $existingData->session_id,
                        'exam_id' => $existingData->exam_id,
                        'class_id' => $existingData->class_id,
                        'exam_date' => $existingData->exam_date,
                        'exam_duration' => $existingData->exam_duration,
                    ],
                ]);
            } else {
                // Data doesn't exist, display the form

                return $this->render('_form_hall_ticket', [
                    'model' => $model,
                ]);
            }
        }

        // If the request is not a POST request or if the model fails to load, display the form
        return $this->render('_form_hall_ticket', [
            'model' => $model,
        ]);
    }
    public function actionGetStudentData()
    {
        $post = Yii::$app->request->post();
        $sessionId = $post["ExamSchedules"]["session_id"];
        // $examId = $post["ExamSchedules"]["exam_id"];
        $classId = $post["ExamSchedules"]["class_id"];
        $studentId =

            $examHallTicket = StudentDetails::find()->where(['academic_year_id' => (int)$sessionId,  'student_class_id' => (int)$classId, 'campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->all();
        // var_dump($examHallTicket);exit;
        return $this->renderPartial('view-hall-ticket', ['examHallTicket' => $examHallTicket, 'post' => $post]);
    }
    public function actionGenerateHallTicketPdf($id)
    {
        $get = Yii::$app->request->get();
        $academicYear = (int)$get['academic_year'];
        $classId = (int)$get['class'];

        $examHallTicketStudent = StudentDetails::findOne($id);
        $studentTimeTable = ExamSchedules::find()
            ->where([
                'session_id' => $academicYear,
                'exam_id' => (int)$get['exam_id'],
                'class_id' => $classId
            ])
            ->orderBy(['created_on' => SORT_ASC]) // Sorting by 'id' in ascending order
            ->all();


        // Check the campus_id to decide which template to render
        if ($examHallTicketStudent->campus_id == 72) {
            $content = $this->renderPartial('newera-hall-ticket-pdf', [
                'studentTimeTable' => $studentTimeTable,
                'examHallTicketStudent' => $examHallTicketStudent
            ]);
        } else {
            $content = $this->renderPartial('hall-ticket-pdf', [
                'studentTimeTable' => $studentTimeTable,
                'examHallTicketStudent' => $examHallTicketStudent
            ]);
        }

        // Define folder path to save the PDF
        $folderPath = Yii::getAlias('@webroot/uploads/hall_tickets/');
        if (!file_exists($folderPath)) {
            if (!mkdir($folderPath, 0777, true)) {
                throw new \Exception('Failed to create directory for saving PDF.');
            }
        }

        // Define the file path
        $pdfFileName = 'hall_ticket_' . $id . '.pdf';
        $pdfFilePath = $folderPath . $pdfFileName;

        // Create PDF instance
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_FILE,
            'filename' => $pdfFilePath,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => [],
            'methods' => [
                'SetHeader' => false,
                'SetFooter' => false,
            ]
        ]);

        // Render PDF
        $pdf->render();

        // Save the PDF file path into ExamHallTicket model
        $examHallTicket = new ExamHallTicket();
        $examHallTicket->academic_year_id = $examHallTicketStudent->academic_year_id;
        $examHallTicket->campus_id = $examHallTicketStudent->campus_id;
        $examHallTicket->student_detail_id = $examHallTicketStudent->id;
        $examHallTicket->student_user_id = $examHallTicketStudent->user_id;
        $examHallTicket->admission_no = $examHallTicketStudent->admission_number;
        $examHallTicket->hall_ticket_pdf = $pdfFilePath; // Save the file path
        $examHallTicket->status = 1; // Save the file path

        // Determine URL for the saved file
        $hostInfo = Yii::$app->request->hostInfo;
        $baseUrl = Yii::$app->request->baseUrl;
        $pdfUrl = $hostInfo . $baseUrl . Url::to('/uploads/hall_tickets/') . $pdfFileName;

        $examHallTicket->hall_ticket_pdf = $pdfUrl; // Save the file URL

        $examHallTicket->save(false); // Save without validation

        // Redirect user to the generated PDF
        return Yii::$app->response->sendFile($pdfFilePath, $pdfFileName, ['inline' => true]);
    }



    public function actionGenerateAllHallTicketsPdf()
    {
        $selectedIds = Yii::$app->request->post('selected');

        if (empty($selectedIds)) {
            throw new \Exception('No students selected.');
        }

        $pdfFiles = [];
        $folderPath = Yii::getAlias('@webroot/uploads/hall_tickets/');

        if (!file_exists($folderPath)) {
            if (!mkdir($folderPath, 0777, true)) {
                throw new \Exception('Failed to create directory for saving PDFs.');
            }
        }

        foreach ($selectedIds as $id) {
            $examHallTicketStudent = StudentDetails::findOne($id);
            // var_dump(Yii::$app->request->post('exam_id'));exit;

            $studentTimeTable = ExamSchedules::find()
                ->where([
                    'session_id' => $examHallTicketStudent->academic_year_id,
                    'exam_id' => Yii::$app->request->post('exam_id'),
                    'class_id' => $examHallTicketStudent->student_class_id,
                ])
                ->orderBy(['created_on' => SORT_ASC])
                ->all();


            // Render PDF content based on student campus ID
            if ($examHallTicketStudent->campus_id == 72) {
                $content = $this->renderPartial('newera-hall-ticket-pdf', [
                    'studentTimeTable' => $studentTimeTable,
                    'examHallTicketStudent' => $examHallTicketStudent
                ]);
            } else {
                $content = $this->renderPartial('hall-ticket-pdf', [
                    'studentTimeTable' => $studentTimeTable,
                    'examHallTicketStudent' => $examHallTicketStudent
                ]);
            }

            // Define PDF file path for each student
            $pdfFileName = 'hall_ticket_' . $id . '.pdf';
            $pdfFilePath = $folderPath . $pdfFileName;

            // Create PDF
            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'destination' => Pdf::DEST_FILE,
                'filename' => $pdfFilePath,
                'content' => $content,
            ]);

            $pdf->render();
            $pdfFiles[] = $pdfFilePath; // Store each PDF file path
        }

        // Compress all PDFs into a ZIP file
        $zip = new \ZipArchive();
        $zipFileName = $folderPath . 'hall_tickets.zip';

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($pdfFiles as $file) {
                if (file_exists($file)) { // Ensure the file exists before adding to ZIP
                    $zip->addFile($file, basename($file));
                } else {
                    throw new \Exception('PDF file does not exist: ' . $file);
                }
            }
            if (!$zip->close()) {
                throw new \Exception('Failed to close the ZIP file.');
            }
        } else {
            throw new \Exception('Failed to create ZIP file.');
        }

        // Send the ZIP file for download
        return Yii::$app->response->sendFile($zipFileName);
    }
}
