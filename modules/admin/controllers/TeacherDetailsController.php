<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\search\TeacherAttenddenceSearch;
use app\modules\admin\models\TeacherAttenddence;
use Yii;
use app\models\User;
use app\models\UserSearch;
use app\modules\admin\models\base\Auth;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\ClassTeacher;
use app\modules\admin\models\TeacherDetails;
use app\modules\admin\models\search\TeacherDetailsSearch;
use app\modules\admin\models\StudentClass;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TeacherDetailsController implements the CRUD actions for TeacherDetails model.
 */
class TeacherDetailsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-student-class-attendance', 'add-teacher-has-students', 'upload-excel', 'not-marked-teachers'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-student-class-attendance', 'add-teacher-has-students', 'upload-excel', 'not-marked-teachers'],
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
     * Lists all TeacherDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeacherDetailsSearch();
        $model = new TeacherDetails();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->CampusSearch(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->camoussubsearch(Yii::$app->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    public function actionNotMarkedTeachers()
    {
        $searchModel = new TeacherDetailsSearch();
        $model = new TeacherDetails();
        $currentDate = date('Y-m-d'); // Get the current date
        $campus_id = \Yii::$app->user->identity->campus_id; // Assuming you get campus_id from the user identity

        // Check the user's role
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->searchNotMarkedTeachers(Yii::$app->request->queryParams, $campus_id, $currentDate);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->searchNotMarkedTeachers(Yii::$app->request->queryParams, $campus_id, $currentDate);
        } else if (\Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->searchNotMarkedTeachers(Yii::$app->request->queryParams, $campus_id, $currentDate);
        }

        return $this->render('not_marked_teachers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }


    // public function actionUploadExcel()
    // {
    //     $modelImport = new TeacherDetails();
    //     $uploaded = false;
    //     $post = Yii::$app->request->post();

    //     if (Yii::$app->request->isAjax && $modelImport->load($post)) {
    //         try {
    //             $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport, 'fileImport');
    //             $inputFile = $modelImport->fileImport->tempName;
    //             $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
    //             $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
    //             $objPHPExcel = $objReader->load($inputFile);

    //             $sheet = $objPHPExcel->getSheet(0);
    //             $highestRow = $sheet->getHighestRow();
    //             $highestColumn = $sheet->getHighestColumn();

    //             for ($row = 2; $row <= $highestRow; $row++) {
    //                 $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);





    //                 $campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
    //                 $class_name = $rowData[0][1];
    //                 $section_name = $rowData[0][2];
    //                 $id_number = $rowData[0][3];
    //                 $name = $rowData[0][4];
    //                 $date_of_birth =  $rowData[0][5];
    //                 $gender = $rowData[0][6];
    //                 $blood_group_id = $rowData[0][7];
    //                 $father_name = $rowData[0][8];
    //                 $contact_number =  $rowData[0][9];
    //                 $email =  $rowData[0][10];
    //                 $address =  $rowData[0][11];
    //                 $contact_no =  $rowData[0][9];
    //                 $user_teacher = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::role_teacher])->one();
    //                 if (empty($user_teacher)) {
    //                     if (!empty($rowData[0][5])) {
    //                         $UNIX_DATE = ($rowData[0][5] - 25569) * 86400;
    //                         $date_of_birth =  gmdate("d-m-Y H:i:s", $UNIX_DATE);
    //                     }

    //                     $user = new User();
    //                     $user->username = $contact_number . '@' . User::role_teacher . '.com';
    //                     $user->email = $email;
    //                     $user->first_name = $name;
    //                     $user->contact_no = $contact_no;
    //                     $user->date_of_birth = $date_of_birth;
    //                     $user->user_role = User::role_teacher;
    //                     $user->gender = $gender;
    //                     $user->status = User::STATUS_ACTIVE;
    //                     $user->save(false);

    //                     $model = new TeacherDetails();
    //                     $model->campus_id = $campus_id;
    //                     $model->user_id  = $user->id;

    //                     $StudentClass = StudentClass::find()->where(['title' => $class_name])->andWhere(['campus_id' => $campus_id])->one();
    //                     $student_class_id  = !empty($StudentClass->id) ? $StudentClass->id : '';
    //                     $ClassSections = ClassSections::find()->where(['section_name' => $section_name])->andWhere(['student_class_id' => $student_class_id])->andWhere(['campus_id' => $campus_id])->one();
    //                     $class_sections_id = !empty($ClassSections->id) ? $ClassSections->id : '';
    //                     $student_class_id  = !empty($ClassSections->student_class_id) ? $ClassSections->student_class_id : '';

    //                     $check_teacher_class_section = TeacherDetails::find()->where(['campus_id' => $campus_id])->andWhere(['class_id' => $student_class_id])->andWhere(['section_id' => $class_sections_id])->one();
    //                     if (empty($check_teacher_class_section)) {
    //                         $model->class_id = $student_class_id;
    //                         $model->section_id = $class_sections_id;
    //                     }



    //                     $model->id_number = $id_number;
    //                     $model->name = $name;
    //                     $model->date_of_birth = $date_of_birth;
    //                     $model->gender = $gender;
    //                     $model->blood_group_id = $blood_group_id;
    //                     $model->father_name = $father_name;
    //                     $model->contact_number = $contact_number;
    //                     $model->email = $email;
    //                     $model->address = $address;
    //                     $model->status = TeacherDetails::STATUS_ACTIVE;
    //                     $model->save(false);
    //                 }
    //             }

    //             $message = "Upload Success";
    //             $type = 'success';
    //         } catch (Exception $e) {
    //             $message = $e->getMessage();
    //             $type = 'error';
    //         }

    //         return json_encode(array('type' => $type, 'message' => $message));
    //     }
    // }




    public function actionUploadExcel()
    {
        $modelImport = new TeacherDetails();
        $post = Yii::$app->request->post();

        if (Yii::$app->request->isAjax && $modelImport->load($post)) {
            // Start a transaction
            // $transaction = Yii::$app->db->beginTransaction();

            // try {
            $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport, 'fileImport');

            $inputFile = $modelImport->fileImport->tempName;
            $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFile);

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);

                $campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);

                $class_name = $rowData[0][1];
                $section_name = $rowData[0][2];
                $id_number = $rowData[0][3];
                $name = $rowData[0][4];
                $date_of_birth =  $rowData[0][5];
                $gender = $rowData[0][6];
                $blood_group_id = $rowData[0][7];
                $father_name = $rowData[0][8];
                $contact_number =  $rowData[0][9];
                $email =  $rowData[0][10];
                $address =  $rowData[0][11];
                $contact_no =  $rowData[0][9];
                $user_teacher = User::find()->where(['contact_no' => $contact_no])->andWhere(['user_role' => User::role_teacher])->one();

                if (empty($user_teacher)) {
                    $date_of_birth = $rowData[0][5];

                    if (is_numeric($date_of_birth)) {
                        $UNIX_DATE = ($date_of_birth - 25569) * 86400;
                        $date_of_birth = gmdate("d-m-Y", $UNIX_DATE);
                    }

                    $user = new User();
                    $user->username = $contact_number . '@' . User::role_teacher . '.com';
                    $user->email = isset($email) ? $email : $contact_number . '@' . User::role_teacher . '.com';
                    $user->first_name = $name;
                    $user->contact_no = $contact_no;
                    $user->date_of_birth = $date_of_birth;
                    $user->user_role = User::role_teacher;
                    $user->gender = $gender;
                    $user->status = User::STATUS_ACTIVE;
                    $user->save(false);
                } else {
                    // Update existing user
                    $user = $user_teacher;
                    $user->email = isset($email) ? $email : $contact_number . '@' . User::role_teacher . '.com';
                    $user->first_name = $name;
                    $user->date_of_birth = $date_of_birth;
                    $user->gender = $gender;
                    $user->status = User::STATUS_ACTIVE;
                    $user->save(false);
                }

                // Check if the TeacherDetails record exists
                $model = TeacherDetails::find()->where(['user_id' => $user->id])->one();

                if (!$model) {
                    $model = new TeacherDetails();
                    $model->campus_id = $campus_id;
                }

                // Only assign class/section if both are not empty
                if (!empty($class_name) && !empty($section_name)) {
                    $StudentClass = StudentClass::find()->where(['title' => $class_name])->andWhere(['campus_id' => $campus_id])->one();
                    $student_class_id  = !empty($StudentClass->id) ? $StudentClass->id : 0;
                    $ClassSections = ClassSections::find()->where(['section_name' => $section_name])->andWhere(['student_class_id' => $student_class_id])->andWhere(['campus_id' => $campus_id])->one();
                    $class_sections_id = !empty($ClassSections->id) ? $ClassSections->id : 0;
                    $student_class_id  = !empty($ClassSections->student_class_id) ? $ClassSections->student_class_id : 0;

                    $check_teacher_class_section = TeacherDetails::find()->where(['campus_id' => $campus_id])->andWhere(['class_id' => $student_class_id])->andWhere(['section_id' => $class_sections_id])->one();
                    if (empty($check_teacher_class_section)) {
                        $model->class_id = $student_class_id;
                        $model->section_id = $class_sections_id;
                    }
                }


                $model->user_id = $user->id;

                $model->id_number = $id_number;

                $model->name = $name;

                $model->date_of_birth = $date_of_birth;

                $model->gender = $gender;

                $model->blood_group_id = $blood_group_id;

                $model->father_name = $father_name;
                $model->contact_number = $contact_number;
                $model->email = $email;
                $model->address = $address;

                $model->status = TeacherDetails::STATUS_ACTIVE;
                if (empty($model->profile_image)) {
                    $model->profile_image = "";
                }
                $model->save(false);
            }

            // Commit transaction
            // $transaction->commit();

            $message = "Upload Success";
            $type = 'success';
            // } catch (Exception $e) {
            //     // Rollback transaction
            //     $transaction->rollBack();
            //     $message = $e->getMessage();
            //     $type = 'error';
            // }

            return json_encode(['type' => $type, 'message' => $message]);
        }
    }

    // public function actionUploadExcel()
    // {
    //     $modelImport = new TeacherDetails();
    //     $post = Yii::$app->request->post();

    //     if (Yii::$app->request->isAjax && $modelImport->load($post)) {
    //         $transaction = Yii::$app->db->beginTransaction();

    //         // try {
    //             $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport, 'fileImport');
    //             $inputFile = $modelImport->fileImport->tempName;
    //             $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
    //             $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
    //             $objPHPExcel = $objReader->load($inputFile);

    //             $sheet = $objPHPExcel->getSheet(0);
    //             $highestRow = $sheet->getHighestRow();
    //             $highestColumn = $sheet->getHighestColumn();

    //             $campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);

    //             for ($row = 2; $row <= $highestRow; $row++) {
    //                 $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);

    //                 $class_name = $rowData[0][1];
    //                 $section_name = $rowData[0][2];
    //                 $id_number = $rowData[0][3];
    //                 $name = $rowData[0][4];
    //                 $date_of_birth = !empty($rowData[0][5]) ? gmdate("d-m-Y H:i:s", ($rowData[0][5] - 25569) * 86400) : null;
    //                 $gender = $rowData[0][6];
    //                 $blood_group_id = $rowData[0][7];
    //                 $father_name = $rowData[0][8];
    //                 $contact_number = $rowData[0][9];
    //                 $email = $rowData[0][10];
    //                 $address = $rowData[0][11];

    //                 $user_teacher = User::find()->where(['contact_no' => $contact_number, 'user_role' => User::role_teacher])->one();

    //                 if (empty($user_teacher)) {
    //                     $user = new User();
    //                     $user->username = $contact_number . '@' . User::role_teacher . '.com';
    //                     $user->email = $email;
    //                     $user->first_name = $name;
    //                     $user->contact_no = $contact_number;
    //                     $user->date_of_birth = $date_of_birth;
    //                     $user->user_role = User::role_teacher;
    //                     $user->gender = $gender;
    //                     $user->status = User::STATUS_ACTIVE;
    //                     $user->save(false);
    //                 } else {
    //                     $user = $user_teacher;
    //                     $user->email = $email;
    //                     $user->first_name = $name;
    //                     $user->date_of_birth = $date_of_birth;
    //                     $user->gender = $gender;
    //                     $user->status = User::STATUS_ACTIVE;
    //                     $user->save(false);
    //                 }

    //                 $model = TeacherDetails::findOne(['user_id' => $user->id]) ?: new TeacherDetails();
    //                 $model->campus_id = $campus_id;

    //                 $studentClass = StudentClass::find()->where(['title' => $class_name, 'campus_id' => $campus_id])->one();
    //                 $student_class_id = $studentClass->id ?? null;

    //                 $classSections = ClassSections::find()->where(['section_name' => $section_name, 'student_class_id' => $student_class_id, 'campus_id' => $campus_id])->one();
    //                 $class_sections_id = $classSections->id ?? null;

    //                 if (empty(TeacherDetails::findOne(['campus_id' => $campus_id, 'class_id' => $student_class_id, 'section_id' => $class_sections_id]))) {
    //                     $model->class_id = $student_class_id;
    //                     $model->section_id = $class_sections_id;
    //                 }

    //                 $model->user_id = $user->id;
    //                 $model->id_number = $id_number;
    //                 $model->name = $name;
    //                 $model->date_of_birth = $date_of_birth;
    //                 $model->gender = $gender;
    //                 $model->blood_group_id = $blood_group_id;
    //                 $model->father_name = $father_name;
    //                 $model->contact_number = $contact_number;
    //                 $model->email = $email;
    //                 $model->address = $address;
    //                 $model->status = TeacherDetails::STATUS_ACTIVE;
    //                 $model->save(false);
    //             }

    //             $transaction->commit();
    //             return json_encode(['type' => 'success', 'message' => 'Upload Success']);
    //         // } catch (\Exception $e) {
    //         //     $transaction->rollBack();
    //         //     return json_encode(['type' => 'error', 'message' => $e->getMessage()]);
    //         // }
    //     }
    // }




    /**
     * Displays a single TeacherDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerStudentClassAttendance = new \yii\data\ArrayDataProvider([
            'allModels' => $model->studentClassAttendances,
        ]);
        $providerTeacherHasStudents = new \yii\data\ArrayDataProvider([
            'allModels' => $model->teacherHasStudents,
        ]);
        $TeacherDetailsearchModel = new TeacherAttenddenceSearch();
        $dataProviderTeacherAttendence = $TeacherDetailsearchModel->search(Yii::$app->request->queryParams);

        $teacherAttendenceSearchModel = new TeacherAttenddenceSearch();

        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $teacherAttendencedataProvider = $teacherAttendenceSearchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $teacherAttendencedataProvider = $teacherAttendenceSearchModel->campusAdminSearch(Yii::$app->request->queryParams, $id);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $teacherAttendencedataProvider = $teacherAttendenceSearchModel->institutesSearch(Yii::$app->request->queryParams, $id);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $teacherAttendencedataProvider = $teacherAttendenceSearchModel->campusSubAdminSearch(Yii::$app->request->queryParams, $id);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerStudentClassAttendance' => $providerStudentClassAttendance,
            'providerTeacherHasStudents' => $providerTeacherHasStudents,
            'teacherAttendenceSearchModel' => $teacherAttendenceSearchModel,
            'teacherAttendencedataProvider' => $teacherAttendencedataProvider

        ]);
    }

    /**
     * Creates a new TeacherDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TeacherDetails();
        $model->scenario = 'create';
        if ($model->loadAll(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $class_id = $post['TeacherDetails']['class_id'];
            if (isset($post['TeacherDetails']['section_id'])) {
                $section_id = $post['TeacherDetails']['section_id'];
            }
            $contact_number = $post['TeacherDetails']['contact_number'];
            $user_role = User::role_teacher;
            $campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);

            $user_check = User::find()->where(['contact_no' => $contact_number])->andWhere(['user_role' => $user_role])->one();
            if (empty($user_check)) {
                $teacher_details = TeacherDetails::find()->where(['class_id' => $class_id])->andWhere(['section_id' => $section_id])->andWhere(['campus_id' => $campus_id])->one();
                if (empty($teacher_details)) {
                    $profile_image = \yii\web\UploadedFile::getInstance($model, 'profile_image');
                    if (!empty($profile_image)) {
                        // $image = Yii::$app->notification->imageKitUpload($profile_image, 'profile_image/teachers');

                        // // var_dump($image);exit;


                        // if (!empty($image)) {
                        //     $model->profile_image = $image['url'];
                        // } else {
                        //     $model->profile_image = "";
                        // }
                        $profile_image = \yii\web\UploadedFile::getInstance($model, 'profile_image');
                        if (!empty($profile_image)) {
                            $uploadDir = Yii::getAlias('@webroot/uploads/profile_image/teachers/');
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0775, true);
                            }

                            $imageName = time() . '_' . preg_replace('/\s+/', '_', $profile_image->baseName) . '.' . $profile_image->extension;
                            $uploadPath = $uploadDir . $imageName;

                            if ($profile_image->saveAs($uploadPath)) {
                                $model->profile_image = Yii::$app->request->hostInfo . Yii::getAlias('@web') . '/uploads/profile_image/teachers/' . $imageName;
                            } else {
                                $model->profile_image = '';
                            }
                        }
                    }


                    $model->campus_id = $campus_id;
                    if ($model->saveAll()) {

                        if (!empty($class_id) && !empty($section_id)) {
                            $class_teacher_check = ClassTeacher::find()->where(['class_id' => $class_id])->andWhere(['section_id' => $section_id])->one();
                            if (empty($class_teacher_check)) {
                                $ClassTeacher = new ClassTeacher();
                                $ClassTeacher->class_id  = $class_id;
                                $ClassTeacher->section_id   = $section_id;
                                $ClassTeacher->teacher_details_id  = $model->id;
                                $ClassTeacher->status  =  ClassTeacher::STATUS_ACTIVE;
                                $ClassTeacher->save(false);
                            }
                        }

                        $first_name = $model->name;
                        $contact_no = $model->contact_number;
                        $email = $model->email;
                        $username = $contact_no . '@' . $user_role . '.com';
                        $new_user = new User();
                        $new_user->first_name = $first_name;
                        $new_user->user_role = $user_role;
                        $new_user->contact_no = $contact_no;
                        $new_user->email = $email;
                        $new_user->username = $username;
                        $new_user->save(false);
                        $model->user_id = $new_user->id;



                        if ($model->save(false)) {
                            return $this->redirect(['index']);
                        } else {
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    } else {
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                } else {
                    $model->addError('class_id', 'This class added by another teacher');
                    $model->addError('section_id', 'This section added by another teacher');


                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                $model->addError('contact_number', 'User already exist');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TeacherDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $profile_image_old = $model->profile_image;
        $contact_number_old = $model->contact_number; // Store old contact number

        if ($model->loadAll(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $class_id = isset($post['TeacherDetails']['class_id']) ? $post['TeacherDetails']['class_id'] : $model->class_id;
            $section_id = isset($post['TeacherDetails']['section_id']) ? $post['TeacherDetails']['section_id'] : $model->section_id;

            $teacher_details_exist = TeacherDetails::find()->where(['id' => $id])->one();

            if (!empty($teacher_details_exist)) {
                $profile_image = \yii\web\UploadedFile::getInstance($model, 'profile_image');
                if (!empty($profile_image)) {
                    $image = Yii::$app->notification->imageKitUpload($profile_image, 'profile_image/teachers');
                    $model->profile_image = $image['url'];
                } else {
                    $model->profile_image = $profile_image_old;
                }

                // Check if contact number has been updated
                if ($model->contact_number != $contact_number_old) {
                    // Find the user by user_id from teacher_details_exist
                    $user = User::findOne($teacher_details_exist->user_id);
                    if ($user) {
                        $user->contact_no = $model->contact_number;
                        $user->username = $model->contact_number . '@teacher.com';
                        $user->save(false);

                        // Update the Auth table's source_id with the new contact number
                        $auth = Auth::findOne(['user_id' => $user->id]);
                        if ($auth) {
                            $auth->source_id = $model->contact_number;
                            $auth->save(false);
                        }
                    }
                }

                $model->save(false);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $teacher_details = TeacherDetails::find()->where(['class_id' => $class_id, 'section_id' => $section_id])->one();
                if (empty($teacher_details)) {
                    $profile_image = \yii\web\UploadedFile::getInstance($model, 'profile_image');
                    if (!empty($profile_image)) {
                        $image = Yii::$app->notification->imageKitUpload($profile_image, 'profile_image/teachers');
                        $model->profile_image = $image['url'];
                    } else {
                        $model->profile_image = $profile_image_old;
                    }

                    // Update the user contact number if it has changed
                    if ($model->contact_number != $contact_number_old) {
                        $user = User::findOne($teacher_details_exist->user_id);
                        if ($user) {
                            $user->contact_no = $model->contact_number;
                            $user->username = $model->contact_number . '@teacher.com';
                            $user->save(false);

                            // Update the Auth table's source_id with the new contact number
                            $auth = Auth::findOne(['user_id' => $user->id]);
                            if ($auth) {
                                $auth->source_id = $model->contact_number;
                                $auth->save(false);
                            }
                        }
                    }

                    $model->save(false);
                    return $this->redirect(['index', 'id' => $model->id]);
                } else {
                    $model->addError('class_id', 'This class added by another teacher');
                    $model->addError('section_id', 'This section added by another teacher');
                    return $this->render('update', ['model' => $model]);
                }
            }
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }



    /**
     * Deletes an existing TeacherDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = TeacherDetails::STATUS_DELETE;
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
            $model = TeacherDetails::find()->where([
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
     * Finds the TeacherDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TeacherDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TeacherDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentClassAttendance
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentClassAttendance()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentClassAttendance');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formStudentClassAttendance', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for TeacherHasStudents
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddTeacherHasStudents()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('TeacherHasStudents');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formTeacherHasStudents', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
