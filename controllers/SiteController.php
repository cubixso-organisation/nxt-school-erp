<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\forms\LoginForm;
use app\forms\ContactForm;
use app\models\User;
use app\modules\admin\models\Page;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use app\modules\admin\models\WebSetting;
use app\modules\admin\forms\UserForm;
use app\modules\admin\models\Notification;
use app\modules\admin\models\UserSearch;
use app\modules\admin\models\EmailTemplate;
use yii\web\UploadedFile;
use app\components\AuthHandler;
use app\components\FirebaseNotification;
use app\components\SendOtp;
use app\components\Toast;
use app\modules\admin\models\Auth;
use app\modules\admin\models\base\StudentClassAttendance;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\ExamsResult;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\LoginLogs;
use app\modules\admin\models\ParentDetails;
use app\modules\admin\models\UserOtp;
use app\modules\exammanagement\models\base\ExamStudentMarksheet;
use app\modules\exammanagement\models\base\MarksheetSetting;
use app\modules\exammanagement\models\base\ScheduledExamMarksDevision;
use app\modules\exammanagement\models\base\ScheduledExamMarksDevisionResults;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use \app\modules\admin\models\base\AdmissionEnquirie;

class SiteController extends Controller
{
	public $successUrl = "success";
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => [
							'error',
							'index',
							'home',
							'login',
							'logout',
							'update-student-profile-image',
							'class-section-data',
							'student-data',
							'generate-exam-wise-marksheet',
							'staff-login',
							'otp-verification',
							'verify-otp',
							'send-otp',
							'admission-form',
							'generate-form-link'


						],
						'allow' => true,
						'roles' => [
							'?',
						],
					],
					[
						'actions' => [
							'error',
							'index',
							'home',
							'login',
							'logout',
							'update-student-profile-image',
							'class-section-data',
							'student-data',
							'generate-exam-wise-marksheet',
							'staff-login',
							'otp-verification',
							'verify-otp',
							'send-otp',
							'admission-form',
							'generate-form-link'







						],
						'allow' => true,
						'roles' => [
							'@',
						],
					],

				],
				/*'denyCallback' => function ($rule, $action) {
            throw new \Exception('Sorry Page Not Found');
            },*/
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => [
						'post'
					]
				]
			]
		];
	}
	/**
	 * Displays Errors.
	 *
	 * @return string
	 */
/*	public function actionError()
	{
		$app = Yii::app();
		if ($error = $app->errorHandler->error->code) {
			if ($app->request->isAjaxRequest) {
				echo $error['message'];
			} else {
				//$this->layout = 'doRedirect';
				$this->render('error' . ($this->getViewFile('error' . $error) ? $error : ''), $error);
			}
		}
	}*/

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
			'auth' => [
				'class' => 'yii\authclient\AuthAction',
				'successCallback' => [$this, 'successCallback'],
			],

		];
	}

	public function successCallback($client)
	{
		// get user data from client
		var_dump($client);
		exit;
		$userAttributes = $client->getUserAttributes();


		$user = User::find()->where(['email' => $userAttributes['email']])->one();
		if (!empty($user)) {
			Yii::$app->user->login($user);
		} else {
			$session = Yii::$app->session;
			$session['attribute'] = $userAttributes;
			$this->successUrl = Url::to(['signin']);
		}
		die(print_r($userAttributes));
		// do some thing with user data. for example with $userAttributes['email']
	}
	public function actionTestMail()
	{
		$mail =    Yii::$app->mailer->compose()
			->setFrom('support@getcashback.co.in')
			->setTo('sri.srinadh555@gmail.com')
			->setSubject('Email sent from Yii2-Swiftmailer')
			->send();
		print_r($mail);
		exit;
	}

	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
