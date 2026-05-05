<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\models\User;
use app\modules\staffmanagement\models\StaffDetails;
use app\modules\staffmanagement\models\search\StaffDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\TeacherDetails;
use app\modules\staffmanagement\models\base\StaffDesignations;
use app\modules\staffmanagement\models\base\StaffSalary;

/**
 * StaffDetailsController implements the CRUD actions for StaffDetails model.
 */
class StaffDetailsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'today-attendance', 'import-all-staffs'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin() || User::isCampusAdmin();
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
     * Lists all StaffDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaffDetailsSearch();





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


    public function actionTodayAttendance()
    {
        $searchModel = new StaffDetailsSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams, 'today');
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
     * Displays a single StaffDetails model.
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
     * Creates a new StaffDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StaffDetails();

        if ($model->loadAll(Yii::$app->request->post())) {
            $userName = $model->contact_no . '@' . User::ROLE_STAFF . '.com';
            $user = User::find()->where(['username' => $userName])->one();
            if (!empty($user)) {
                Yii::$app->session->setFlash('error', 'Contact Number is already used please used please use different number');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $user = new User;
            $user->username = $model->contact_no . '@' . User::ROLE_STAFF . '.com';
            $user->first_name = $model->name;
            $user->last_name = $model->name;
            $user->email = $model->email;
            $user->contact_no = $model->contact_no;
            $user->gender = $model->gender;
            $user->campus_id = User::getCampusId(\Yii::$app->user->identity->id);
            $model->campus_id = User::getCampusId(\Yii::$app->user->identity->id);
            $user->user_role = User::ROLE_STAFF;
            if ($model->save(false) && $user->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StaffDetails model.
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
     * Deletes an existing StaffDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = StaffDetails::STATUS_DELETE;
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
            $model = StaffDetails::find()->where([
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


    public function actionAddSalary()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = StaffDetails::find()->where([
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



    // import all staffs


    public function actionImportAllStaffs()
    {

        $data = [];
        try {
            $campusId = (new User())->getCampusId();
            $users = User::find()->where([
                'or',
                ['user_role' => User::ROLE_CHEF_WARDEN],
                ['user_role' => User::ROLE_CHEF_WARDEN],
                ['user_role' => User::ROLE_WARDEN],
                ['user_role' => User::ROLE_LIBRARIAN],
                ['user_role' => User::ROLE_BUS_COORDINATOR],
                ['user_role' => User::ROLE_BUS_DRIVER],
            ])->andWhere(['campus_id' => $campusId])->all();

            // import users 
            foreach ($users as $user) {
                $staffDetails = StaffDetails::find()->where(['user_id' => $user->id])->andWhere(['status' => StaffDetails::STATUS_ACTIVE])->one();
                if (empty($staffDetails)) {
                    $staffDetails = new StaffDetails();
                }

                $staffDetails->user_id = $user->id;
                $staffDetails->name = $user->first_name ?? "" . $user->last_name ?? "";
                $staffDetails->campus_id  = $campusId;


                //    checking for designation & Adding // 

                $designation = StaffDesignations::find()->where(['title' => $user->user_role])->one();
                if (empty($designation)) {
                    $designation = new StaffDesignations();
                }

                $designation->title = $user->user_role;
                $designation->campus_id = $campusId;
                $designation->status = StaffDesignations::STATUS_ACTIVE;

                $designation->save(false);

                $staffDetails->designation_id = $designation->id;
                $staffDetails->contact_no = $user->contact_no;
                $staffDetails->date_of_birth = isset($user->date_of_birth) ? $user->date_of_birth : "";
                $staffDetails->gender = isset($user->gender) ? $user->gender : "";
                $staffDetails->email = $user->email ?? "";
                $staffDetails->status = StaffDetails::STATUS_ACTIVE;
                $staffDetails->save(false);
                // Checking for salary and creating salary
                $checkForSalary = StaffSalary::find()->where(['staff_id' => $staffDetails->id])->andWhere(['campus_id' => $campusId])->one();
                if (empty($checkForSalary)) {
                    $staffSalary = new StaffSalary();
                    $staffSalary->campus_id = $campusId;
                    $staffSalary->staff_id = $staffDetails->id;
                    $staffSalary->ctc  = 0;
                    $staffSalary->basic_salary_type = 0;
                    $staffSalary->basic_salary_value = 0;
                    $staffSalary->earnings = 0;
                    $staffSalary->ctc_monthly = 0;
                    $staffSalary->ctc_yearly = 0;
                    $staffSalary->total_deduction_monthly = 0;
                    $staffSalary->total_deduction_yearly = 0;
                    $staffSalary->salary_group_id = 0;
                    $staffSalary->status = StaffSalary::STATUS_ACTIVE;
                    $staffSalary->save(false);
                }
            }


            $teachers =  TeacherDetails::find()->where(['campus_id' => $campusId])->andWhere(['status' => TeacherDetails::STATUS_ACTIVE])->all();

            foreach ($teachers as $teacher) {
                $staffDetails = StaffDetails::find()->where(['user_id' => $teacher->user_id])->andWhere(['status' => StaffDetails::STATUS_ACTIVE])->one();
                if (empty($staffDetails)) {
                    $staffDetails = new StaffDetails();
                }

                $staffDetails->user_id = $teacher->user_id;
                $staffDetails->name = $teacher->name;
                $staffDetails->campus_id  = $campusId;


                //    checking for designation & Adding // 

                $designation = StaffDesignations::find()->where(['title' => User::role_teacher])->one();
                if (empty($designation)) {
                    $designation = new StaffDesignations();
                }

                $designation->title = User::role_teacher;
                $designation->campus_id = $campusId;
                $designation->status = StaffDesignations::STATUS_ACTIVE;

                $designation->save(false);

                $staffDetails->designation_id = $designation->id;
                $staffDetails->contact_no = $teacher->contact_number;
                $staffDetails->date_of_birth = isset($user->date_of_birth) ? $user->date_of_birth : "";
                $staffDetails->gender = isset($user->gender) ? $user->gender : "";
                $staffDetails->email = $user->email ?? "";
                $staffDetails->status = StaffDetails::STATUS_ACTIVE;
                $staffDetails->save(false);


                $checkForSalary = StaffSalary::find()->where(['staff_id' => $staffDetails->id])->andWhere(['campus_id' => $campusId])->one();
                if (empty($checkForSalary)) {
                    $staffSalary = new StaffSalary();
                    $staffSalary->campus_id = $campusId;
                    $staffSalary->staff_id = $staffDetails->id;
                    $staffSalary->ctc  = 0;
                    $staffSalary->basic_salary_type = 0;
                    $staffSalary->basic_salary_value = 0;
                    $staffSalary->earnings = 0;
                    $staffSalary->ctc_monthly = 0;
                    $staffSalary->ctc_yearly = 0;
                    $staffSalary->total_deduction_monthly = 0;
                    $staffSalary->total_deduction_yearly = 0;
                    $staffSalary->salary_group_id = 0;
                    $staffSalary->status = StaffSalary::STATUS_ACTIVE;
                    $staffSalary->save(false);
                }
            }

            $data['status'] = "OK";
            $data["Success"] = "Staff Imported Succesfully";
        } catch (\Exception $e) {
            // Handle any exceptions

            $data['status'] = "NOK";
            $data["error"] = $e->getMessage();
        }

        return json_encode($data);
    }



    /**
     * Finds the StaffDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaffDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaffDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
