<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\models\User;
use app\modules\staffmanagement\models\base\SalaryGroupComponents;
use app\modules\staffmanagement\models\MonthlyPayrolls;
use app\modules\staffmanagement\models\SalaryComponents;
use app\modules\staffmanagement\models\SalaryGroups;
use app\modules\staffmanagement\models\search\MonthlyPayrollsSearch;
use app\modules\staffmanagement\models\StaffSalary;
use DateTime;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * MonthlyPayrollsController implements the CRUD actions for MonthlyPayrolls model.
 */
class MonthlyPayrollsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'generate-payroll', 'print'],
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
     * Lists all MonthlyPayrolls models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MonthlyPayrollsSearch();





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
     * Displays a single MonthlyPayrolls model.
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
     * Creates a new MonthlyPayrolls model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MonthlyPayrolls();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }



    public function actionGeneratePayroll()
    {
        $salaryArr = [];
        $data = [];
        $totalDeduction = 0;
        $post = Yii::$app->request->post();
        $year = isset($post['year']) ? $post['year'] : "";
        $month = isset($post['month']) ? $post['month'] : "";


        $staffSalary = StaffSalary::find()->where(['campus_id' => (new User())->getCampusId()])->all();
        foreach ($staffSalary as $salary) {
            $monthlyPayroll = MonthlyPayrolls::find()
                ->where(['month' => $month])
                ->andWhere(['YEAR(date)' => $year])
                ->andWhere(['staff_id' => $salary->staff_id])
                ->one();

            if (empty($monthlyPayroll)) {

                $monthlyPayroll = new MonthlyPayrolls();
            }
            if (!empty($salary->ctc)) {

                $monthlyPayroll->campus_id = (new User())->getCampusId();
                $monthlyPayroll->staff_id = $salary->staff_id;
                $monthlyPayroll->user_id = $salary->staff->user_id;
                $monthlyPayroll->yearly_ctc = $salary->ctc;
                $monthlyPayroll->monthly_ctc = ($salary->ctc / 12);
                $monthlyPayroll->salary_group_id = $salary->salary_group_id;
                $monthlyPayroll->month = $month;
                $monthlyPayroll->basic_salary_type_value = $salary->basic_salary_type_value;
                $monthlyPayroll->basic_salary_monthly = $salary->monthly_basic_salary;
                $monthlyPayroll->basic_salary_yearly = $salary->annual_basic_salary;
                $monthlyPayroll->date = date('Y-m-d H:i:s');

                if (!empty($salary->salary_group_id)) {
                    $salaryGroup = SalaryGroups::find()->where(['id' => $salary->salary_group_id])->one();
                    if (!empty($salaryGroup)) {
                        $salaryGroupComponents = SalaryGroupComponents::find()->where(['group_id' => $salaryGroup->id])->all();
                        if (!empty($salaryGroupComponents)) {
                            $componentsArr = []; // Initialize an array to store components

                            foreach ($salaryGroupComponents as $components) {
                                $componentData = []; // Initialize an array to store component data

                                if ($components->component->component_type == SalaryComponents::COMPONENT_TYPE_EARNING) {
                                    // Earnings logic
                                    if ($components->component->value_type == SalaryComponents::VALUE_TYPE_BASIC_PERCENTAGE) {
                                        $calculationForMonthly = ($monthlyPayroll->basic_salary_monthly * $components->component->component_value_monthly) / 100;
                                    } elseif ($components->component->value_type == SalaryComponents::VALUE_TYPE_CTC_PERCENTAGE) {
                                        $calculationForMonthly = ($monthlyPayroll->monthly_ctc * $components->component->component_value_monthly) / 100;
                                    }

                                    // Assign earnings data to $componentData
                                    $componentData['component_name'] = $components->component->name;
                                    $componentData['component_type'] = $components->component->component_type;
                                    $componentData['value_type'] = $components->component->value_type;
                                    $componentData['component_value_monthly'] = $components->component->component_value_monthly;
                                    $componentData['calculated_value_monthly'] = $calculationForMonthly;
                                    $componentData['calculated_value_yearly'] = $calculationForMonthly * 12;
                                } elseif ($components->component->component_type == SalaryComponents::COMPONENT_TYPE_DEDUCTION) {
                                    // Deduction logic
                                    if ($components->component->value_type == SalaryComponents::VALUE_TYPE_BASIC_PERCENTAGE) {
                                        $calculationForMonthly = ($monthlyPayroll->basic_salary_monthly * $components->component->component_value_monthly) / 100;
                                    } elseif ($components->component->value_type == SalaryComponents::VALUE_TYPE_CTC_PERCENTAGE) {
                                        $calculationForMonthly = ($monthlyPayroll->monthly_ctc * $components->component->component_value_monthly) / 100;
                                    }

                                    $totalDeduction += $calculationForMonthly;

                                    // Assign deduction data to $componentData
                                    $componentData['component_name'] = $components->component->name;
                                    $componentData['component_type'] = $components->component->component_type;
                                    $componentData['value_type'] = $components->component->value_type;
                                    $componentData['component_value_monthly'] = $components->component->component_value_monthly;
                                    $componentData['calculated_value_monthly'] = $calculationForMonthly;
                                    $componentData['calculated_value_yearly'] = $calculationForMonthly * 12;
                                }

                                // Add $componentData to $componentsArr
                                $componentsArr[] = $componentData;
                            }

                            $totalMonthlyEarning = $monthlyPayroll->monthly_ctc - $totalDeduction;
                            $totalData = [
                                'total_deduction' => $totalDeduction,
                                'total_monthly_earning' => $totalMonthlyEarning
                            ];
                            // Add $componentsArr to $data['components']
                            $data['components'] = $componentsArr;
                            $mergedData = array_merge($data, $totalData);
                            // Save $data as JSON
                            $monthlyPayroll->total_monthly_pay = $totalMonthlyEarning;
                            $monthlyPayroll->salary_components = json_encode($mergedData);
                        }
                    }
                }

                $monthlyPayroll->save(false);
            }
        }
        return true;
    }


    /**
     * Updates an existing MonthlyPayrolls model.
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
     * Deletes an existing MonthlyPayrolls model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = MonthlyPayrolls::STATUS_DELETE;
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
            $model = MonthlyPayrolls::find()->where([
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

    public function actionPrint($id)
    {
        $model = $this->findModel($id);


        $content = $this->renderPartial('_pay_slip', [
            'model' => $model,

        ]);



        $hostInfo = Yii::$app->request->hostInfo;
        $baseUrl = Yii::$app->request->baseUrl;
        $folderPath = Yii::getAlias('@webroot/uploads/staffmanagement/payslip/') . $model->campus->name_of_the_educational_Institution . '/' . $model->date . '/';
        if (!file_exists($folderPath)) {
            if (!mkdir($folderPath, 0777, true)) {
                throw new \Exception('Failed to create directory for saving PDF.');
            }
        }

        $pdfFilePath = $folderPath . 'pay_slip_' . $model->staff->name . rand(0000, 9999) . '.pdf';


        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
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
        $pdf->render();

        // Determine URL for the saved file
        $pdfFileName = basename($pdfFilePath);
        $pdfUrl = $hostInfo . $baseUrl . Url::to('/uploads/staffmanagement/payslip/') . $model->campus->name_of_the_educational_Institution  . '/' . $model->date . '/' . $pdfFileName;
        $model->payslip_url = $pdfUrl;
        $model->save(false);
        // Update marksheet_url column with the full URL

        // Redirect user to the generated PDF
        return $this->redirect($pdfUrl);
    }
    /**
     * Finds the MonthlyPayrolls model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MonthlyPayrolls the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonthlyPayrolls::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
