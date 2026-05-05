<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\base\StudentAnswers;
use app\modules\admin\models\base\StudentDetails;
use Yii;
use app\models\User;
use app\modules\admin\models\AssessmentResults;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\OnlineAssessment;
use app\modules\admin\models\Question;
use app\modules\admin\models\OnlineAssessmentSearch;
use app\modules\admin\models\QuestionOption;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * OnlineAssessmentController implements the CRUD actions for OnlineAssessment model.
 */
class OnlineAssessmentController extends Controller
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
     * Lists all OnlineAssessment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OnlineAssessmentSearch();





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
     * Displays a single OnlineAssessment model.
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
     * Creates a new OnlineAssessment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OnlineAssessment();
        // Always start with one question model for the form
        $questionModels = [new Question()];

        // Begin a DB transaction
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Get posted questions array (names used in the view)
                $questionsData = Yii::$app->request->post('questions');

                if (!empty($questionsData) && is_array($questionsData)) {
                    foreach ($questionsData as $index => $questionData) {
                        // Proceed only if a question text is provided
                        if (!empty($questionData['question_text'])) {
                            $question = new Question();
                            $question->assessment_id = $model->id;
                            $question->campus_id = $model->campus_id;
                            $question->question_text = $questionData['question_text'];
                            $question->marks = $questionData['marks'];

                            // Validate and map type string to DB integer
                            if (isset($questionData['type'])) {
                                if ($questionData['type'] == 'MCQ') {
                                    $question->type = 1;
                                } elseif ($questionData['type'] == 'True/False') {
                                    $question->type = 2;
                                } else {
                                    $question->type = 3; // Text or Written type
                                }
                            } else {
                                throw new Exception("Question type is not set for question index {$index}.");
                            }

                            $question->status = $model->status; // or set appropriate status

                            if (!$question->save(false)) {
                                throw new Exception("Failed to save question at index {$index}.");
                            }

                            // Process options if question is MCQ or True/False
                            if ($questionData['type'] == 'MCQ') {
                                if (isset($questionData['options']) && is_array($questionData['options'])) {
                                    foreach ($questionData['options'] as $optionIndex => $optionData) {
                                        if (!empty($optionData['text'])) {
                                            $questionOption = new QuestionOption();
                                            $questionOption->question_id = $question->id;
                                            $questionOption->campus_id = $model->campus_id;
                                            $questionOption->option_text = $optionData['text'];
                                            // Check if this option is the selected correct one
                                            $questionOption->is_correct = (isset($questionData['correct_option']) && $questionData['correct_option'] == $optionIndex) ? 1 : 2;
                                            $questionOption->status = 1; // or assign as needed
                                            if (!$questionOption->save(false)) {
                                                throw new Exception("Failed to save option for question id {$question->id}.");
                                            }
                                        }
                                    }
                                } else {
                                    throw new Exception("Options not provided for MCQ question at index {$index}.");
                                }
                            } elseif ($questionData['type'] == 'True/False') {
                                // For True/False, create two options: one for True and one for False.
                                $trueOption = new QuestionOption();
                                $trueOption->question_id = $question->id;
                                $trueOption->campus_id = $model->campus_id;
                                $trueOption->option_text = 'True';
                                $trueOption->is_correct = (isset($questionData['correct_option']) && $questionData['correct_option'] == 'True') ? 1 : 2;
                                $trueOption->status = 1;
                                if (!$trueOption->save(false)) {
                                    throw new Exception("Failed to save True option for question id {$question->id}.");
                                }

                                $falseOption = new QuestionOption();
                                $falseOption->question_id = $question->id;
                                $falseOption->campus_id = $model->campus_id;
                                $falseOption->option_text = 'False';
                                $falseOption->is_correct = (isset($questionData['correct_option']) && $questionData['correct_option'] == 'False') ? 1 : 2;
                                $falseOption->status = 1;
                                if (!$falseOption->save(false)) {
                                    throw new Exception("Failed to save False option for question id {$question->id}.");
                                }
                            }

                            // Process student answers
                            $getStudents = StudentDetails::find()->where(['section_id' => $model->section_id])->all();
                            if (!empty($getStudents)) {
                                foreach ($getStudents as $student) {
                                    $studentAnswers = new StudentAnswers();
                                    $studentAnswers->question_id = $question->id;
                                    $studentAnswers->assessment_id = $model->id;
                                    $studentAnswers->student_id = $student->id;
                                    $studentAnswers->selected_option_id = 0;
                                    $studentAnswers->answer_text = "";
                                    $studentAnswers->marks_awarded = 0;
                                    $studentAnswers->status = StudentAnswers::STATUS_TEST_PENDING;
                                    if (!$studentAnswers->save(false)) {
                                        throw new Exception("Failed to save student answer for student id {$student->id}.");
                                    }
                                }
                            }

                            // Process student assessment results
                            if (!empty($getStudents)) {
                                foreach ($getStudents as $student) {
                                    $results = new AssessmentResults();
                                    $results->assessment_id = $model->id;
                                    $results->student_id = $student->id;
                                    $results->total_marks = $model->total_marks;
                                    $results->marks_scored = 0;
                                    $results->start_time = "";
                                    $results->end_time = "";
                                    $results->last_attempt_question_id = 0;
                                    $results->test_completed = AssessmentResults::TEST_NOT_COMPLETED;
                                    $results->status = AssessmentResults::STATUS_ACTIVE;
                                    if (!$results->save(false)) {
                                        throw new Exception("Failed to save assessment results for student id {$student->id}.");
                                    }
                                }
                            }
                        }
                    }
                }

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // throw new Exception("Failed to load or save the Online Assessment model.");
            }
        } catch (Exception $e) {
            // Roll back all DB changes if any error occurred
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->render('create', [
            'model' => $model,
            'questionModels' => $questionModels,
        ]);
    }




    /**
     * Updates an existing OnlineAssessment model.
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
     * Deletes an existing OnlineAssessment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = OnlineAssessment::STATUS_DELETE;
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
            $model = OnlineAssessment::find()->where([
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
     * Finds the OnlineAssessment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OnlineAssessment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OnlineAssessment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    // public function actionSubjectList($campus_id)
    // {
    //     $subjects = \app\modules\admin\models\Subjects::find()->where(['campus_id' => $campus_id])->all();
    //     return json_encode(ArrayHelper::map($subjects, 'id', 'name'));
    // }

    // public function actionAcademicYearList($campus_id)
    // {
    //     $years = \app\modules\admin\models\AcademicYears::find()->where(['campus_id' => $campus_id])->all();
    //     return json_encode(ArrayHelper::map($years, 'id', 'title'));
    // }

    public function actionSectionList($campus_id)
    {
        $sections = ClassSections::find()->where(['campus_id' => $campus_id])->all();
        return json_encode(ArrayHelper::map($sections, 'id', 'name'));
    }
}
