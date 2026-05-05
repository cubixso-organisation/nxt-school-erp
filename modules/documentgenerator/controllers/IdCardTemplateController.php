<?php

namespace app\modules\documentgenerator\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentDetails;
use app\modules\documentgenerator\models\IdCardTemplate;
use app\modules\documentgenerator\models\search\IdCardTemplateSearch;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * IdCardTemplateController implements the CRUD actions for IdCardTemplate model.
 */
class IdCardTemplateController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'generate-id-card', 'get-sections', 'get-student-data', 'generate-pdf'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'generate-id-card'],
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
     * Lists all IdCardTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IdCardTemplateSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionGenerateIdCard()
    {
        $searchModel = new IdCardTemplateSearch();
        $model =  new IdCardTemplate();
        $campusId = User::getCampusId();

        $classRoomTitles = ArrayHelper::map(
            \app\modules\admin\models\StudentClass::find()
                ->where(['campus_id' => $campusId])
                ->asArray()
                ->all(),
            'id',
            'title'
        );


        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('generate_id_card', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'campusId'  => $campusId,
            'model' => $model,
            'classRoomTitles' => $classRoomTitles
        ]);
    }

    /**
     * Displays a single IdCardTemplate model.
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

    public function actionGetSections()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            // var_dump($parents);
            // exit;
            if ($parents != null) {
                $city_id = $parents[0];


                $out = $this->getItemid((int)$city_id);

                return Json::encode(['output' => $out, 'selected' => '']);;
            }
        }


        return Json::encode($out);
    }
    public function actionGeneratePdf()
    {
        // Get the selected IDs from the POST request
        $selectedIds = Yii::$app->request->post('selected_ids');
        $post = Yii::$app->request->post();
        // var_dump($post['template_id']);
        // exit;

        $idCardTemplate = IdCardTemplate::find()->where(['id' => $post['template_id']])->one();
        if (empty($idCardTemplate) || empty($idCardTemplate->front_background_image) || empty($idCardTemplate->school_logo)) {
            Yii::$app->session->setFlash('error', 'Invalid template: Please ensure all fields in the ID Card Template are properly filled out.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $idArray = explode(',', $selectedIds);

        // Fetch the student details based on the selected IDs
        $students = StudentDetails::findAll(['id' => $idArray]);

        // Setup mPDF
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => __DIR__ . '/vendor/mpdf/mpdf/tmp',
            'format' => [127.6, 202.6], // Using mm size for precision
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);
        $mpdf->SetDisplayMode('fullpage');

        // Stylesheet for ID card layout
        $stylesheet = "
        .id-card-container {
            width: 127.6mm;
            height: 202.6mm;
            position: relative;
            background-color: #f0f0f0; /* Assuming the grey background */
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 0;
        }
        
        .id-card-content {
            position: relative;
            width: 100%;
            height: 100%;
            color: #000;
            font-family: Arial, sans-serif;
        }
        
        .school-logo {
            width: 50mm;
            position: relative;
            visibility:hidden !important;
        }
        
        .student-photo {
            width: 45mm;
            height: 50mm;
            border-radius: 100%; /* Circular image */
            margin-top: 55mm;
            margin-left: 40mm;
            object-fit: cover;
               border-style: solid;
    border-color: #90AFC4
;
    border-width: medium;

    overflow: hidden;
        }
        
        .student-details {
            font-size: 14pt;
            margin-top: 4mm;
            gap: 2px;
            text-align: center;
            width: 100%;
            line-height: 1.2;  
            padding-bottom: 15mm;
        }
            .logodiv{
    margin-left: 37mm;
            }
        ";

        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        foreach ($students as $key => $student) {
            // Path to the background image and school logo
            $backgroundImage = $idCardTemplate->front_background_image;
            $schoolLogo = $idCardTemplate->school_logo;
            $html = '
            <div class="id-card-container" style="background-image: url(' . $backgroundImage . ');">
                <div class="id-card-content">
               
                    <img src="' . (isset($student->profile_photo) ? $student->profile_photo : "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png") . '" alt="Student Photo" class="student-photo">
                    <div class="student-details">
                        <strong>' . $student->student_name . '</strong>

                        <p>Class: ' . $student->studentClass->title .   '&nbsp;&nbsp;&nbsp;Section: ' .  $student->section->section_name . '</p>
                        <p>Father Name: ' . $student->parent->name_of_the_father . '</p>
                        <p>address: ' . $student->current_address . '</p>
                        <p>Contact No: ' . $student->parent->contact_number . '</p>
                      
                    </div>
                </div>
            </div>';

            // Add the HTML content to the PDF
            $mpdf->WriteHTML($html);

            // Add a page break after every ID card (if multiple cards are to be on separate pages)
            if (($key + 1) % 1 == 0) {
                $mpdf->AddPage();
            }
        }

        // Output the PDF
        $mpdf->Output('student_id_cards.pdf', \Mpdf\Output\Destination::INLINE);
    }




    public function actionGetStudentData()
    {
        $post = Yii::$app->request->post();
        // var_dump($post);exit;
        $classId = $post["IdCardTemplate"]["title"];
        $sectionId = $post["IdCardTemplate"]["section_name"];
        $certificateid = $post["IdCardTemplate"]["certificate_name"];

        $studentDetails = StudentDetails::find()->where(['student_class_id' => (int)$classId])->andWhere(['section_id' => $sectionId])->all();

        return $this->renderPartial('_student_table', ['studentDetails' => $studentDetails, 'certificate_id' => (int)$certificateid]);
    }

    public function getItemid($id)
    {

        $out = [];


        $data = ClassSections::find()->where(['student_class_id' => (int)$id])->andWhere(['status' => ClassSections::STATUS_ACTIVE])->all();

        if (!empty($data)) {

            foreach ($data as $dat) {
                $out[] = ['id' => $dat->id, 'name' => $dat->section_name ?? ""]; // Use correct object properties
            }
        }

        return $out;
    }

    /**
     * Creates a new IdCardTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IdCardTemplate();

        if ($model->load(Yii::$app->request->post())) {

            $uploadFields = ['school_logo', 'signature', 'front_background_image'];
            $uploadsPath = Yii::getAlias('@webroot/uploads/');
            $uploadsUrl = Yii::getAlias('@web/uploads/');

            if (!is_dir($uploadsPath)) {
                mkdir($uploadsPath, 0777, true); // Create uploads folder if not exist
            }

            foreach ($uploadFields as $field) {
                $file = UploadedFile::getInstance($model, $field);
                if ($file) {
                    $filename = $field . '_' . time() . '.' . $file->extension;
                    $fullPath = $uploadsPath . $filename;
                    if ($file->saveAs($fullPath)) {
                        $model->$field = $uploadsUrl . $filename;
                    }
                }
            }

            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }




    /**
     * Updates an existing IdCardTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Store existing image URLs
        $existingSchoolLogo = $model->school_logo;
        $existingSignature = $model->signature;
        $existingFrontBackgroundImage = $model->front_background_image;

        if ($model->loadAll(Yii::$app->request->post())) {
            try {
                // Handle school_logo upload
                $uploadSchoolLogo = \yii\web\UploadedFile::getInstance($model, 'school_logo');
                if (!empty($uploadSchoolLogo)) {
                    $imageSchoolLogo = Yii::$app->notification->imageKitUpload($uploadSchoolLogo, 'school_logo');
                    $model->school_logo = $imageSchoolLogo['url'];
                } else {
                    // Retain the existing school_logo if no new file is uploaded
                    $model->school_logo = $existingSchoolLogo;
                }

                // Handle signature upload
                $uploadSignature = \yii\web\UploadedFile::getInstance($model, 'signature');
                if (!empty($uploadSignature)) {
                    $imageSignature = Yii::$app->notification->imageKitUpload($uploadSignature, 'signature');
                    $model->signature = $imageSignature['url'];
                } else {
                    // Retain the existing signature if no new file is uploaded
                    $model->signature = $existingSignature;
                }

                // Handle front_background_image upload
                $uploadFrontBackground = \yii\web\UploadedFile::getInstance($model, 'front_background_image');
                if (!empty($uploadFrontBackground)) {
                    $imageFrontBackground = Yii::$app->notification->imageKitUpload($uploadFrontBackground, 'front_background_image');
                    $model->front_background_image = $imageFrontBackground['url'];
                } else {
                    // Retain the existing front_background_image if no new file is uploaded
                    $model->front_background_image = $existingFrontBackgroundImage;
                }

                // Save model without validation
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Template updated successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to update the ID card template.');
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'An error occurred while uploading the files or updating the template: ' . $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing IdCardTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = IdCardTemplate::STATUS_DELETE;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (! empty($post['id'])) {
            $model = IdCardTemplate::find()->where([
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
     * Finds the IdCardTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IdCardTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IdCardTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
