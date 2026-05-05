<?php

namespace app\modules\admin\controllers;

use app\modules\exammanagement\models\base\ScheduledExamMarksDevisionResults;
use Yii;
use app\models\User;
use app\modules\admin\models\base\AcademicYears;
use app\modules\admin\models\base\ClassSections;
use app\modules\admin\models\base\Exams;
use app\modules\admin\models\base\StudentClass;
use app\modules\admin\models\ExamsResult;
use app\modules\admin\models\search\ExamsResultSearch;
use app\modules\exammanagement\models\base\GradeDefination;
use app\modules\exammanagement\models\base\ScheduledExamMarksDevision;
use app\modules\exammanagement\models\base\TeacherClassAndSubjects;
use app\modules\exammanagement\models\Grade;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;



/**
 * ExamsResultController implements the CRUD actions for ExamsResult model.
 */
class ExamsResultController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update-all-grade', 'update', 'delete', 'update-status', 'get-percentage', 'get-subjects', 'get-scheduled-marks', 'save-marks'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-all-grade', 'update-status', 'get-percentage', 'get-subjects', 'get-scheduled-marks', 'save-marks'],
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
    public function actionGetScheduledMarks($exam_schedule_id)
    {
        $marks = ScheduledExamMarksDevision::find()
            ->where(['exam_schedule_id' => $exam_schedule_id])
            ->all();

        $data = [];
        foreach ($marks as $mark) {
            $data[] = [
                'id' => $mark->id,
                'mark' => "",
                'non_editable_field' => $mark->marksDevision->title,
                'devision_id' => $mark->marksDevision->id,
                'max_marks_devision' => $mark->max_marks_devision,

            ];
        }

        return $this->asJson(['status' => 'OK', 'data' => $data]);
    }

    public function actionSaveMarks()
    {
        // Retrieve POST data
        $post = Yii::$app->request->post();
        // var_dump($post);
        // exit;

        // Extract data from POST request
        $student_id = Yii::$app->request->post('student_id');
        $exam_id = Yii::$app->request->post('exam_id');
        $exam_scheduled_id = Yii::$app->request->post('exam_scheduled_id');
        $exam_result_id = Yii::$app->request->post('exam_result_id');
        $marks = Yii::$app->request->post('marks');

        $totalMarksScored = 0;
        foreach ($marks as $mark_data) {
            // Check if the record already exists
            $existingRecord = ScheduledExamMarksDevisionResults::find()
                ->where(['student_id' => $student_id])
                ->andWhere(['exam_result_id' => $exam_result_id])
                ->andWhere(['marks_devision_id' => $mark_data['devision_id']])
                ->one();

            if ($existingRecord) {
                // Update existing record
                $existingRecord->marks_scored = $mark_data['mark'];
                $existingRecord->marks_devision_id = $mark_data['devision_id'];
                $existingRecord->exam_schedule_id = $exam_scheduled_id;
                $existingRecord->scheduled_exam_devision_id = $mark_data['id']; // Use the mark record id if relevant
                $existingRecord->status = 1; // Set status as needed (example status)
                $existingRecord->updated_on = date('Y-m-d H:i:s');
                $existingRecord->update_user_id = Yii::$app->user->id; // Assuming you have user management
                $existingRecord->save(false);
                $totalMarksScored += $existingRecord->marks_scored;
            } else {
                // Create new record
                $newRecord = new ScheduledExamMarksDevisionResults();
                $newRecord->exam_result_id = $exam_result_id;
                $newRecord->student_id = $student_id;
                $newRecord->marks_devision_id = $mark_data['devision_id'];
                $newRecord->exam_schedule_id = $exam_scheduled_id;
                $newRecord->scheduled_exam_devision_id = $mark_data['id']; // Use the mark record id if relevant
                $newRecord->marks_scored = $mark_data['mark'];
                $newRecord->status = 1; // Set status as needed (example status)
                $newRecord->created_on = date('Y-m-d H:i:s');
                $newRecord->update_user_id = Yii::$app->user->id; // Assuming you have user management
                $newRecord->create_user_id = Yii::$app->user->id; // Assuming you have user management
                $newRecord->save(false);
                $totalMarksScored += $newRecord->marks_scored;
            }
        }
        // *********** checking for the grade **********//



        $examResult = ExamsResult::find()->where(['exams_result_id' => $exam_result_id])->one();
        $grade = Grade::find()->where(['campus_id' => (new User())->getCampusId()])
            ->andWhere(['status' => Grade::STATUS_ACTIVE])
            ->andWhere(['maximum_exam_marks' => $examResult->total_marks])
            ->andWhere(['section_id' => $examResult->section_id])
            ->one();

        $examResult->marks_scored = $totalMarksScored;


        $examResult->pecentage = ($totalMarksScored / $examResult->total_marks) * 100;
        if (!empty($grade)) {
            $gradeDefination = GradeDefination::find()
                ->where(['grade_id' => $grade->id])
                ->andWhere(['<=', 'min_marks', $totalMarksScored])
                ->andWhere(['>=', 'max_marks', $totalMarksScored])
                ->one();



            $examResult->grade = $gradeDefination->grade;
            $examResult->cgpa = $gradeDefination->cgpa;
        }


        $examResult->save(false);





        return $this->asJson(['status' => 'OK']);
    }



    public function actionUpdateAllGrade()
{
    $post = Yii::$app->request->post();
    $sectionId = Yii::$app->request->post('sectionId');
    $exam_id   = Yii::$app->request->post('examid');

    $examResults = ExamsResult::find()
        ->where(['exam_id' => $exam_id, 'section_id' => $sectionId])
        ->all();

    if (!empty($examResults)) {
        foreach ($examResults as $examResult) {
            $totalMarksScored = $examResult->marks_scored ?? 0;

            $grade = Grade::find()
                ->where([
                    'campus_id' => (new User())->getCampusId(),
                    'status'    => Grade::STATUS_ACTIVE,
                    'maximum_exam_marks' => $examResult->total_marks,
                    'section_id' => $examResult->section_id
                ])
                ->one();

            if ($grade) {
                $gradeDefination = GradeDefination::find()
                    ->where(['grade_id' => $grade->id])
                    ->andWhere(['<=', 'min_marks', $totalMarksScored])
                    ->andWhere(['>=', 'max_marks', $totalMarksScored])
                    ->one();
                        //    var_dump($grade);exit;
                if ($gradeDefination) {
                    $examResult->grade = $gradeDefination->grade;
                    $examResult->cgpa  = $gradeDefination->cgpa;
                    $examResult->save(false);
                }
            }
        }
    }

    return $this->asJson(['status' => 'OK']);
}





    /**
     * Lists all ExamsResult models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExamsResultSearch();
        $get = Yii::$app->request->get();
        $exams_result_count = 0;
        $academicYearTitle = '';
        $nameOfExam = '';
        $className = '';
        $sectionName = '';
        if (!empty($get)) {

            $academic_year_id = $get['ExamsResultSearch']['academic_year_id'];
            $class_id = $get['ExamsResultSearch']['class_id'];
            if (!isset($get['ExamsResultSearch']['section_id']) || empty($get['ExamsResultSearch']['section_id'])) {
                Yii::$app->session->setFlash('error', 'Please Re-Select the Class and Section');
                return $this->redirect(Yii::$app->request->referrer);
            }
            $section_id = $get['ExamsResultSearch']['section_id'];

            $exam_id = $get['ExamsResultSearch']['exam_id'];
            // $percentage_or_gpa = $get['ExamsResultSearch']['percentage_or_gpa'];
            $academicYearTitle = AcademicYears::find()->where(['id' => $academic_year_id])->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->one();
            $nameOfExam = Exams::find()->where(['id' => $exam_id])->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->one();
            $className = StudentClass::find()->where(['id' => $class_id])->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->one();
            $sectionName = ClassSections::find()->where(['id' => $section_id])->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->one();
        }


        if (!empty($percentage_or_gpa)) {

            $exams_result_count = ExamsResult::find()
                ->where(['academic_year_id' => $academic_year_id])
                ->andWhere(['class_id' => $class_id])
                ->andWhere(['section_id' => $section_id])
                ->andWhere(['exam_id' => $exam_id])
                ->andWhere(['between', 'percentage_or_gpa', 1, $percentage_or_gpa])
                ->count();
        }

        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        }


        if (Yii::$app->request->post('hasEditable')) {
            $postId = Yii::$app->request->post('editableKey');
            $model = ExamsResult::findOne($postId);

            $out = Json::encode(['output' => '', 'message' => '']);
            $post = [];
            $posted = current($_POST['ExamsResult']);
            $post['ExamsResult'] = $posted;

            if ($model->load($post)) {
                $output = '';

                // Update Marks Scored
                if (isset($post['ExamsResult']['marks_scored']) && $post['ExamsResult']['marks_scored'] !== "") {
                    $marksScored = $post['ExamsResult']['marks_scored'];
                    $oldScore = $model->marks_scored;

                    if ($marksScored > $model->total_marks) {
                        $out = Json::encode(['output' => (float)$oldScore, 'message' => 'Marks entered cannot be greater than total marks']);
                    } elseif ($marksScored == 0) {
                        $model->pecentage = 0;
                        $out = Json::encode(['output' => (float)$oldScore, 'message' => 'Marks Cannot be 0']);
                    } elseif ($marksScored > 0) {
                        $model->marks_scored = $marksScored;
                        $model->status = ExamsResult::MARKS_UPDATED;
                        $model->attandance = ExamsResult::PRESENT;
                        $pecentage = ($marksScored / $model->total_marks) * 100;
                        $model->pecentage = number_format($pecentage, 1);
                        $model->save(false);

                        $output = (float)$marksScored;
                        $out = Json::encode(['output' => (float)$output, 'message' => '']);
                    } else {
                        $out = Json::encode(['output' => (float)$oldScore, 'message' => 'Something Went Wrong']);
                    }
                }

                // Update Marks Type
                if (isset($post['ExamsResult']['marks_type']) && $post['ExamsResult']['marks_type'] !== "") {
                    $marksType = $post['ExamsResult']['marks_type'];
                    $model->marks_type = $marksType;
                    $model->save(false);
                    $out = Json::encode(['output' => strip_tags($model->getMarksTypeBadges()), 'message' => '']);
                }

                // Update Grade
                if (isset($post['ExamsResult']['grade']) && $post['ExamsResult']['grade'] !== "") {
                    $grade = $post['ExamsResult']['grade'];
                    $model->grade = $grade;
                    $model->save(false);
                    $out = Json::encode(['output' => $model->grade, 'message' => '']);
                }

                // Update CGPA
                if (isset($post['ExamsResult']['cgpa']) && $post['ExamsResult']['cgpa'] !== "") {
                    $cgpa = $post['ExamsResult']['cgpa'];
                    $oldScore = $model->cgpa;

                    if ($cgpa > 10) {
                        $out = Json::encode(['output' => $oldScore, 'message' => 'CGPA Cannot be greater than 10.']);
                    } else {
                        $model->cgpa = $cgpa;
                        $model->save(false);
                        $out = Json::encode(['output' => $model->cgpa, 'message' => '']);
                    }
                }
            }

            return $out;
        }



        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'count' => $exams_result_count,
            'academicYearTitle' => $academicYearTitle,
            'nameOfExam' => $nameOfExam,
            'className' => $className,
            'sectionName' => $sectionName

        ]);
    }

    /**
     * Displays a single ExamsResult model.
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
     * Creates a new ExamsResult model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExamsResult();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->exams_result_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ExamsResult model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->exams_result_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ExamsResult model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
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
            $model = ExamsResult::find()->where([
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
     * Finds the ExamsResult model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExamsResult the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExamsResult::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    function actionGetPercentage($id)
    {
        $data = [];
        $examResults = ExamsResult::find()->where(['exams_result_id' => $id])->one();
        if (!empty($examResults)) {

            if(!empty($examResults->pecentage) || $examResults->pecentage !=0){
                $data['status'] = "OK";
                $data['Detail'] = $examResults->pecentage;
            }else{
            if($examResults->total_marks !=0 && $examResults->marks_scored != 0){
             $pecentage =    ($examResults->marks_scored*100)/$examResults->total_marks;
             $examResults->pecentage = round($pecentage,2);
             $examResults->save(false);
             $data['status'] = "OK";
            $data['Detail'] = $examResults->pecentage;
            }else{
                $data['status'] = "OK";
                $data['Detail'] = 0;
            }
            }
           
        } else {
            $data['status'] = "NOK";
            $data['error'] = "Error Finding Percentage";
        }

        return json_encode($data);
    }
    public function actionGetSubjects()
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
                $getSub = (new TeacherClassAndSubjects())->getSubjects($type);

                // var_dump($out);exit;
                // return $out;
                return  Json::encode($getSub);
            }
        }
    }
}
