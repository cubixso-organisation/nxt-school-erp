<?php

namespace app\modules\admin\controllers;

use yii\helpers\Url;
use Yii;
use app\models\User;
use app\modules\admin\models\base\ClassSections;
use app\modules\admin\models\base\FeesTyps;
use app\modules\admin\models\base\PayFees;
use app\modules\admin\models\Campus;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\search\FeeStructuresSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FeeStructuresController implements the CRUD actions for FeeStructures model.
 */
class FeeStructuresController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-assign-fee-to-student', 'add-pay-fees', 'class-section-data', 'balance-sheet', 'fee-structure', 'update-fee', 'class-section-data-fee','student-data'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-assign-fee-to-student', 'add-pay-fees', 'class-section-data', 'balance-sheet', 'fee-structure', 'update-fee', 'class-section-data-fee','student-data'],
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
     * Lists all FeeStructures models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FeeStructuresSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionBalanceSheet()
    {
        $searchModel = new FeeStructuresSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }
        return $this->render('fees_reports', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }




    /**
     * Displays a single FeeStructures model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $providerPayFees = new \yii\data\ArrayDataProvider([
            'allModels' => $model->payFees,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerPayFees' => $providerPayFees,
        ]);
    }

    /**
     * Creates a new FeeStructures model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FeeStructures();

        $searchModel = new FeeStructuresSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
            $model->status = FeeStructures::STATUS_ACTIVE;

            // Get selected student classes and sections
            $studentClasses = (array)$model->student_class_id;
            $classSections = (array)$model->class_section_id;

            // Iterate over each combination and check for existence before saving
            foreach ($studentClasses as $studentClass) {
                foreach ($classSections as $classSectionId) {
                    $classSection  = ClassSections::find()->where(['student_class_id' => $studentClass])->all();
                    $existingRecord = FeeStructures::find()
                        ->where(['student_class_id' => $classSectionId, 'class_section_id' => $studentClass, 'fee_type_id' => $model->fee_type_id])
                        ->exists();


                    if (!$existingRecord) {

                        foreach ($classSection as $classSec) {

                            if ($classSec->id == $classSectionId) {
                                $newModel = new FeeStructures();
                                $newModel->campus_id = $model->campus_id;
                                $newModel->title = $model->title;
                                $newModel->fee_type_id = $model->fee_type_id;
                                $newModel->student_class_id = $studentClass;
                                $newModel->class_section_id = $classSec->id;
                                $newModel->fee = $model->fee;
                                $newModel->maximum_detuction = $model->maximum_detuction;
                                $newModel->status = FeeStructures::STATUS_ACTIVE;
                                $newModel->save(false);
                            }
                        }
                    }
                }
            }

            return $this->redirect(['create']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }



    public function actionFeeStructure()
    {
        $model = new FeeStructures();

        $get_fee_structs = FeeStructures::find()->where(['campus_id' => User::getCampusId(\Yii::$app->user->identity->id)])->andWhere(['status' => FeesTyps::STATUS_ACTIVE])->all();
        // if ($model->loadAll(Yii::$app->request->post())) {

        //     $model->campus_id  =  User::getCampusesByUser(Yii::$app->user->identity->id);
        //     $model->status = FeeStructures::STATUS_ACTIVE;
        //     $model->saveAll();

        //     return $this->redirect(['create']);
        // } else {
        return $this->render('fee_structure', [
            'model' => $model,
            // 'searchModel' => $searchModel,
            // 'dataProvider' => $dataProvider,
            'get_fee_structs' => $get_fee_structs
        ]);
    }

    /**
     * Updates an existing FeeStructures model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $searchModel = new FeeStructuresSearch();
    
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusSearch(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } elseif (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }
    
        $model = $this->findModel($id);
    
        if ($model->load(Yii::$app->request->post())) {
            // Check if student_class_id and class_section_id are arrays and get the first value if they are
            $postData = Yii::$app->request->post('FeeStructures');
            $model->student_class_id = is_array($postData['student_class_id']) ? $postData['student_class_id'][0] : $postData['student_class_id'];
            $model->class_section_id = is_array($postData['class_section_id']) ? $postData['class_section_id'][0] : $postData['class_section_id'];
    
            // Assign campus ID
            $model->campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
    
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
    
        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    

    


    /**
     * Deletes an existing FeeStructures model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            // Set the status of $model to FeeStructures::STATUS_DELETE
            $model->status = FeeStructures::STATUS_DELETE;

            // Retrieve all PayFees records where fee_structures_id matches $id
            $student_details = PayFees::find()->where(['fee_structures_id' => $id])->all();

            // Iterate over each $student_detail
            foreach ($student_details as $student_detail) {
                // Set the status of $student_detail to PayFees::STATUS_INACTIVE


                // Save $student_detail without validation
                $student_detail->delete(false);
            }

            // Save $model without validation
            $model->save(false);
        }


        return $this->redirect(['create']);
    }


    public function actionStudentData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $class_id = $parents[0];
                $out = (new FeeStructures())->getSectionData($class_id);
                return $out;
            }
        }

        return $out;
    }
    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = FeeStructures::find()->where([
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
     * Finds the FeeStructures model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FeeStructures the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeeStructures::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for AssignFeeToStudent
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddAssignFeeToStudent()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('AssignFeeToStudent');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formAssignFeeToStudent', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for PayFees
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddPayFees()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('PayFees');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formPayFees', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }


    public function actionClassSectionData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $class_id = $parents[0];
                $out = (new FeeStructures())->getSectionData($class_id);
                return $out;
            }
        }

        return $out;
    }




    public function actionClassSectionDataFee()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (Yii::$app->request->isPost) {
            $class_ids = Yii::$app->request->post('class_ids', []);
            if (!empty($class_ids)) {
                $out = (new FeeStructures())->getSectionDataFee($class_ids);
            }
        }

        return $out;
    }
    public function actionUpdateFee()
    {

        $get = \Yii::$app->request->get();
        $id = $get['id'];
        $fee = $get['fee'];
        $maxDeduction = $get['maxDeduction'];
        // \Yii::$app->response->format = 'json';
        if (!empty($id)) {
            $model = FeeStructures::find()->where([
                'id' => $id
            ])->one();
            if (!empty($model)) {
                $model->fee = $fee;
                $model->maximum_detuction = $maxDeduction;
            }

            if ($model->save(false)) {
                $data['status'] = "Success";
                $data['details'] = "Data Updated";
            } else {
                $data['status'] = "Error";
                $data['details'] = "Data not Updated";
            }
        }
        return json_encode($data);
    }
}
