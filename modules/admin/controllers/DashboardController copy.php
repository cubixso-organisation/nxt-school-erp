<?php

namespace app\modules\admin\controllers;

use app\components\Toast;
use yii;
use app\models\User;
use app\modules\admin\models\base\BusDetails;
use app\modules\admin\models\base\Campus as BaseCampus;
use app\modules\admin\models\base\FeeStructures;
use app\modules\admin\models\base\Institutes as BaseInstitutes;
use app\modules\admin\models\base\LeaveRequests;
use app\modules\admin\models\base\NoticeBoards;
use app\modules\admin\models\base\StudentClassAttendance;
use app\modules\admin\models\base\TeacherAttenddence;
use app\modules\admin\models\base\TeacherDetails;
use app\modules\admin\models\base\TemporaryAssignTeacher;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\ParentDetails;
use app\modules\admin\models\ParentHasCampus;
use app\modules\admin\models\PayFees;
use app\modules\admin\models\PaymentDetails;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentDetails;
use app\modules\admin\models\StudentDetailsAgentLead;
use app\modules\hostelmanagement\models\base\Hostellers;
use app\modules\hostelmanagement\models\base\Hostels;
use app\modules\hostelmanagement\models\base\Rooms;
use app\modules\leavemanagement\models\base\DashboardNotification;
use app\modules\leavemanagement\models\base\StaffLeaveApplied;
use app\modules\leavemanagement\models\base\StaffLeaveTypes;
use app\modules\librarymanagement\models\base\LibraryBooks;
use app\modules\librarymanagement\models\base\LibraryMembers;
use app\modules\librarymanagement\models\base\LibraryRacks;
use yii\filters\VerbFilter;