public function actionIndex()
{
    if (Yii::$app->user->isGuest) {
        return $this->render('index');
    }

    // Admin / Institute / Campus users
    if (
        User::isAdmin() ||
        User::isInstituteAdmin() ||
        User::isCampusAdmin() ||
        User::isCampusSubAdmin() ||
        User::isLibraryManager() ||
        User::isChefWarden()
    ) {
        return $this->redirect(['/admin/dashboard']);
    }

    // Default fallback
    return $this->render('index');
}

	public function actionClassSectionData()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = [];
		if (isset($_POST['depdrop_parents'])) {
			$parents = $_POST['depdrop_parents'];

			if ($parents != null) {
				$class_id = $parents[0];
				$out = $this->getSectionData($class_id);
				return $out;
			}
		}

		return $out;
	}
	public function getSectionData($class_id)
	{
		$out = [];
		$data = ClassSections::find()
			->where(['student_class_id' => $class_id])
			->andWhere(['status' => Campus::STATUS_ACTIVE])

			->asArray()
			->all();
		foreach ($data as $dat) {
			$out[] = ['id' => $dat['id'], 'name' => $dat['section_name']];
		}
		return $output = [
			'output' => $out
		];
	}


	public function actionStudentData()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = [];
		if (isset($_POST['depdrop_parents'])) {
			$parents = $_POST['depdrop_parents'];
			if ($parents != null) {
				$class_id = $parents[0];
				$section_id = $parents[1];
				$out = $this->getStudentData($class_id, $section_id);
				return $out;
			}
		}

		return $out;
	}
	public function getStudentData($class_id, $section_id)
	{
		$out = [];
		$data = StudentDetails::find()
			->where(['student_class_id' => $class_id])
			->andWhere(['section_id' => $section_id])
			->andWhere(['status' => Campus::STATUS_ACTIVE])

			->asArray()
			->all();
		foreach ($data as $dat) {
			if (!empty($dat['parent_id'])) {
				$parentDetails = ParentDetails::find()->where(['id' => $dat['parent_id']])->one();
				if (!empty($parentDetails)) {
					$out[] = ['id' => $dat['id'], 'name' => $dat['student_name'] . ' (' . $parentDetails->name_of_the_father . ')'];
				} else {
					$out[] = ['id' => $dat['id'], 'name' => $dat['student_name']];
				}
			} else {
				$out[] = ['id' => $dat['id'], 'name' => $dat['student_name']];
			}
		}
		return $output = [
			'output' => $out
		];
	}

	public function actionUpdateStudentProfileImage($campusId = '')
	{
		$model = new StudentDetails();
		$post = Yii::$app->request->post();

		if (!empty($post)) {
			$studentDetails = StudentDetails::find()->where(['id' => $post['StudentProfile']['student_name']])->one();

			if (!$studentDetails) {
				throw new NotFoundHttpException('Student not found.');
			}

			// Preserve old image
			$oldImage = $studentDetails->profile_photo;

			// Handle profile photo upload
			$profile_photo = \yii\web\UploadedFile::getInstanceByName('StudentProfile[profile_photo]');
			if (!empty($profile_photo)) {
				// Create upload path if not exists
				$uploadPath = Yii::getAlias('@webroot/uploads/profile_image/students/');
				if (!is_dir($uploadPath)) {
					mkdir($uploadPath, 0777, true);
				}
				$fileName = uniqid() . '_' . time() . '.' . $profile_photo->extension;
				$filePath = $uploadPath . $fileName;
				if ($profile_photo->saveAs($filePath)) {
					// Generate full URL
					$host = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl;
					$studentDetails->profile_photo = $host . '/uploads/profile_image/students/' . $fileName;
				} else {
					$studentDetails->profile_photo = $oldImage; // retain existing if upload fails
				}
			} else {
				$studentDetails->profile_photo = $oldImage; // retain existing
			}

			// Handle permanent_address
			$submittedAddress = trim($post['StudentProfile']['permanent_address'] ?? '');
			if (!empty($submittedAddress)) {
				$studentDetails->permanent_address = $submittedAddress;
			}
			// else keep the old value

			// Handle phone_number
			$submittedPhone = trim($post['StudentProfile']['phone_number'] ?? '');
			if (!empty($submittedPhone)) {
				$studentDetails->phone_number = $submittedPhone;
			}
			// else keep the old value

			if ($studentDetails->save(false)) {
				return true;
			} else {
				Yii::error("Failed to save student profile: " . json_encode($studentDetails->errors));
			}
		} else {
			$campus = Campus::findOne($campusId);
			if (!empty($campus)) {
				return $this->render('updateStudentDetails', [
					'model' => $model,
					'campus' => $campus
				]);
			} else {
				throw new BadRequestHttpException('Bad Request');
			}
		}
	}


	/**
	 * Displays contact page.
	 *
	 * @return Response|string
	 */
	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact()) {
			Yii::$app->session->setFlash('contactFormSubmitted');

			return $this->refresh();
		}

		return $this->render('contact', [
			'model' => $model,
		]);
	}

	/**
	 * Displays about page.
	 *
	 * @return string
	 */





	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}
	public static function getCountryFromIp($ip)
	{
		// Handle localhost IP addresses
		if ($ip === '::1' || $ip === '127.0.0.1') {
			return 'Localhost';
		}

		// Query the IP API
		$url = "http://ip-api.com/json/$ip";
		$response = @file_get_contents($url);

		if ($response === FALSE) {
			return 'Unknown Country'; // Handle errors gracefully
		}

		$data = json_decode($response);

		// Check if the status is 'success'
		if ($data->status === 'success') {
			return $data->country;
		}

		return 'Unknown Country'; // Fallback in case of failure
	}


	public static function saveLoginLogs($user_id, $ip, $country, $campus_id)
	{
		$loginUser = new LoginLogs();
		$loginUser->campus_id = $campus_id;
		$loginUser->ip	 = $ip;
		$loginUser->country = $country;
		$loginUser->campus_id = $campus_id;
		$loginUser->user_id = $user_id;
		$loginUser->status = LoginLogs::STATUS_ACTIVE;
		$loginUser->save(false);
	}


	public function actionOtpVerification($user_id, $ip) {}

	public function actionSendOtp($id)
	{

		$userId = base64_decode($id);
		$user = User::find()->where(['id' => $userId])->one();
		$contact_no = $user->contact_no;
		$otp = rand(1111, 9999);
		$webSetting = new WebSetting();

		$templateId = $webSetting->getSettingBykey('sms_template_id');
		$apiKey = $webSetting->getSettingBykey('sms_api_key');
		$senderId = $webSetting->getSettingBykey('sender_id');
		$otp = rand(1111, 9999);
		$key = $apiKey;
		// $key = 'eac23b0c07b54748e1b3ba0fb0eed058';
		$sms = 'Dear Customer, Your OTP for Estudent is ' . $otp . '. Please do not share OTP with anyone. Regards, EStudent';
		$sms_url = urlencode($sms);
		$template_id = $templateId;
		// $template_id = '1707168312544700319';
		$sender = $senderId;
		$route = 7;
		$SendOtpData = new SendOtp();
		$send_otp = $SendOtpData->sendOtp($key, $contact_no, $sms_url, $template_id, $sender, $route);
		if (strlen($send_otp) > 4) {
			$date = date('Y-m-d H:i:s');
			$user_otp = new UserOtp();
			$user_otp->contact_number = $contact_no;
			$user_otp->otp = $otp;
			$user_otp->expire_date_and_time = date("Y-m-d H:i:s", strtotime($date . " +5 minutes"));
			$user_otp->messageid = $send_otp;
			$user_otp->status = UserOtp::STATUS_PENDING;
			$user_otp->save(false);

			return $this->render('otp-screen', ['user' => $user, 'send_otp' => $send_otp]);
		} else {
			Yii::$app->session->setFlash('error', "Unable to send the OTP please retry again");
			return $this->redirect(Yii::$app->request->referrer);
		}
	}
	public function actionVerifyOtp()
	{
		$post = Yii::$app->request->post();
		if (!empty($post)) {
			$contact_no = !empty($post['contact_no']) ? $post['contact_no'] : '';
			$otp_code = !empty($post['otp_code']) ? $post['otp_code'] : '';
			$session_code = !empty($post['session_code']) ? $post['session_code'] : '';

			if (empty($contact_no)) {
				Yii::$app->session->setFlash('error', "Contact Number Not Found");
				return $this->redirect(Yii::$app->request->referrer);
			}

			// Retrieve OTP entry
			$user_otp = UserOtp::find()->where(['contact_number' => $contact_no, 'otp' => $otp_code, 'messageid' => $session_code])->one();

			if (!empty($user_otp)) {
				$now_date_time = date('Y-m-d H:i:s');
				$expire_date_and_time = $user_otp->expire_date_and_time;

				if (strtotime($expire_date_and_time) > strtotime($now_date_time)) {
					// OTP is valid
					$user_otp->status = UserOtp::STATUS_VERIFIED;
					$user_otp->save(false);

					// Fetch the user and log them in directly
					$user = User::findOne(['contact_no' => $contact_no]);
					if ($user) {
						// Log the user in without requiring the form login method
						Yii::$app->user->login($user, 3600 * 24 * 30); // Log the user in for 30 days (adjust as needed)
						$currentIp = Yii::$app->request->userIP;
						$country = $this->getCountryFromIp($currentIp);

						$loginLogs = $this->saveLoginLogs($user->id, $currentIp, $country, $user_verification->campus_id ?? 0);
						// Redirect based on role
						if (User::isAdmin()) {
							return $this->redirect(['/admin/dashboard']);
						} elseif (User::isCampusAdmin()) {
							return $this->redirect(['/admin/dashboard']);
						} elseif (User::isInstituteAdmin()) {
							return $this->redirect(['/admin/dashboard']);
						} elseif (User::isCampusSubAdmin()) {
							return $this->redirect(['/admin/dashboard']);
						} elseif (User::isLibraryManager()) {
							return $this->redirect(['/admin/dashboard']);
						} elseif (User::isChefWarden()) {
							return $this->redirect(['/admin/dashboard']);
						}

						return $this->goBack();
					}
				} else {
					Yii::$app->session->setFlash('error', "OTP has expired.");
					return $this->redirect(Yii::$app->request->referrer);
				}
			} else {
				Yii::$app->session->setFlash('error', "Invalid OTP.");
				return $this->redirect(Yii::$app->request->referrer);
			}
		}
	}

	public function actionLogin()
	{
if (!Yii::$app->user->isGuest) {
    return $this->redirect('/admin/dashboard');
}

		$model = new LoginForm();

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return ActiveForm::validate($model);
			\Yii::$app->end();
		}

		if ($model->load(Yii::$app->request->post())) {
			$user_verification = User::find()->where(['username' => $model->username])->one();

			// First, check if the user exists and validate the password

			if ($user_verification && $user_verification->validatePassword($model->password)) {
				// If password is valid, proceed to check the IP and other conditions
				// $currentIp = Yii::$app->request->userIP;
				// $ipRecord = LoginLogs::find()->where(['user_id' => $user_verification->id, 'ip' => $currentIp])->one();

				// if (!$ipRecord || $ipRecord->status == LoginLogs::STATUS_INACTIVE) {
				// 	if (empty($user_verification->contact_no)) {
				// 		Yii::$app->session->setFlash('error', "Contact Number Not Found. Please contact the admin and provide your contact number to log in.");
				// 		return $this->redirect(Yii::$app->request->referrer);
				// 	}

				// 	// Generate OTP and redirect to OTP verification page
				// 	$userId = base64_encode($user_verification->id);
				// 	return $this->redirect(['/site/send-otp', 'id' => $userId]);
				// }

				if ($model->login()) {
					$currentIp = Yii::$app->request->userIP;
					$country = $this->getCountryFromIp($currentIp);

					// Save login logs
					$loginLogs = $this->saveLoginLogs($user_verification->id, $currentIp, $country, $user_verification->campus_id ?? 0);

					// Redirect based on user role
					if (User::isAdmin()) {
						return $this->redirect(['/admin/dashboard']);
					} else if (User::isCampusAdmin()) {
						return $this->redirect(['/admin/dashboard']);
					} else if (User::isInstituteAdmin()) {
						return $this->redirect(['/admin/dashboard']);
					} else if (User::isCampusSubAdmin()) {
						return $this->redirect(['/admin/dashboard']);
					} else if (User::isLibraryManager()) {
						return $this->redirect(['/admin/dashboard']);
					} else if (User::isChefWarden()) {
						return $this->redirect(['/admin/dashboard']);
					}

					return $this->goBack();
				}
			} else {
				Yii::$app->session->setFlash('error', 'Invalid username or password.');
			}
		}

		return $this->render('login', ['model' => $model]);
	}


	// public function actionStaffLogin()
	// {


	// 	if (!Yii::$app->user->isGuest) {
	// 		$this->redirect(url: '/admin/dashboard');
	// 	}
	// 	$model = new LoginForm();
	// 	// echo "<pre>";
	// 	// print_r($model);
	// 	// echo "</pre>";


	// 	if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {

	// 		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	// 		return ActiveForm::validate($model);
	// 		\Yii::$app->end();
	// 	}
	// 	if ($model->load(Yii::$app->request->post())) {

	// 		$matchWithPhoneNumber = User::find()->where(['contact_no' => $model['username']])->one();
	// 		$matchWithEmail = User::find()->where(['email' => $model['username']])->one();

	// 		if (!empty($matchWithPhoneNumber)) {
	// 			$user_verification = $matchWithPhoneNumber;
	// 		} else if (!empty($matchWithEmail)) {
	// 			$user_verification = $matchWithEmail;
	// 		} else {
	// 			(new Toast)->error('Invalid Credientials');
	// 			return $this->redirect(Yii::$app->request->referr);
	// 		}


	// 		// var_dump($model->login());exit;
	// 		if ($model->login()) {

	// 			if (User::isDynamicUser()) {

	// 				return $this->redirect(['/admin/dashboard']);
	// 			}



	// 			return $this->goBack();
	// 		}
	// 	}
	// 	return $this->render('staff-login', ['model' => $model]);
	// }


	public function actionAdminLogin()
	{
		//$this->layout = '//main-login';
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($model->login()) {
				if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
					return $this->redirect(['/admin/dashboard']);
				}
				return $this->goBack();
			}
		}
		return $this->render('/site/admin-login', [
			'model' => $model
		]);
	}


	public function actionGenerateExamWiseMarksheet($id = '', $exam_id = '')
	{
		$data = [];
		$structure = [];

		// Fetch Exam Wise Marksheet details
		$examWiseMarksheet = ExamStudentMarksheet::find()->where(['student_id' => $id])->andWhere(['exam_id' => $exam_id])->one();
		$studentDetail = StudentDetails::find()->where(['id' => $id])->one();
		if (!$examWiseMarksheet) {
			return "Details Not Found";
		}

		// Fetch Marksheet Template Settings
		$getMarksheetTemplateSettings = MarksheetSetting::find()->where(['campus_id' => $examWiseMarksheet->campus_id])->one();
		// var_dump($examWiseMarksheet->campus_id);
		// exit;

		if (!$getMarksheetTemplateSettings) {
			return "Unable to generate marksheet please contact to your school/college";
		}

		// Fetch Exam Results
		$examResults = ExamsResult::find()
			->where(['exam_id' => $examWiseMarksheet->exam_id])
			->andWhere(['student_id' => $examWiseMarksheet->student_id])
			->all();

		if (empty($examResults)) {
			throw new \Exception("Results Not Found");
		}

		// Fetch Student Details
		$studentDetails = StudentDetails::find()->where(['id' => $examWiseMarksheet->student_id])->one();
		if (empty($studentDetails)) {
			throw new \Exception("Student Details Not Found");
		}

		// Initialize arrays for divisions and marks
		$devisionArray = [];
		$ddMarks = [];

		// Iterate over each exam result to build marks and division arrays
		foreach ($examResults as $result) {
			$scheduledExamDevisions = ScheduledExamMarksDevision::find()
				->where(['exam_schedule_id' => $result->exam_scheduled_id])
				->all();

			foreach ($scheduledExamDevisions as $sed) {
				if (!empty($sed)) {
					$devisionArray[] = $sed->marksDevision->title;
				}
			}

			$divisionResults = ScheduledExamMarksDevisionResults::find()
				->where(['student_id' => $studentDetails->id])
				->andWhere(['exam_result_id' => $result->exams_result_id])
				->all();

			$subjectMarks = [
				'subject' => $result->subject->subject_name,
				'scores' => [],
				'total' => 0,
				'grade' => ''
			];

			foreach ($divisionResults as $ds) {
				$subjectMarks['scores'][$ds->marksDevision->title] = $ds->marks_scored;
				$subjectMarks['total'] += $ds->marks_scored; // Calculate total marks
			}

			$subjectMarks['grade'] = $result->grade; // Assume grade is pre-calculated
			$ddMarks[] = $subjectMarks;
		}

		// Fetch and process attendance data
		$currentYear = date('Y');
		$currentMonth = date('m');
		$lastDayOfMonth = date("Y-m-t", strtotime("$currentYear-$currentMonth-01"));

		$attendanceData = StudentClassAttendance::find()
			->select([
				new Expression('YEAR(date) as year'),
				new Expression('MONTH(date) as month'),
				new Expression('COUNT(DISTINCT date) as total_working_days'),
				new Expression('SUM(CASE WHEN status = ' . StudentClassAttendance::STATUS_PRESENT . ' THEN 1 ELSE 0 END) as total_present_days')
			])
			->where(['student_id' => $studentDetails->id])
			->andWhere(['academic_year_id' => $examWiseMarksheet->session_id])
			->andWhere(['<=', 'date', $lastDayOfMonth])  // Fixed here
			->groupBy(new Expression('YEAR(date), MONTH(date)'))
			->orderBy(new Expression('YEAR(date), MONTH(date)'))
			->asArray()
			->all();

		// Initialize the attendance array
		$attendanceReport = [];

		foreach ($attendanceData as $data) {
			$monthName = date("F", mktime(0, 0, 0, $data['month'], 10)); // Convert month number to name

			$attendanceReport[] = [
				'month' => $monthName,
				'working_days' => (int)$data['total_working_days'],
				'present_days' => (int)$data['total_present_days']
			];
		}

		// Prepare the final structure
		$structure = [
			'header_image_url' => $getMarksheetTemplateSettings->marksheet_header_image,
			'profile_image' => $studentDetails->profile_photo,
			'exam_name' => $examWiseMarksheet->exam->name_of_exam,
			'principal_signature_image' => $getMarksheetTemplateSettings->principal_signature,
			'student_details' => [
				'student_name' => $studentDetails->student_name,
				'father_name' => $studentDetails->parent->name_of_the_father,
				'mother_name' => $studentDetails->parent->name_of_the_mother ?? "N/A",
				'gender' => $studentDetails->user->gender ?? "N/A",
				'id_no' => $studentDetails->id ?? "N/A",
				'class' => $studentDetails->studentClass->title ?? "N/A",
				'session' => $studentDetails->session->title ?? "N/A",
				'date' => date('d-M-Y')
			],
			'total_marks' => $examWiseMarksheet->total_obtained_marks,
			'percentage' => $examWiseMarksheet->total_percentage,
			'headers' => array_unique($devisionArray),
			'marks' => $ddMarks,
			'graphs' => [
				'marks_chart' => [
					'labels' => array_column($ddMarks, 'subject'),
					'data' => array_column($ddMarks, 'total'),
					'backgroundColor' => "rgba(79, 15, 20, .6)",
					'borderColor' => "rgba(79, 15, 20, 1)"
				],
				'attendance_chart' => [
					'labels' => ["Present Days", "Absent Days"],
					'data' => [
						array_sum(array_column($attendanceReport, 'present_days')),
						array_sum(array_column($attendanceReport, 'working_days')) - array_sum(array_column($attendanceReport, 'present_days'))
					],
					'backgroundColor' => ["rgba(79, 15, 20, .6)", "rgba(150, 150, 150, .6)"],
					'borderColor' => ["rgba(79, 15, 20, 1)", "rgba(150, 150, 150, 1)"]
				]
			],
			'attendance' => $attendanceReport
		];

		// Output the final JSON structure
		$finalStructure = json_encode($structure, JSON_PRETTY_PRINT);

		$generatePdf = (new FirebaseNotification())->generateMarksheetPdf($finalStructure);
		// var_dump($generatePdf);exit;
		return $generatePdf;
	}
	public function actionAdmissionForm($campus_id = null)
	{
		$model = new AdmissionEnquirie();
		$successMessage = '';

		if ($campus_id) {
			$model->campus_id = $campus_id;
			$campus = Campus::findOne($campus_id);
		} else {
			$campus = null;
		}

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$successMessage = 'Enquiry submitted successfully.';
		}

		return $this->render('admission-form', [
			'model' => $model,
			'campus' => $campus,
			'successMessage' => $successMessage,
		]);
	}
}
