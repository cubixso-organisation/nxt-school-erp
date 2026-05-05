<?php

namespace app\modules\documentgenerator\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\ClassRooms;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentDetails;
use app\modules\documentgenerator\models\base\GeneratedCertificateData;
use app\modules\documentgenerator\models\Studentcertificates;
use app\modules\documentgenerator\models\search\StudentcertificatesSearch;
use yii\data\ActiveDataProvider;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\HtmlPurifier;
use yii\db\Expression;
use yii\helpers\FileHelper;

/**
 * StudentcertificatesController implements the CRUD actions for Studentcertificates model.
 */
class StudentcertificatesController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'view-certificate', 'preview', 'generate-certificate', 'get-sections', 'get-student-data', 'generate-pdf', 'view-generated-certificate', 'index-certificate-list'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'view-certificate', 'generate-certificate',  'preview', 'get-sections', 'get-student-data', 'generate-pdf', 'view-generated-certificate', 'index-certificate-list',],
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
     * Lists all Studentcertificates models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentcertificatesSearch();





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
     * Displays a single Studentcertificates model.
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
     * Creates a new Studentcertificates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Studentcertificates();

        if ($model->load(Yii::$app->request->post())) {

            $uploadImageSp = \yii\web\UploadedFile::getInstance($model, 'student_image');
            if (!empty($uploadImageSp)) {
                $image = Yii::$app->notification->imageKitUpload($uploadImageSp, 'student_image');
                $model->student_image = $image['url'];
                $model->campus_id = (new User)->getCampusId();
            }
            //    var_dump($model);
            //     exit;
            $upload_imageBg = \yii\web\UploadedFile::getInstance($model, 'background_image');
            if (!empty($upload_imageBg)) {
                $image = Yii::$app->notification->imageKitUpload($upload_imageBg, 'background_image');
                $model->background_image = $image['url'];
            }


            $uploadImageLeft = \yii\web\UploadedFile::getInstance($model, 'left_sig');
            if (!empty($uploadImageLeft)) {
                $image = Yii::$app->notification->imageKitUpload($uploadImageLeft, 'left_sig');
                $model->left_sig = $image['url'];
            }

            $uploadImageRight = \yii\web\UploadedFile::getInstance($model, 'right_sig');
            if (!empty($uploadImageRight)) {
                $image = Yii::$app->notification->imageKitUpload($uploadImageRight, 'right_sig');
                $model->right_sig = $image['url'];
            }

            $uploadImageCenter = \yii\web\UploadedFile::getInstance($model, 'center_sig');
            if (!empty($uploadImageCenter)) {
                $image = Yii::$app->notification->imageKitUpload($uploadImageCenter, 'center_sig');
                $model->center_sig = $image['url'];
            }
            //    var_dump($model);
            //     exit;



            $model->campus_id = (new User)->getCampusId();

            if ($model->save(false)) {

                return $this->redirect(['index']);
            } else {
                print_r($model->getErrors());
                exit();
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing Studentcertificates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Store the existing image URLs
        $existingStudentImage = $model->student_image;
        $existingBackgroundImage = $model->background_image;
        $oldleft_sig = $model->left_sig;
        $oldright_sig = $model->right_sig;
        $oldcenter_sig = $model->center_sig;

        if ($model->loadAll(Yii::$app->request->post())) {

            $uploadImageSp = \yii\web\UploadedFile::getInstance($model, 'student_image');
            $upload_imageBg = \yii\web\UploadedFile::getInstance($model, 'background_image');

            // Check if a new student image is uploaded
            if ($uploadImageSp !== null) {
                $imageSp = Yii::$app->notification->imageKitUpload($uploadImageSp);
                $model->student_image = $imageSp['url'];
            } else {
                // Retain the existing student image if not uploaded
                $model->student_image = $existingStudentImage;
            }

            // Check if a new background image is uploaded
            if ($upload_imageBg !== null) {
                $image = Yii::$app->notification->imageKitUpload($upload_imageBg);
                $model->background_image = $image['url'];
            } else {
                // Retain the existing background image if not uploaded
                $model->background_image = $existingBackgroundImage;
            }





            $uploadImageLeft = \yii\web\UploadedFile::getInstance($model, 'left_sig');
            if (!empty($uploadImageLeft)) {
                $image = Yii::$app->notification->imageKitUpload($uploadImageLeft, 'left_sig');
                $model->left_sig = $image['url'];
            } else {
                $model->left_sig = $oldleft_sig;
            }

            $uploadImageRight = \yii\web\UploadedFile::getInstance($model, 'right_sig');
            if (!empty($uploadImageRight)) {
                $image = Yii::$app->notification->imageKitUpload($uploadImageRight, 'right_sig');
                $model->right_sig = $image['url'];
            } else {
                $model->right_sig = $oldright_sig;
            }

            $uploadImageCenter = \yii\web\UploadedFile::getInstance($model, 'center_sig');
            if (!empty($uploadImageCenter)) {
                $image = Yii::$app->notification->imageKitUpload($uploadImageCenter, 'center_sig');
                $model->center_sig = $image['url'];
            } else {
                $model->center_sig = $oldcenter_sig;
            }

            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Studentcertificates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = Studentcertificates::STATUS_DELETE;
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
            $model = Studentcertificates::find()->where([
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
     * Finds the Studentcertificates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Studentcertificates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Studentcertificates::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    public function actionViewCertificate($id, $template_type)
    {
        $model = $this->findModel($id);

        if ($template_type == 1) {
            return $this->render('view_certificate', [
                'model' => $model,
                'id' => $id,
                'template_type' => $template_type,
            ]);
        } elseif ($template_type == 2) {
            return $this->render('view_certificate_pot', [
                'model' => $model,
                'id' => $id,
                'template_type' => $template_type,
            ]);
        } else {

            throw new \yii\web\NotFoundHttpException('Invalid template_type');
        }
    }


    public function actionGenerateCertificate()
    {
        $model = new \app\modules\documentgenerator\models\Studentcertificates();

        $campusId = User::getCampusId();

        // Fetch class room titles from ClassRoom model
        $classRoomTitles = ArrayHelper::map(
            \app\modules\admin\models\StudentClass::find()
                ->where(['campus_id' => $campusId])
                ->asArray()
                ->all(),
            'id',
            'title'
        );

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                return;
            }
        }

        return $this->render('generate-certificate', [
            'model' => $model,
            'classRoomTitles' => $classRoomTitles,
        ]);
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


    public function actionPreview()
    {
        $model = new Studentcertificates();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            var_dump($model['background_image']);
            exit;

            $model->student_image = UploadedFile::getInstance($model, 'student_image');
            $model->background_image = UploadedFile::getInstance($model, 'background_image');
            // Perform any additional validation or processing as needed
            var_dump($model->background_image);
            exit;
            // Get the content of the image
            $imageData = base64_encode(file_get_contents($model->student_image->tempName));


            $imageDatabg = base64_encode(file_get_contents($model->background_image->tempName));
            // Render the preview view with $imageData and other data
            return $this->renderAjax('preview', ['model' => $model, 'imageData' => $imageData]);
        }


        return null;
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
    public function actionGetStudentData()
    {
        $post = Yii::$app->request->post();
        $classId = $post["Studentcertificates"]["title"];
        $sectionId = $post["Studentcertificates"]["section_name"];
        $certificateid = $post["Studentcertificates"]["certificate_name"];

        $studentDetails = StudentDetails::find()->where(['student_class_id' => (int)$classId])->andWhere(['section_id' => $sectionId])->all();

        return $this->renderPartial('_student_table', ['studentDetails' => $studentDetails, 'certificate_id' => (int)$certificateid]);
    }


    public function actionGeneratePdf($studentDetailId, $certificateId)
    {
        try {
            $this->layout = 'blank';

            $studentDetails = StudentDetails::find()->where(['id' => (int)$studentDetailId])->one();
            $certificate = Studentcertificates::find()->where(['id' => (int)$certificateId])->one();
            $certiDesc = $this->dynamicDescription($certificate->body_text, $studentDetails);

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $hostInfo = Yii::$app->request->hostInfo;
            $baseUrl = Yii::$app->request->baseUrl;

            $filename = FileHelper::normalizePath($certificate->certificate_name) . '_' . time() . '.pdf';
            $uploadDirectory = Yii::getAlias('@webroot/uploads/documentPdf/pdf/');
            $filePath = $uploadDirectory . $filename;
            $fileUrl = $hostInfo . $baseUrl . '/uploads/documentPdf/pdf/' . $filename;

            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'destination' => Pdf::DEST_FILE,
                'filename' => $filePath,
                'content' => $this->renderPartial('_printpage', ['studentDetails' => $studentDetails, 'certiDesc' => $certiDesc, 'certificate' => $certificate]),
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.css',
                'cssInline' => 'body { background-image: url("' . $certificate->background_image . '");  }',
                'options' => ['title' => false],
                'methods' => [
                    'SetHeader' => false,
                    'SetFooter' => false,
                ],
            ]);

            $generatedCertificateData = new GeneratedCertificateData();
            $generatedCertificateData->student_id = $studentDetails->id;
            $generatedCertificateData->student_name = $studentDetails->student_name;
            $generatedCertificateData->created_user_id = Yii::$app->user->id;
            $generatedCertificateData->updated_user_id = Yii::$app->user->id;
            $generatedCertificateData->updated_on = date('Y-m-d H:i:s');
            $generatedCertificateData->certificate_name = $certificate->certificate_name;
            $generatedCertificateData->certificate_file_path = $fileUrl;
            $generatedCertificateData->created_on = date('Y-m-d H:i:s');

            if ($generatedCertificateData->validate() && $generatedCertificateData->save()) {
                $pdf->render();

                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                return $this->redirect($fileUrl);
            } else {
                throw new \Exception('Failed to save generated certificate data.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }
    }


    private function dynamicDescription($bodyText, $studentDetails)
    {

        // Your input string containing keys and dynamic data
        $inputString = $bodyText;

        // Dynamic data (replace with your actual dynamic data)
        $dynamicData = [
            'name' => $studentDetails->student_name,
            'dob' => $studentDetails->date_of_birth,
            'email' => $studentDetails->email,
            'phone' => $studentDetails->phone_number,
            'present_address' => $studentDetails->current_address,
            'guardian' => $studentDetails->parent->name_of_the_father,
            'created_at' => date('Y-m-d'),
            'admission_no' => $studentDetails->admission_number,
            'roll_no' => $studentDetails->rool_number,
            'class' => $studentDetails->studentClass->title,
            'section' => $studentDetails->section->section_name,
            'gender' => $studentDetails->gender,
            'admission_date' => $studentDetails->admission_date,
            'category' => $studentDetails->category,
            'cast' => $studentDetails->caste,
            'father_name' => $studentDetails->parent->name_of_the_father,
            'mother_name' => $studentDetails->parent->name_of_the_mother,
        ];

        // Keys to be replaced
        $dynamicKeys = array_keys($dynamicData);

        // Loop through the dynamic keys and replace them in the input string
        foreach ($dynamicKeys as $key) {
            $keyWithBrackets = "[$key]";
            $replacement = isset($dynamicData[$key]) ? $dynamicData[$key] : $key;
            $inputString = str_replace($keyWithBrackets, $replacement, $inputString);
        }

        // Use HtmlPurifier to clean up the resulting string (optional)
        $filteredString = HtmlPurifier::process($inputString);
        $trimStart =  str_replace('[', '', $inputString);

        $trimClose =  str_replace(']', '', $trimStart);
        return $trimClose;

        // Now $filteredString contains the original data with the specified dynamic keys replaced
    }
    public function actionViewGeneratedCertificate($id)
    {
        $model = GeneratedCertificateData::findOne($id);

        return $this->render('_generated_certificate', [
            'model' => $model,
        ]);
    }
    // Assuming your controller is named MyController
    public function actionIndexCertificateList()
    {
        $query = GeneratedCertificateData::find()->joinWith(['student as stu'])->where(['stu.campus_id' => (new User())->getCampusId()]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index_certificate_list', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
