<?php

namespace app\modules\exammanagement\controllers;

use app\components\FirebaseNotification;
use app\modules\admin\models\ExamsResult;
use Yii;
use app\models\User;
use app\modules\admin\models\base\Exams;
use app\modules\admin\models\base\StudentClassAttendance;
use app\modules\admin\models\base\StudentDetails;
use app\modules\exammanagement\models\base\ExamSchedules;
use app\modules\exammanagement\models\base\MarksheetSetting;
use app\modules\exammanagement\models\FinalMarksheet;
use app\modules\exammanagement\models\search\FinalMarksheetSearch;
use kartik\mpdf\Pdf;
use Mpdf\Mpdf;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use yii\helpers\Url;

/**
 * FinalMarksheetController implements the CRUD actions for FinalMarksheet model.
 */
class FinalMarksheetController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'generate-pdf'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'generate-pdf'],
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
     * Lists all FinalMarksheet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinalMarksheetSearch();

        $get = Yii::$app->request->get('FinalMarksheetSearch');
        if (!empty($get["session_id"]) && !empty($get["class_id"]) && !empty($get["section_id"])) {

            $session_id = $get["session_id"];
            $class_id = $get["class_id"];
            $section_id = $get["section_id"];

            $examResults = ExamsResult::find()
                ->where(['academic_year_id' => (int)$session_id])
                ->andWhere(['class_id' => $class_id])
                ->andWhere(['section_id' => $section_id])
                ->groupBy('student_id')
                ->all();
            foreach ($examResults as $examResult) {

                $finalResult = FinalMarksheet::find()->where(['student_id' => $examResult->student_id])
                    ->andWhere(['session_id' => $examResult->academic_year_id])
                    ->andWhere(['class_id' => $examResult->class_id])
                    ->andWhere(['section_id' => $examResult->section_id])
                    ->one();
                if (empty($finalResult)) {
                    $finalResult =  new FinalMarksheet();
                    $finalResult->student_id = $examResult->student_id;
                    $finalResult->student_user_id = $examResult->user_id;
                    $finalResult->class_id = $examResult->class_id;
                    $finalResult->section_id = $examResult->section_id;
                    $finalResult->campus_id = $examResult->campus_id;
                    $finalResult->session_id = $examResult->academic_year_id;
                    $finalResult->marksheet_url = "";
                    $finalResult->status = FinalMarksheet::STATUS_ACTIVE;
                    $finalResult->save(false);
                } else {
                    $finalResult->student_id = $examResult->student_id;
                    $finalResult->student_user_id = $examResult->user_id;
                    $finalResult->class_id = $examResult->class_id;
                    $finalResult->section_id = $examResult->section_id;
                    $finalResult->campus_id = $examResult->campus_id;
                    $finalResult->session_id = $examResult->academic_year_id;
                    $finalResult->marksheet_url = "";
                    $finalResult->status = FinalMarksheet::STATUS_ACTIVE;
                    $finalResult->save(false);
                }
            }
        }



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
     * Displays a single FinalMarksheet model.
     * @param integer $id
     * @return mixed
     */
    // public function actionView($id)
    // {
    //     $model = $this->findModel($id);



    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        // Fetch all exam schedules for the student's session and class
        $exams = ExamSchedules::find()
            ->where(['session_id' => $model->session_id])
            ->andWhere(['class_id' => $model->class_id])
            ->andWhere(['section_id' => $model->section_id])
            ->groupBy('exam_id')
            ->all();

        // Fetch all exam results for the student
        $examResults = ExamsResult::find()
            ->where(['student_id' => $model->student_id])
            ->all();

        return $this->render('view', [
            'model' => $model,
            'exams' => $exams,
            'examResults' => $examResults,
        ]);
    }


    public function actionGeneratePdf($id)
    {
        $studentid = FinalMarksheet::find()->where(['id' => $id])->one();
        if (!$studentid) {
            throw new \Exception("Final Marksheet not found for ID: $id");
        }
    
        $studentDetails = StudentDetails::find()->where(['id' => $studentid->student_id])->one();
        // var_dump($studentDetails);exit;
        if (empty($studentDetails)) {
            throw new \Exception("Student Details Not Found for ID: " . $studentid->student_id);
        }
    
        $getMarksheetTemplateSettings = MarksheetSetting::find()->where(['campus_id' => $studentDetails->campus_id])->one();
        if (!$getMarksheetTemplateSettings) {
            throw new \Exception("Missing Marksheet Template Settings for Campus ID: " . $studentDetails->campus_id);
        }
    
        $exams = Exams::find()
            ->andWhere(['campus_id' => $studentDetails->campus_id])
            ->orderBy(['created_on' => SORT_ASC])
            ->all();
    
        if (empty($exams)) {
            throw new \Exception("No Exams Found for Student ID: " . $studentid->student_id);
        }
    
        $subjects = [];
        $subjectWiseMarks = [];
        $totalMarksPerSubject = [];
        $totalMaxMarksPerSubject = [];
        $totalMarksPerExam = [];
    
        foreach ($exams as $exam) {
            $examResults = ExamsResult::find()
                ->where(['exam_id' => $exam->id, 'student_id' => $studentDetails->id])
                ->all();
    
            if (empty($examResults)) {
                continue;
            }
    
            foreach ($examResults as $result) {
                $subjectName = $result->subject->subject_name;
                $subjects[$subjectName] = $subjectName;
    
                if (!isset($subjectWiseMarks[$subjectName])) {
                    $subjectWiseMarks[$subjectName] = [];
                    $totalMarksPerSubject[$subjectName] = 0;
                    $totalMaxMarksPerSubject[$subjectName] = 0;
                }
    
                $subjectWiseMarks[$subjectName][$exam->name_of_exam] = [
                    'obtained' => $result->marks_scored,
                    'max' => $result->total_marks,
                    'grade' => $result->grade
                ];
    
                $totalMarksPerSubject[$subjectName] += $result->marks_scored;
                $totalMaxMarksPerSubject[$subjectName] += $result->total_marks;
    
                if (!isset($totalMarksPerExam[$exam->name_of_exam])) {
                    $totalMarksPerExam[$exam->name_of_exam] = 0;
                }
                $totalMarksPerExam[$exam->name_of_exam] += $result->marks_scored;
            }
        }
    
        $totalObtainedMarks = array_sum($totalMarksPerSubject);
        $totalMaxMarks = array_sum($totalMaxMarksPerSubject);
        $totalPercentage = ($totalMaxMarks > 0) ? ($totalObtainedMarks / $totalMaxMarks) * 100 : 0;
    
        $currentYear = date('Y');
        $currentMonth = date('m');
        $lastDayOfMonth = date("Y-m-t", strtotime("$currentYear-$currentMonth-01"));
    
        $attendanceData = StudentClassAttendance::find()
            ->select([
                new Expression('YEAR(date) as year'),
                new Expression('MONTH(date) as month'),
                new Expression('COUNT(DISTINCT date) as total_working_days'),
                new Expression('SUM(CASE WHEN status = ' . StudentClassAttendance::STATUS_PRESENT . ' THEN 1 ELSE 0 END) as total_present_days')
            ])
            ->where(['student_id' => $studentDetails->id])
            ->andWhere(['academic_year_id' => $studentDetails->academic_year_id])
            ->andWhere(['<=', 'date', $lastDayOfMonth])
            ->groupBy(new Expression('YEAR(date), MONTH(date)'))
            ->orderBy(new Expression('YEAR(date), MONTH(date)'))
            ->asArray()
            ->all();
    
        $attendanceReport = [];
        foreach ($attendanceData as $data) {
            $monthName = date("F", mktime(0, 0, 0, $data['month'], 10));
            $attendanceReport[] = [
                'month' => $monthName,
                'working_days' => (int)$data['total_working_days'],
                'present_days' => (int)$data['total_present_days']
            ];
        }
    
        $structure = [
            'header_image_url' => $getMarksheetTemplateSettings->marksheet_header_image,
            'profile_image' => $studentDetails->profile_photo,
            'student_details' => [
                'student_name' => $studentDetails->student_name,
                'father_name' => $studentDetails->parent->name_of_the_father,
                'mother_name' => $studentDetails->parent->name_of_the_mother ?? "N/A",
                'gender' => $studentDetails->user->gender ?? "N/A",
                'id_no' => $studentDetails->id ?? "N/A",
                'class' => $studentDetails->studentClass->title ?? "N/A",
                'session' => $studentDetails->session->title ?? "N/A",
                'date' => date('d-M-Y')
            ],
            'exams' => array_column($exams, 'name_of_exam'),
            'subject_marks' => $subjectWiseMarks,
            'subjects' => $subjects,
            'total_marks_per_subject' => $totalMarksPerSubject,
            'total_max_marks_per_subject' => $totalMaxMarksPerSubject,
            'total_obtained_marks' => $totalObtainedMarks,
            'total_max_marks' => $totalMaxMarks,
            'percentage' => $totalPercentage,
            'attendance' => $attendanceReport,
            'total_marks_per_exam' => $totalMarksPerExam,
        ];
    
        // Output the final JSON structure
        $finalStructure = json_encode($structure, JSON_PRETTY_PRINT);

        $generatePdf = (new FirebaseNotification())->generateFinalMarksheetPdf($finalStructure);
// var_dump($generatePdf);exit;
        return $generatePdf;
    }
    

    
        
    
    

    


    /**
     * Creates a new FinalMarksheet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinalMarksheet();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinalMarksheet model.
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
     * Deletes an existing FinalMarksheet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = FinalMarksheet::STATUS_DELETE;
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
            $model = FinalMarksheet::find()->where([
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
     * Finds the FinalMarksheet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinalMarksheet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinalMarksheet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