class DashboardController extends Controller
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
                        'actions' => ['index', 'dashboard-notification', 'clear-notification', 'fetch-chart-data', 'fetch-donut-data', 'get-data-for-chart', 'get-attendance-data-for-chart', 'change-themes-color'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin()  || User::isCampusSubAdmin() || User::isLibraryManager() || User::isChefWarden() || User::isDynamicUser();
                        }



                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'dashboard-notification', 'clear-notification', 'fetch-chart-data', 'get-data-for-chart', 'get-attendance-data-for-chart'],
                        'matchCallback' => function () {
                            return User::isManager();
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
     * Displays dashboard with some statistics.
     *
     * @return string
     */
    public function actionIndex()
    {
        $records = Yii::$app->db->createCommand('SELECT * FROM user')->queryAll();
        $my_campus = '';
        $student_details_institutes = '';
        $total_bus_institutes = '';
        $total_campus = Campus::find()->where(['status' => Campus::STATUS_ACTIVE])->count();
        $total_group_of_institutes = Institutes::find()->where(['subscription_type' => Institutes::subscription_type_group_of_institutions])->andWhere(['status' => Institutes::STATUS_ACTIVE])->count();
        $total_group_of_individual = Institutes::find()->where(['subscription_type' => Institutes::subscription_type_individual_institution])->andWhere(['status' => Institutes::STATUS_ACTIVE])->count();
        $total_students = StudentDetails::find()->Where(['status' => StudentDetails::STATUS_ACTIVE])->count();
        $total_teachers = TeacherDetails::find()->where(['status' => TeacherDetails::STATUS_ACTIVE])->count();
        $campusCounts = Campus::find()
            ->select(['state_id', 'state.state_name', 'count(*) as campus_count'])
            ->joinWith('state') // Use joinWith to include the state relation
            ->where(['campus.status' => Campus::STATUS_ACTIVE]) // Specify the table name
            ->groupBy('state_id')
            ->asArray()
            ->all();

        // Initialize an array to hold the results
        $stateWiseCounts = [];
        foreach ($campusCounts as $count) {
            if (!empty($count)) {
                // Access the state name directly from the result
                $stateName = isset($count['state_name']) ? $count['state_name'] : "Unknown State";
                $stateWiseCounts[$stateName] = $count['campus_count'];
            }
        }


        // var_dump($stateWiseCounts);
        // exit;
        $data = [];
        $leaveRequest = '';
        $noticeBoard = '';
        $teachers = '';
        if (User::isInstituteAdmin()) {
            $getInstituteIdOfUser = (new institutes())->getInstituteIdOfUser();
            $my_campus = Campus::find()->where(['institute_id' => $getInstituteIdOfUser])->count();
            $getInstituteHasCampusIds = (new Campus())->getInstituteHasCampusIds();
            $student_details_institutes = StudentDetails::find()->where(['in', 'campus_id', $getInstituteHasCampusIds])->count();
            $total_bus_institutes = BusDetails::find()->where(['in', 'campus_id', $getInstituteHasCampusIds])->count();
            $data['all_group_of_campus'] = Campus::find()->joinWith('institute')->where(['institutes.id' => $getInstituteIdOfUser])->all();

            $data['total_agents'] = EmployeeDetails::find()
                ->joinWith('user')
                ->where(['user.user_role' => User::ROLE_AGENT])
                ->andWhere(['in', 'employee_details.campus_id', $getInstituteHasCampusIds])
                ->count();



            $data['total_agents_admissions'] = StudentDetailsAgentLead::find()->where(['in', 'campus_id', $getInstituteHasCampusIds])->count();
            $data['total_drivers'] = EmployeeDetails::find()
                ->joinWith('user')
                ->where(['user.user_role' => User::ROLE_BUS_DRIVER])
                ->andWhere(['in', 'employee_details.campus_id', $getInstituteHasCampusIds])
                ->count();
            $data['payment_details_pending'] = PaymentDetails::find()
                ->where(['status' => PaymentDetails::status_pending])
                ->andWhere(['in', 'campus_id', $getInstituteHasCampusIds])
                ->count();
            $data['payment_details_failed'] = PaymentDetails::find()
                ->where(['status' => PaymentDetails::status_failed])
                ->andWhere(['in', 'campus_id', $getInstituteHasCampusIds])
                ->count();

            $data['total_parents'] = User::find()
                ->where(['user_role' => User::ROLE_PARENT])
                ->andWhere(['in', 'campus_id', $getInstituteHasCampusIds])
                ->count();


            $data['total_fee_collection'] = PaymentDetails::find()->where(['in', 'campus_id', $getInstituteHasCampusIds])
                ->andWhere(['status' => PaymentDetails::status_success])->sum('paid_amount');
            $fee_structures = PayFees::find()->joinWith('feeStructures')
                ->andWhere(['in', 'pay_fees.campus_id', $getInstituteHasCampusIds])
                ->sum('fee_structures.fee');

            $pay_fees_fee_cut = PayFees::find()->where(['in', 'pay_fees.campus_id', $getInstituteHasCampusIds])->sum('fees_cut');
            $total_fee = $fee_structures - $pay_fees_fee_cut;
            $data['total_fee'] = $total_fee;
            $data['pending_fee'] = $data['total_fee'] - $data['total_fee_collection'];
            $data['no_of_classes'] = StudentClass::find()->where(['in', 'campus_id', $getInstituteHasCampusIds])->andWhere(['is_agent' => null])->count();
            $data['no_of_sections'] = ClassSections::find()->where(['in', 'campus_id', $getInstituteHasCampusIds])->count();
        } elseif (User::isAdmin()) {
            $data['all_group_of_institutions'] = Institutes::find()->andWhere(['subscription_type' => Institutes::subscription_type_group_of_institutions])->all();

            $data['all_individual_campus'] = Campus::find()->joinWith('institute')->where(['institutes.subscription_type' => Institutes::subscription_type_individual_institution])->all();
        } else {
            $my_campus = '';
            $student_details_institutes = '';
            $total_bus_institutes = '';
        }
        if (User::isCampusAdmin()  || User::isCampusSubAdmin() || User::isDynamicUser()) {
            $campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);
            $getCampusId = User::getCampusesByUser(Yii::$app->user->identity->id);
            $student_details = StudentDetails::find()->where(['campus_id' => $campus_id])->count();
            $total_bus_campus = BusDetails::find()->where(['campus_id' => $campus_id])->count();



            // echo "Total absent students: " . $absent_students;
            // Get current date and day
            $currentDate = date('Y-m-d');
            //   to get the absent students
            $data['absent_students'] = StudentDetails::find()
                ->joinWith('studentClassAttendances') // Assuming the relation is named 'studentClassAttendances'
                ->where([
                    'student_details.campus_id' => $campus_id,
                    'student_class_attendance.date' => $currentDate,
                ])
                ->groupBy('student_details.id')
                ->having([
                    'SUM(IF(student_class_attendance.status = :present_status, 1, 0))' => 0 // No periods with present status
                ])
                ->addParams([':present_status' => StudentClassAttendance::STATUS_PRESENT])
                ->count();

            // for get the absent teachers
            $data['absent_teachers'] = TeacherDetails::find()
                ->alias('td')
                ->leftJoin('teacher_attenddence ta', 'ta.teacher_details_id = td.id AND ta.date = :currentDate', [':currentDate' => $currentDate])
                ->where(['td.campus_id' => $campus_id])
                ->andWhere(['ta.teacher_details_id' => null])
                ->count();

            //  to get the total fees discount

            $data['total_discount'] = PayFees::find()
                ->where(['campus_id' => $campus_id])
                ->sum('fees_cut');

            // for get the month paid amount

            $currentMonth = date('Y-m'); // Current year and month in YYYY-MM format

            $data['month_amount'] = PaymentDetails::find()
                ->where(['campus_id' => $campus_id])
                ->andWhere(['like', 'created_on', $currentMonth])
                ->sum('paid_amount');

            // Fetch records from the temporary_assign_teacher table for the specified day and date
            $teachers = TemporaryAssignTeacher::find()
                ->where(['date' => $currentDate])
                ->andWhere(['campus_id' => $getCampusId])
                ->andWhere(['OR', ['replaced_teacher_detail_id' => NULL], ['replaced_teacher_detail_id' => '']])
                ->orderBy(['created_on' => SORT_DESC])
                ->limit(5)
                ->all();
            //  var_dump($teachers);exit;
            $leaveRequest = StaffLeaveApplied::find()
                ->where(['status' => StaffLeaveApplied::STATUS_PENDING])
                ->andWhere(['campus_id' => $getCampusId])
                ->orderBy(['created_on' => SORT_DESC])
                ->limit(4)
                ->all();

            $noticeBoard = NoticeBoards::find()
                ->where(['status' => NoticeBoards::STATUS_ACTIVE])
                ->andWhere(['campus_id' => $getCampusId])
                ->orderBy(['created_on' => SORT_DESC])
                ->limit(5)
                ->all();

            $data['total_agents'] = EmployeeDetails::find()
                ->joinWith('user')
                ->where(['user.user_role' => User::ROLE_AGENT])
                ->andWhere(['employee_details.campus_id' => $campus_id])
                ->count();

            $data['total_agents_admissions'] = StudentDetailsAgentLead::find()->where(['campus_id' => $campus_id])->count();
            $data['total_drivers'] = EmployeeDetails::find()
                ->joinWith('user')
                ->where(['user.user_role' => User::ROLE_BUS_DRIVER])
                ->andWhere(['employee_details.campus_id' => $campus_id])
                ->count();
            $data['payment_details_pending'] = PaymentDetails::find()
                ->where(['status' => PaymentDetails::status_pending])
                ->andWhere(['campus_id' => $campus_id])
                ->count();
            $data['payment_details_failed'] = PaymentDetails::find()
                ->where(['status' => PaymentDetails::status_failed])
                ->andWhere(['campus_id' => $campus_id])
                ->count();

            $data['total_parents'] = ParentHasCampus::find()
                ->andWhere(['campus_id' => $campus_id])
                ->count();

            $data['total_fee_collection'] = PaymentDetails::find()->where(['campus_id' => $campus_id])->andWhere(['status' => PaymentDetails::status_success])->sum('paid_amount');
            $fee_structures = PayFees::find()->joinWith('feeStructures')
                ->andWhere(['pay_fees.campus_id' => $campus_id])
                ->sum('fee_structures.fee');

            $pay_fees_fee_cut = PayFees::find()->where(['pay_fees.campus_id' => $campus_id])->sum('fees_cut');
            $total_fee = $fee_structures - $pay_fees_fee_cut;
            $data['total_fee'] = $total_fee;
            $data['pending_fee'] = $data['total_fee'] - $data['total_fee_collection'];
            $data['no_of_classes'] = StudentClass::find()->where(['campus_id' => $campus_id])->andWhere(['is_agent' => null])->count();
            $data['no_of_sections'] = ClassSections::find()->where(['campus_id' => $campus_id])->count();
        } else {
            $student_details = '';
            $total_bus_campus = '';
        }
        if (User::isLibraryManager()) {
            $campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);
            $total_books = LibraryBooks::find()->where(['status' => LibraryBooks::STATUS_ACTIVE])->andWhere(['campus_id' => $campus_id])->count();
            $total_racks = LibraryRacks::find()->where(['campus_id' => $campus_id])->count();
            $total_lib_members = LibraryMembers::find()->where(['status' => LibraryMembers::STATUS_ACTIVE])->andWhere(['campus_id' => $campus_id])->count();
            return $this->render('index_library_manager', [
                'total_books' => $total_books,
                'total_racks' => $total_racks,
                'total_lib_members' => $total_lib_members
            ]);
        }
        if (User::isChefWarden()) {
            $campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);
            $total_hostel = Hostels::find()->where(['status' => Hostels::STATUS_ACTIVE])->andWhere(['campus_id' => $campus_id])->count();
            $total_hostelers = Hostellers::find()->where(['status' => Hostellers::STATUS_ACTIVE])->andWhere(['campus_id' => $campus_id])->count();
            $total_hostel_individual = Hostels::find()->where(['status' => Hostels::STATUS_ACTIVE])->andWhere(['campus_id' => $campus_id])->all();
            $total_warden = User::find()->where(['user_role' => User::ROLE_CHEF_WARDEN, 'campus_id' => $campus_id])->count();
            // $total_warden_assign = 
            return $this->render('index_chief_warden', [
                'total_hostel' => $total_hostel,
                'total_hostelers' => $total_hostelers,
                'total_hostel_individual' => $total_hostel_individual,
                'total_warden' => $total_warden,
            ]);
        }

        if (User::isCampusSubAdmin()) {
            $url = 'index_campus_sub_admin';
        } elseif (User::isCampusAdmin()) {
            $url = 'index_campus_admin';
        } elseif (User::isInstituteAdmin()) {
            $url = 'index_institute_admin';
        } elseif (User::isLibraryManager()) {
            $url = 'index_library_manager';
        } elseif (User::isChefWarden()) {
            $url = 'index_chief_warden';
        } else if (User::isDynamicUser()) {
            $url = 'dynamic_user_view';
        } else {
            $url = 'index';
        }


        return $this->render($url, [
            'total_group_of_institutes' => $total_group_of_institutes,
            'total_group_of_individual' => $total_group_of_individual,
            'my_campus' => $my_campus,
            'student_details_institutes' => $student_details_institutes,
            'total_bus_institutes' => $total_bus_institutes,
            'student_details' => $student_details,
            'total_bus_campus' => $total_bus_campus,
            'data' => $data,
            'leaveRequest' => $leaveRequest,
            'noticeBoard' => $noticeBoard,
            'teachers' => $teachers,
            'total_campus' => $total_campus,
            'total_students' => $total_students,
            'total_teachers' => $total_teachers,
            'stateWiseCounts' => $stateWiseCounts
        ]);
    }



    function actionDashboardNotification()
    {
        $data = [];
        $dashboardNotification = DashboardNotification::find()->where(['campus_id' => (new User)->getCampusId()])->andWhere(['is_read' => 2])->all();
        $notid = [];
        if (!empty($dashboardNotification)) {

            $data['status'] = 'OK';
            foreach ($dashboardNotification as $noti) {
                $notid[] = $noti->asJson();
            }
            $count = DashboardNotification::find()->where(['campus_id' => (new User)->getCampusId()])->andWhere(['is_read' => 2])->count();

            $data['details'] = $notid;
            $data['count'] = $count;
        } else {
            $data['status'] = 'NOK';
            $data['error'] = "No Notification Found";
        }
        return json_encode($data);
    }

    function actionClearNotification()
    {
        $data = [];
        $dashboardNotification = DashboardNotification::find()->where(['campus_id' => (new User)->getCampusId()])->andWhere(['is_read' => 2])->all();
        $notid = [];
        if (!empty($dashboardNotification)) {

            foreach ($dashboardNotification as $noti) {
                $noti->is_read = 1;
                $noti->save(false);
            }
            Toast::success('Notification Cleared.');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            Toast::error('No Notification Found.');
            return $this->redirect(Yii::$app->request->referrer);
        }
        return json_encode($data);
    }

    public function actionFetchChartData()
    {
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);

        // Fetch teachers' data from TeacherDetails table
        $teachersData = TeacherDetails::find()
            ->where(['campus_id' => $campusId])
            ->all();

        // Fetch students' data from StudentDetails table
        $studentsData = StudentDetails::find()
            ->where(['campus_id' => $campusId])
            ->all();

        // Process data for chart
        $teacherCount = $this->processData($teachersData);
        $studentCount = $this->processData($studentsData);

        // Prepare data for rendering in the chart
        $chartData = [
            'teacherCount' => $teacherCount,
            'studentCount' => $studentCount,
        ];

        // Return data as JSON response
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $chartData;
    }


    // Function to process data and get counts year-wise
    private function processData($data)
    {
        $count = [];
        foreach ($data as $record) {
            $year = date('Y', strtotime($record->created_on));
            if (!isset($count[$year])) {
                $count[$year] = 1;
            } else {
                $count[$year]++;
            }
        }

        // Initialize all years with a count of zero if there is no data
        $currentYear = date('Y');
        for ($year = $currentYear; $year >= $currentYear - 2; $year--) {
            if (!isset($count[$year])) {
                $count[$year] = 0;
            }
        }

        // Sort the array by keys (years) in ascending order
        ksort($count);

        return $count;
    }






    public function actionFetchDonutData()
    {
        // Fetch data for different categories (e.g., teachers, students, librarian, warden)
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);

        $teachersCount = TeacherDetails::find()
            ->where(['campus_id' => $campusId])
            ->all();

        $studentsCount = StudentDetails::find()
            ->where(['campus_id' => $campusId])
            ->all();

        $librarianCount = User::find()
            ->where(['user_role' => User::ROLE_LIBRARIAN])
            ->andWhere(['campus_id' => $campusId])
            ->all();

        $wardenCount = User::find()
            ->where(['user_role' => User::ROLE_WARDEN])
            ->andWhere(['campus_id' => $campusId])
            ->all();
        $hostlersCount = Hostellers::find()->Where(['campus_id' => $campusId])
            ->all();

        // Prepare the data as an associative array
        $teacherCount = $teachersCount ? $this->processDataDonut($teachersCount) : 0;
        $studentsCounts = $studentsCount ? $this->processDataDonut($studentsCount) : 0;
        $librarianCounts = $librarianCount ? $this->processDataDonut($librarianCount) : 0;
        $wardenCounts = $wardenCount ? $this->processDataDonut($wardenCount) : 0;
        $hostlersCounts = $hostlersCount ? $this->processDataDonut($hostlersCount) : 0;
        $data = [
            'teachers' => $teacherCount,
            'students' => $studentsCounts,
            'librarian' => $librarianCounts,
            'warden' => $wardenCounts,
            'hostlers' => $hostlersCounts,
        ];

        // Convert the data to JSON format and send it as response
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
    private function processDataDonut($data)
    {
        $count = count($data);
        return $count;
    }
    public function actionGetDataForChart()
    {
        // Fetch campus ID based on the logged-in user
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);

        // Fetch fee pending data from the fee_structures table
        $feePendingData = FeeStructures::find()
            ->select(['YEAR(due_date) AS year', 'SUM(balance_fee) AS fee_pending'])
            ->leftJoin('pay_fees', 'fee_structures.id = pay_fees.fee_structures_id')
            ->andWhere(['pay_fees.campus_id' => $campusId])
            ->groupBy(['YEAR(due_date)'])
            ->asArray()
            ->all();


        // Fetch fee paid data from the payment_details table
        $feePayedData = PaymentDetails::find()
            ->select(['YEAR(created_on) AS year', 'SUM(paid_amount) AS fee_payed'])
            ->andWhere(['campus_id' => $campusId])
            ->groupBy(['YEAR(created_on)'])
            ->asArray()
            ->all();


        // Check if data is empty
        if (empty($feePendingData)) {
            // Set default values for fee pending data
            $feePendingData = [['year' => date('Y'), 'fee_pending' => 0]];
        }

        if (empty($feePayedData)) {
            // Set default values for fee paid data
            $feePayedData = [['year' => date('Y'), 'fee_payed' => 0]];
        }

        // Process data for chart
        $processedFeePendingData = $this->processFeeData($feePendingData);
        $processedFeePayedData = $this->processFeeData($feePayedData);

        // Extract years for x-axis categories
        $years = array_column($feePendingData, 'year');


        $chartData = [
            'feePendingData' => $processedFeePendingData,
            'feePayedData' => $processedFeePayedData,
            'categories' => $years,
        ];


        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $chartData;
    }




    private function processFeeData($data)
    {
        $processedData = [];
        foreach ($data as $item) {
            $year = $item['year'];
            $fee = $item['fee_pending'] ?? $item['fee_payed'] ?? 0; // Use fee_pending if available, otherwise use fee_payed

            // Organize data by year
            $processedData[$year] = $fee;
        }

        // Fill in missing years with zero values
        $currentYear = date('Y');
        $years = [];
        for ($i = 0; $i < 4; $i++) {
            $years[] = $currentYear - $i;
        }

        foreach ($years as $year) {
            if (!isset($processedData[$year])) {
                $processedData[$year] = 0;
            }
        }

        // Sort the data by year
        ksort($processedData);

        return array_values($processedData);
    }
    public function actionGetAttendanceDataForChart()
    {
        $currentDate = date('Y-m-d'); // Get today's date in YYYY-MM-DD format

        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);

        // Initialize an array to store attendance data for both students and teachers
        $attendanceData = [
            'studentAttendance' => [],
            'teacherAttendance' => []
        ];

        // Fetch student attendance data for the current day and specific campus
        $studentAttendance = StudentClassAttendance::find()
            ->select(['COUNT(*) AS attendanceCount'])
            ->joinWith('student as sd')
            ->where(['date' => $currentDate])
            ->andWhere(['sd.campus_id' => $campusId])
            ->scalar();

        // Fetch teacher attendance data for the current day and specific campus
        $teacherAttendance = TeacherAttenddence::find()
            ->select(['COUNT(*) AS attendanceCount'])
            ->joinWith('teacherDetails as td')
            ->where(['date' => $currentDate])
            ->andWhere(['td.campus_id' => $campusId])
            ->scalar();

        // Store the attendance counts for today
        $attendanceData['studentAttendance'] = (int) $studentAttendance;
        $attendanceData['teacherAttendance'] = (int) $teacherAttendance;

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $attendanceData;
    }
    public function actionChangeThemesColor()
    {
        $bgColor = Yii::$app->request->post('bg_color_preference');
        $buttonColor = Yii::$app->request->post('button_color_preference');

        $userId = Yii::$app->user->identity->id;
        $user = User::findOne($userId);

        if (!empty($user)) {
            $user->bg_color_preference = $bgColor;
            $user->button_color_preference = $buttonColor;

            if ($user->save(false)) {
                Yii::$app->session->setFlash('success', 'Color preferences updated successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update color preferences: ' . json_encode($user->errors));
                Yii::error('Failed to update color preferences: ' . json_encode($user->errors));
            }
        } else {
            Yii::$app->session->setFlash('error', 'User not found.');
            Yii::error('User not found.');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
