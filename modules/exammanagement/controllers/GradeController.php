<?php

namespace app\modules\exammanagement\controllers;

use app\modules\exammanagement\models\base\GradeDefination;
use Yii;
use app\models\User;
use app\modules\admin\models\base\ClassSections;
use app\modules\exammanagement\models\Grade;
use app\modules\exammanagement\models\search\GradeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GradeController implements the CRUD actions for Grade model.
 */
class GradeController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-grade-defination'],
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
     * Lists all Grade models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GradeSearch();





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
     * Displays a single Grade model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerGradeDefination = new \yii\data\ArrayDataProvider([
            'allModels' => $model->gradeDefinations,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerGradeDefination' => $providerGradeDefination,
        ]);
    }

    /**
     * Creates a new Grade model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Grade();

        if ($model->loadAll(Yii::$app->request->post())) {

            if (empty($model["gradeDefinations"])) {
                Yii::$app->session->setFlash('error', "At least add one grade definition");
                return $this->redirect(Yii::$app->request->referrer);
            }

            if (!empty($model["section_id"])) {
                // Start a database transaction
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $firstGradeId = null; // To track the first created Grade ID
                    foreach ($model["section_id"] as $sectionId) {
                        // Find the section
                        $section = ClassSections::find()->where(['id' => $sectionId])->andWhere(['status' => ClassSections::STATUS_ACTIVE])->one();

                        if ($section === null) {
                            throw new \Exception("Section not found or inactive");
                        }

                        // Create a new Grade instance for each section
                        $gradeModel = new Grade();
                        $gradeModel->attributes = $model->attributes;
                        $gradeModel->campus_id = (new User)->getCampusId();
                        $gradeModel->section_id = $sectionId;

                        if ($gradeModel->save(false)) {
                            // Store the first created Grade ID for redirection
                            if ($firstGradeId === null) {
                                $firstGradeId = $gradeModel->id;
                            }

                            // Save grade definitions
                            foreach ($model["gradeDefinations"] as $gradeDefination) {
                                if (!isset($gradeDefination['max_marks'], $gradeDefination['min_marks'], $gradeDefination['grade'], $gradeDefination['cgpa'])) {
                                    throw new \Exception("Incomplete grade definition data");
                                }

                                $defination = new GradeDefination();
                                $defination->grade_id = $gradeModel->id;
                                $defination->section_id = $gradeModel->section_id;
                                $defination->campus_id = (new User)->getCampusId();
                                $defination->max_marks = $gradeDefination['max_marks'];
                                $defination->min_marks = $gradeDefination['min_marks'];
                                $defination->grade = $gradeDefination['grade'];
                                $defination->cgpa = $gradeDefination['cgpa'];
                                $defination->status = GradeDefination::STATUS_ACTIVE;

                                if (!$defination->save(false)) {
                                    throw new \Exception("Failed to save grade definition for grade ID: " . $gradeModel->id);
                                }
                            }
                        } else {
                            throw new \Exception("Failed to save grade for section ID: " . $sectionId);
                        }
                    }

                    // Commit transaction if everything is successful
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Grade and definitions created successfully");

                    // Redirect to the view page of the first created Grade ID
                    return $this->redirect(['view', 'id' => $firstGradeId]);
                } catch (\Exception $e) {
                    // Rollback the transaction in case of error
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    return $this->redirect(Yii::$app->request->referrer);
                }
            } else {
                Yii::$app->session->setFlash('error', "Please select the class and section");
                return $this->redirect(Yii::$app->request->referrer);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Updates an existing Grade model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {

            if (empty($model["gradeDefinations"])) {
                Yii::$app->session->setFlash('error', "At least add one grade definition");
                return $this->redirect(Yii::$app->request->referrer);
            }

            // Start a database transaction
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Save the Grade model itself
                if ($model->save(false)) {

                    // Delete existing GradeDefination entries for this grade (to update them)
                    GradeDefination::deleteAll(['grade_id' => $model->id]);

                    // Save the updated GradeDefination records
                    foreach ($model["gradeDefinations"] as $gradeDefination) {
                        if (!isset($gradeDefination['max_marks'], $gradeDefination['min_marks'], $gradeDefination['grade'], $gradeDefination['cgpa'])) {
                            throw new \Exception("Incomplete grade definition data");
                        }

                        $defination = new GradeDefination();
                        $defination->grade_id = $model->id;
                        $defination->section_id = $model->section_id;
                        $defination->campus_id = $model->campus_id;
                        $defination->max_marks = $gradeDefination['max_marks'];
                        $defination->min_marks = $gradeDefination['min_marks'];
                        $defination->grade = $gradeDefination['grade'];
                        $defination->cgpa = $gradeDefination['cgpa'];
                        $defination->status = GradeDefination::STATUS_ACTIVE;

                        if (!$defination->save(false)) {
                            throw new \Exception("Failed to save grade definition for grade ID: " . $model->id);
                        }
                    }

                    // Commit the transaction if everything is successful
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Grade and definitions updated successfully");

                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    throw new \Exception("Failed to save grade");
                }
            } catch (\Exception $e) {
                // Rollback the transaction in case of error
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(Yii::$app->request->referrer);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Grade model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        GradeDefination::deleteAll(['grade_id'=>$id]);


        $model = $this->findModel($id);
       
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (! empty($post['id'])) {
            $model = Grade::find()->where([
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
     * Finds the Grade model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Grade the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Grade::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for GradeDefination
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddGradeDefination()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('GradeDefination');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formGradeDefination', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
