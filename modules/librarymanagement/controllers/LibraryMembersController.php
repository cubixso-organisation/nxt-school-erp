<?php

namespace app\modules\librarymanagement\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\base\TeacherDetails;
use app\modules\librarymanagement\models\LibraryMembers;
use app\modules\librarymanagement\models\search\LibraryMembersSearch;
use PharIo\Manifest\Library;
use app\models\UserSearch;
use app\modules\admin\models\Auth;
use app\modules\admin\models\base\Campus;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * LibraryMembersController implements the CRUD actions for LibraryMembers model.
 */
class LibraryMembersController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'get-user', 'create-librarian', 'index-librarian', 'status-change', 'import-student', 'import-teacher'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isLibraryManager();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'create-librarian', 'import-student', 'import-teacher'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin() || User::isLibraryManager();
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
     * Lists all LibraryMembers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LibraryMembersSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_LIBRARIAN) {
            $dataProvider = $searchModel->librarainSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LibraryMembers model.
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
     * Creates a new LibraryMembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LibraryMembers();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LibraryMembers model.
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
     * Deletes an existing LibraryMembers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = LibraryMembers::STATUS_DELETE;
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
            $model = LibraryMembers::find()->where([
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
     * Finds the LibraryMembers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LibraryMembers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LibraryMembers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }



    public function actionGetUser()
    {

        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $type = $parents[0];
                // var_dump($type);exit;
                $out = (new LibraryMembers())->getUser($type);
                // var_dump($out);exit;
                // return $out;
            }
        }
        return  Json::encode($out);
    }



    // public function actionGetData($type_mem)
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;

    //     $data = [];

    //     // Check the value of $type_mem to determine whether to fetch student or teacher data
    //     if ($type_mem == 'student') {
    //         $details = StudentDetails::find()->where(['campus_id' => User::getCampusId()])->all();
    //     } elseif ($type_mem == 'teacher') {
    //         $details = TeacherDetails::find()->where(['campus_id' => User::getCampusId()])->all();
    //     } else {
    //         // Handle the case when $type_mem is neither 'student' nor 'teacher'
    //         return ['error' => 'Invalid type_mem value'];
    //     }

    //     // Process the fetched details
    //     foreach ($details as $detail) {
    //         $data[] = [
    //             'name' => $details->student_name
    //         ];
    //     }

    //     return $data;
    // }

    public function actionCreateLibrarian()
    {

        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $existingUser = User::findOne(['username' => $model->contact_no . '@' . $model->user_role . '.com']);
            if ($existingUser) {
                Yii::$app->session->setFlash('error', 'This Contact already exists.');
                return $this->render(
                    'school_librarian',
                    [
                        'model' => $model,
                    ]
                );
            }
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->contact_no);
            $model->username = $model->contact_no . '@' . $model->user_role . '.com';
            $model->campus_id = User::getCampusId();
            if ($model->save(false)) {
                $auth = new Auth();
                $auth->source = 'Librarian';
                $auth->user_id = $model->id;
                $auth->source_id = $model->contact_no;
                $auth->save(false);
            }
            return $this->redirect(['index-librarian', 'id' => $model->id]);
        } else {
            return $this->render('school_librarian', [
                'model' => $model,
            ]);
        }
    }
    public function actionIndexLibrarian()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->librarian(Yii::$app->request->queryParams, User::ROLE_LIBRARIAN);

        return $this->render('index-librarian', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionStatusChange()
    {
        $post = \Yii::$app->request->post();

        if (!empty($post['id'])) {
            $transaction = User::find()->where(['id' => $post['id']])->one();

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




    public function actionImportStudent()
    {
        $data = [];
        $campusId = User::getCampusId();

        $campus = Campus::find()->where(['id' => $campusId])->one();

        $campusName = isset($campus->name_of_the_educational_Institution) ? $campus->name_of_the_educational_Institution : "";

        $students = StudentDetails::find()
            ->where(['campus_id' => $campusId])
            ->all();

        $importedCount = 0;

        foreach ($students as $student) {
            // Check if library member with the same user_id already exists
            $existingLibraryMember = LibraryMembers::find()
                ->where(['user_id' => $student->user_id])
                ->one();

            if (!$existingLibraryMember) {
                // Generate a unique member ID
                $memberId = $this->generateUniqueMemberId($campusName);

                $libraryMembers = new LibraryMembers();
                $libraryMembers->member_id = $memberId;
                $libraryMembers->user_id = $student->user_id;
                $libraryMembers->library_card_no = $memberId;
                $libraryMembers->admission_no = $student->admission_number;
                $libraryMembers->name = $student->student_name;
                $libraryMembers->member_type = 'student';
                $libraryMembers->phone = $student->phone_number;
                $libraryMembers->campus_id = $student->campus_id;
                $libraryMembers->status = LibraryMembers::STATUS_ACTIVE;

                if ($libraryMembers->save(false)) {
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



    public function actionImportTeacher()
    {
        $data = [];
        $campusId = User::getCampusId();

        $campus = Campus::find()->where(['id' => $campusId])->one();

        $campusName = isset($campus->name_of_the_educational_Institution) ? $campus->name_of_the_educational_Institution : "";

        $teachers = TeacherDetails::find()
            ->where(['campus_id' => $campusId])
            ->all();

        $importedCount = 0;

        foreach ($teachers as $teacher) {
            // Check if library member with the same user_id already exists
            $existingLibraryMember = LibraryMembers::find()
                ->where(['user_id' => $teacher->user_id])
                ->one();

            if (!$existingLibraryMember) {
                $memberId = $this->generateUniqueMemberId($campusName);
                $libraryMembers = new LibraryMembers();
                $libraryMembers->member_id = $memberId;
                $libraryMembers->user_id = $teacher->user_id;
                $libraryMembers->library_card_no = $memberId;
                $libraryMembers->admission_no = "";
                $libraryMembers->name = $teacher->name;
                $libraryMembers->member_type = 'teacher';
                $libraryMembers->phone = $teacher->contact_number;
                $libraryMembers->campus_id = $teacher->campus_id;
                $libraryMembers->status = LibraryMembers::STATUS_ACTIVE;

                if ($libraryMembers->save(false)) {
                    $importedCount++;
                }
            }
        }

        if ($importedCount > 0) {
            $data['status'] = "OK";
            $data['detail'] = "$importedCount teacher(s) imported successfully.";
        } else {
            $data['status'] = "OK";
            $data['detail'] = "All teachers are already imported.";
        }

        return json_encode($data);
    }

    private function generateUniqueMemberId($campusName)
    {
        // Generate a random 6-digit number
        $randomNumber = sprintf('%06d', mt_rand(0, 999999));

        // Combine the first 4 letters of the campus name with the random number
        $memberId = strtoupper(substr($campusName, 0, 4)) . $randomNumber;

        // Check if the generated member ID is unique
        while (LibraryMembers::find()->where(['member_id' => $memberId])->exists()) {
            // If not unique, regenerate the random number and try again
            $randomNumber = sprintf('%06d', mt_rand(0, 999999));
            $memberId = strtoupper(substr($campusName, 0, 4)) . $randomNumber;
        }

        return $memberId;
    }
}
