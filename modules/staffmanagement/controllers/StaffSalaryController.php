<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\models\User;
use app\modules\staffmanagement\models\base\SalaryGroupComponents;
use app\modules\staffmanagement\models\SalaryComponents;
use app\modules\staffmanagement\models\SalaryGroups;
use app\modules\staffmanagement\models\StaffSalary;
use app\modules\staffmanagement\models\search\StaffSalarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StaffSalaryController implements the CRUD actions for StaffSalary model.
 */
class StaffSalaryController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'staff-view', 'update-salary'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'staff-view'],
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
     * Lists all StaffSalary models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaffSalarySearch();





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
     * Displays a single StaffSalary model.
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

    public function actionUpdateSalary()
    {
        $post = Yii::$app->request->post();
        $dd = [];
        $annual_ctc = isset($post['annual_ctc']) ? $post['annual_ctc'] : 0;
        $basic_salary_type_value = isset($post['basic_salary_type']) ? $post['basic_salary_type'] : 0;
        $monthly_basic_salary = isset($post['monthly_basic_salary']) ? $post['monthly_basic_salary'] : 0;
        $annual_basic_salary = isset($post['annual_basic_salary']) ? $post['annual_basic_salary'] : 0;
        $staff_id = isset($post['staff_id']) ? $post['staff_id'] : 0;

        $staffSalary = StaffSalary::find()->where(['staff_id' => $staff_id])->andWhere(['campus_id' => (new User())->getCampusId()])->one();
        if (!empty($staffSalary)) {
            $staffSalary->ctc = (float)$annual_ctc;
            $staffSalary->basic_salary_type_value = (float)$basic_salary_type_value;
            $staffSalary->monthly_basic_salary = (float)$monthly_basic_salary;
            $staffSalary->annual_basic_salary = (float)$annual_basic_salary;
            if ($staffSalary->save(false)) {
                $dd['status'] = "OK";
                $dd['details'] = "Form Submitted Successfully";
            } else {
                $dd['status'] = "NOK";
                $dd['error'] = "Not Submitted";
            }
        } else {
            $dd['status'] = "NOK";
            $dd['error'] = "Something went wrong please try again later";
        }


        return json_encode($dd);
    }

    public function actionStaffView($id)
    {
        $staffSalary = StaffSalary::find()->where(['id' => (int)$id])->one();
        if (empty($staffSalary->salary_group_id)) {
            Yii::$app->session->setFlash('error', 'Staff is not assigned to any payroll group');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $salaryGroupComponents = SalaryGroupComponents::find()->joinWith('component as com')->where(['salary_group_components.group_id' => $staffSalary->salary_group_id])->andWhere(['com.component_type' => SalaryComponents::COMPONENT_TYPE_EARNING])->all();
        $salaryGroupComponentsDeduction = SalaryGroupComponents::find()->joinWith('component as com')->where(['salary_group_components.group_id' => $staffSalary->salary_group_id])->andWhere(['com.component_type' => SalaryComponents::COMPONENT_TYPE_DEDUCTION])->all();

        if (empty($salaryGroupComponents)) {
            $salaryGroupComponents = [];
        }
        return $this->render('view', [
            'model' => $this->findModel($staffSalary->id),
            'salaryGroupComponents' => $salaryGroupComponents,
            'salaryGroupComponentsDeduction' => $salaryGroupComponentsDeduction
        ]);
    }

    /**
     * Creates a new StaffSalary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StaffSalary();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StaffSalary model.
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
     * Deletes an existing StaffSalary model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = StaffSalary::STATUS_DELETE;
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
            $model = StaffSalary::find()->where([
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
     * Finds the StaffSalary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaffSalary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaffSalary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
