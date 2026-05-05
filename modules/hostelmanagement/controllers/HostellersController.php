<?php

namespace app\modules\hostelmanagement\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\Campus;
use app\modules\hostelmanagement\models\base\Hostellers as BaseHostellers;
use app\modules\hostelmanagement\models\base\Hostels as BaseHostels;
use app\modules\hostelmanagement\models\base\Rooms;
use app\modules\hostelmanagement\models\Hostellers;
use app\modules\hostelmanagement\models\search\HostellersSearch;
use app\modules\hostelmanagement\models\WardenToHostel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * HostellersController implements the CRUD actions for Hostellers model.
 */
class HostellersController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'get-data', 'get-student-data', 'status-change', 'import-students'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isCampusAdmin() || User::isChefWarden();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isCampusAdmin() || User::isChefWarden();
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
     * Lists all Hostellers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HostellersSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->SubAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
            $dataProvider = $searchModel->ChiefWardenSearch(Yii::$app->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hostellers model.
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
     * Creates a new Hostellers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hostellers();
        $model->campus_id = User::getCampusId();
    
        if ($model->loadAll(Yii::$app->request->post())) {
            $student_ids = $model->student_ids;
            $room_id = $model->room_id;
            $rooms_count = Rooms::find()->where(['id' => $room_id])->one();
    
            if ($rooms_count->available_bed < count($student_ids)) {
                Yii::$app->session->setFlash('error', "Not enough beds available in this room for the selected students.");
                return $this->redirect(Yii::$app->request->referrer);
            }
    
            $model->floor_id = $rooms_count->floor_id ?? null;
            $wardenToHostel = WardenToHostel::find()->where(['floor_id' => $rooms_count->floor_id])->one();
            if (empty($wardenToHostel->warden_id)) {
                Yii::$app->session->setFlash('error', "No warden has been assigned to the floor of that room; please assign one.");
                return $this->redirect(Yii::$app->request->referrer);
            }
    
            foreach ($student_ids as $student_id) {
                $hosteller = new Hostellers();
                $hosteller->attributes = $model->attributes;
                $hosteller->student_id = $student_id;
                $hosteller->warden_id = $wardenToHostel->warden_id;
    
                $student_details = StudentDetails::find()->where(['id' => $student_id])->one();
                $hosteller->photo = isset($student_details->profile_photo) && $student_details->profile_photo;
    
                $hosteller->save(false);
            }
    
            $rooms_count->available_bed -= count($student_ids);
            $rooms_count->save(false);
    
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    

    /**
     * Updates an existing Hostellers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {
            $old_room_id = $model->getOldAttribute('room_id'); // Get the previous room ID

            $new_room_id = $model->room_id;

            // Check if room is being changed
            if ($old_room_id != $new_room_id) {
                // Increase available bed count of the previous room
                $old_room = Rooms::findOne($old_room_id);
                if ($old_room) {
                    $old_room->available_bed += 1;
                    $old_room->save(false);
                }

                // Proceed with updating the hosteller's room
                $rooms_count = Rooms::find()->where(['id' => $new_room_id])->one();
                if ($rooms_count) {
                    // Check if there's an available bed
                    if ($rooms_count->available_bed == 0) {
                        Yii::$app->session->setFlash('error', "No Bed Available in this room please choose another bed");
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                    // Decrease available bed count of the new room
                    $rooms_count->available_bed -= 1;
                    $rooms_count->save(false);
                } else {
                    Yii::$app->session->setFlash('error', "Invalid room selected.");
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }

            // Proceed with updating the hosteller's room allocation and other details
            // Update floor ID, warden ID, photo, etc.

            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }








    /**
     * Deletes an existing Hostellers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = Hostellers::STATUS_DELETE;
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
            $model = Hostellers::find()->where([
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


    public function actionGetData()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $hostel_id = $parents[0];
                // var_dump('' . $hostel_id . '');
                // exit;
                $out = (new BaseHostels)->getRooms($hostel_id);
                // var_dump($out);exit;
                // return $out; 
            }
        }
        return  Json::encode($out);
    }
    public function actionGetStudentData($student_id)
    {
        // Assuming you have a Student model with an 'aadhar_number' attribute
        $student = StudentDetails::findOne(['id' => $student_id]);

        if ($student) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $student;
        } else {
            // Handle the case when the student is not found
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['error' => 'Student not found'];
        }
    }

    /**
     * Finds the Hostellers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hostellers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hostellers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    public function actionStatusChange()
    {
        $post = \Yii::$app->request->post();

        if (!empty($post['id'])) {
            $transaction = Hostellers::findOne($post['id']);

            if (!empty($transaction)) {
                // Update the status
                $transaction->status = $post['val'];

                // Save the transaction
                if ($transaction->update(false)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }



    public function actionImportStudents()
    {
        $data = [];

        $data = Yii::$app->request->post();
        $campusId = (int)$data["campus_d"];

        $campus = Campus::find()->where(['id' => $campusId])->one();

        $campusName = isset($campus->name_of_the_educational_Institution) ? $campus->name_of_the_educational_Institution : "";

        $students = StudentDetails::find()
            ->where(['campus_id' => $campusId])->andWhere(['hostal_is_required' => StudentDetails::HOSTEL_REQUIRED_YES])
            ->all();

        // var_dump($students);exit;
        $importedCount = 0;

        foreach ($students as $student) {
            // Check if library member with the same user_id already exists
            $alredyAssignedStudent = Hostellers::find()
                ->where(['student_id' => $student->user_id])
                ->andWhere(['campus_id' => $campus->id])
                ->andWhere(['status' => Hostellers::STATUS_ACTIVE])
                ->one();


            if (!$alredyAssignedStudent) {
                // Generate a unique member ID

                $hostelers = new Hostellers();
                $hostelers->campus_id = $campusId;
                $hostelers->student_id = $student->user_id;
                $hostelers->hostel_id = Null;
                $hostelers->warden_id = Null;
                $hostelers->joining_date = date('Y-m-d');
                $hostelers->floor_id = Null;
                $hostelers->room_id = Null;
                $hostelers->status = Hostellers::STATUS_ACTIVE;

                if ($hostelers->save(false)) {
                    $importedCount++;
                }
            }
        }

        if ($importedCount > 0) {
            $data['status'] = "OK";
            $data['detail'] = "$importedCount student(s) imported successfully.";
        } else {
            $data['status'] = "OK";
            $data['detail'] = "All students are already imported.";
        }

        return json_encode($data);
    }
}
