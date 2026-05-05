<?php

namespace app\modules\staffmanagement\controllers;

use app\modules\staffmanagement\models\SalaryGroupComponents;
use Yii;
use app\models\User;
use app\modules\staffmanagement\models\base\SalaryComponents;
use app\modules\staffmanagement\models\base\SalaryGroupToStaff;
use app\modules\staffmanagement\models\SalaryGroups;
use app\modules\staffmanagement\models\search\SalaryGroupsSearch;
use app\modules\staffmanagement\models\StaffDetails;
use app\modules\staffmanagement\models\StaffSalary;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * SalaryGroupsController implements the CRUD actions for SalaryGroups model.
 */
class SalaryGroupsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-salary-group-components', 'get-staff-data', 'group-to-staff', 'selected-values'],
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
     * Lists all SalaryGroups models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SalaryGroupsSearch();





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
     * Displays a single SalaryGroups model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerSalaryGroupComponents = new \yii\data\ArrayDataProvider([
            'allModels' => $model->salaryGroupComponents,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerSalaryGroupComponents' => $providerSalaryGroupComponents,
        ]);
    }

    /**
     * Creates a new SalaryGroups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SalaryGroups();

        if ($model->loadAll(Yii::$app->request->post())) {

            if ($model->save()) {
                // var_dump($model["salary_components"]);exit;

                if (is_array($model["salary_components"])) {

                    foreach ($model["salary_components"] as $component_id) {
                        $salaryGroupComponents =  new SalaryGroupComponents();
                        $salaryGroupComponents->campus_id = $model->campus_id;
                        $salaryGroupComponents->group_id = $model->id;
                        $salaryGroupComponents->component_id = $component_id;
                        $salaryGroupComponents->status = SalaryGroupComponents::STATUS_ACTIVE;
                        $salaryGroupComponents->save(false);
                    }

                    Yii::$app->session->setFlash('success', 'Salary Group Created Successful');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Please select salary components');
                    return $this->redirect(Yii::$app->request->referrer);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Unable to save');
                return $this->redirect(Yii::$app->request->referrer);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SalaryGroups model.
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


    function actionGetStaffData()
    {
        $dd = [];
        $data = [];
        $staffDetails = StaffDetails::find()->where(['campus_id' => (new User())->getCampusId()])->all();
        // var_dump($staffDetails);exit;

        foreach ($staffDetails as $staffDetail) {
            $dd['id'] = $staffDetail->id ?? "";
            $dd['text'] = $staffDetail->name . ' (' . $staffDetail->designation->title . ')';
            $data[] = $dd;
        }
        return json_encode($data);
    }


    function actionGroupToStaff()
    {
        $data = [];
        $post = Yii::$app->request->post();
        $postStaffs = isset($post["selectedValue"]) ? $post["selectedValue"] : [];
        $postGroupId = isset($post["salaryGroupId"]) ? $post["salaryGroupId"] : [];

        foreach ($postStaffs as $staff) {

            $salaryGroupToStaff =  SalaryGroupToStaff::find()->where(['staff_id' => $staff])->andWhere(['campus_id' => (new User())->getCampusId()])->one();

            if (empty($salaryGroupToStaff)) {
                $salaryGroupToStaff =  new SalaryGroupToStaff();
            }
            $salaryGroupToStaff->staff_id = $staff;
            $staffDetails = StaffDetails::find()->where(['id' => $staff])->one();
            $salaryGroupToStaff->staff_user_id = $staffDetails->user_id;
            $salaryGroupToStaff->salary_group_id  = $postGroupId;
            $salaryGroupToStaff->campus_id   = (new User())->getCampusId();
            $salaryGroupToStaff->status = SalaryGroupToStaff::STATUS_ACTIVE;
            $salaryGroupToStaff->save(false);

            // Update Group On Staff Salary // 
            $staffSalary = StaffSalary::find()->where(['staff_id' => $staff])->one();
            if (!empty($staffSalary)) {

                $staffSalary->salary_group_id = (int)$postGroupId;
                $staffSalary->save(false);
            }
        }
        $data['status'] = "OK";
        $data['detail'] = "Update Successful";
        $dd[] = $data;
        return true;
    }


    function actionSelectedValues($id)
    {

        $model = $this->findModel($id);
        $staffs = [];
        $data = [];
        $SalaryGroupToStaff = SalaryGroupToStaff::find()->where(['salary_group_id' => $id])->andWhere(['campus_id' => (new User())->getCampusId()])->all();
        foreach ($SalaryGroupToStaff as $staff) {
            $staffs['id'] = $staff->staff->id;
            $staffs['name'] = $staff->staff->name ?? "";
            $data[] = $staffs;
        }

        return json_encode($data);
    }


    /**
     * Deletes an existing SalaryGroups model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = SalaryGroups::STATUS_DELETE;
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
            $model = SalaryGroups::find()->where([
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
     * Finds the SalaryGroups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SalaryGroups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SalaryGroups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Action to load a tabular form grid
     * for SalaryGroupComponents
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddSalaryGroupComponents()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('SalaryGroupComponents');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formSalaryGroupComponents', ['row' => $row]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
