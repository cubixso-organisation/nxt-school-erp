<?php

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use app\modules\admin\widgets\Menu;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\PaymentDetails;
use app\modules\admin\models\User;
use yii\helpers\Url;
use app\modules\admin\models\WebSetting;

$setting = new WebSetting();
$demo_location = $setting->getSettingBykey('demo_location');

$module = (new Campus())->checkModuleActivationSTatus(Institutes::activation_modules_agent);

$activation_modules_bus_tracking_module = (new Campus())->checkModuleActivationSTatus(Institutes::activation_modules_bus_tracking);

$activation_modules_fee_module_module = (new Campus())->checkModuleActivationSTatus(Institutes::activation_modules_fee_module);

$checkIndividualCampus = (new Campus())->checkIndividualCampus();
$campus_id = (new User())->getCampusesByUser(Yii::$app->user->identity->id);
?>
<li class="<?= Yii::$app->request->url == Url::to(['/admin/dashboard', $schema = true]) ? 'active' : '' ?>">
   <a href="<?= Url::toRoute(['/admin/dashboard', $schema = true]) ?>">
      <!-- <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/db2.png' ?>"> -->

      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
         <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
         <path
            d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm320 96c0-26.9-16.5-49.9-40-59.3L280 88c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 204.7c-23.5 9.5-40 32.5-40 59.3c0 35.3 28.7 64 64 64s64-28.7 64-64zM144 176a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm-16 80a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm288 32a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM400 144a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" />
      </svg>
      <span>Dashboard</span>
   </a>
</li>
<li class="<?= Yii::$app->request->url == Url::to(['/admin/admission-enquirie', $schema = true]) ? 'active' : '' ?>">
   <a href="<?= Url::toRoute(['/admin/admission-enquirie', $schema = true]) ?>">
      <!-- <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/db2.png' ?>"> -->

      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#000000" width="24" height="24">
         <path
            d="M256 0C114.62 0 0 114.62 0 256s114.62 256 256 256 256-114.62 256-256S397.38 0 256 0zm88 360h-56v-32h56c8.84 0 16-7.16 16-16s-7.16-16-16-16h-56v-56c0-8.84-7.16-16-16-16s-16 7.16-16 16v56h-56c-8.84 0-16 7.16-16 16s7.16 16 16 16h56v32h-56c-26.51 0-48-21.49-48-48v-56h-32c-26.51 0-48-21.49-48-48s21.49-48 48-48h32v-56c0-26.51 21.49-48 48-48s48 21.49 48 48v56h32c26.51 0 48 21.49 48 48s-21.49 48-48 48h-32v56c0 26.51-21.49 48-48 48z" />
      </svg>
      <span>Admissions</span>




   </a>
</li>
<!-- <li class="<?= Yii::$app->request->url == Url::to(['/admin/campus/my-campus', $schema = true]) ? 'active' : '' ?>">
   <a href="<?= Url::toRoute(['/admin/campus/my-campus', $schema = true]) ?>">
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96 48 96C21.5 96 0 117.5 0 144L0 464c0 26.5 21.5 48 48 48l208 0 0-96c0-35.3 28.7-64 64-64s64 28.7 64 64l0 96 208 0c26.5 0 48-21.5 48-48l0-320c0-26.5-21.5-48-48-48L473.7 96 337.8 5.4zM96 192l32 0c8.8 0 16 7.2 16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-64c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-64zM96 320l32 0c8.8 0 16 7.2 16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-64c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-64zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-16 0 0-16c0-8.8-7.2-16-16-16z"/></svg>
      <span>Campus</span>
   </a>
</li> -->

<!-- <li class="<?= Yii::$app->request->url == Url::to(['/admin/campus-timing']) ? 'active' : '' ?>">
   <a href="<?= Url::toRoute(['/admin/campus-timing']) ?>">
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/campus1.png' ?>">
      <span>Campus Timings</span>
   </a>
</li> -->
<li class="submenu ">
   <a href="#">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/users.png"> -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
         <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
         <path
            d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z" />
      </svg>
      <span> Users</span> <span class="menu-arrow">
   </a>
   <ul>
      <li>
         <a href="<?= Url::toRoute(['/admin/users']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/users']) ? 'active' : '' ?>">Users</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/users/key-persons']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/users/key-persons']) ? 'active' : '' ?>"
            style="white-space: nowrap;">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/sm2.png' ?>">-->Key
            Persons
         </a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/users/index-teacher']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/users/index-teacher']) ? 'active' : '' ?>"
            style="white-space: nowrap;">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/techedit1.png' ?>">-->
            Teachers Profile Edit
         </a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/users/index-parent']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/users/index-parent']) ? 'active' : '' ?>"
            style="white-space: nowrap;">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/techedit1.png' ?>">-->
            Parent Profile Edit
         </a>
      </li>
   </ul>
</li>
<li class="submenu ">
   <a href="#">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/notice-management-1.png"> -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
         <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
         <path
            d="M64 64C28.7 64 0 92.7 0 128L0 384c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64L64 64zm48 160l160 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-160 0c-8.8 0-16-7.2-16-16s7.2-16 16-16zM96 336c0-8.8 7.2-16 16-16l352 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-352 0c-8.8 0-16-7.2-16-16zM376 160l80 0c13.3 0 24 10.7 24 24l0 48c0 13.3-10.7 24-24 24l-80 0c-13.3 0-24-10.7-24-24l0-48c0-13.3 10.7-24 24-24z" />
      </svg>
      <span> Notice <br>Management</span>
      <span class="menu-arrow">
   </a>
   <ul>
      <li>
         <a href="<?= Url::toRoute(['/admin/notice-boards']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/notice-boards']) ? 'active' : '' ?>">Notice Boards
            </span></a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/student-notice-boards']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-notice-boards']) ? 'active' : '' ?>">Class
            Wise Notice</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/notice-boards/index-student-notice']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/notice-boards/index-student-notice']) ? 'active' : '' ?>">Student
            Notice</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/notice-boards/index-teacher-notice']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/notice-boards/index-teacher-notice']) ? 'active' : '' ?>">Teacher
            Notice</a>
      </li>
   </ul>
</li>
<li class="submenu ">
   <a href="#" style="display: <?= $activation_modules_fee_module_module == 'ok' ? 'block' : 'none' ?>">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/fee-management-1.png"> -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
         <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
         <path
            d="M64 64C28.7 64 0 92.7 0 128L0 384c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64L64 64zm64 320l-64 0 0-64c35.3 0 64 28.7 64 64zM64 192l0-64 64 0c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64l0 64-64 0zm64-192c-35.3 0-64-28.7-64-64l64 0 0 64zM288 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
      </svg>
      <span> Fee Management</span><span class="menu-arrow">
   </a>
   <ul>
      <li>
         <a href="<?= Url::toRoute(['/admin/pay-fees/assign-fee-details']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/pay-fees/assign-fee-details']) ? 'active' : '' ?> ">Pay
            Fee</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/pay-fees/pay-old-fee']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/pay-fees/pay-old-fee']) ? 'active' : '' ?>">Pay Old
            Fee</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/fees-typs/create']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/fees-typs/create']) ? 'active' : '' ?>">Fees
            Types</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/fee-structures/']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/fee-structures/fee-structure']) ? 'active' : '' ?>">Fee
            Structure</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/pay-fees']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/pay-fees']) ? 'active' : '' ?>">Bulk Fee Assign</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/payment-details/fees-reports']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details/fees-reports']) ? 'active' : '' ?>">Fees
            Reports</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/fee-structures/balance-sheet']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/fee-structures/balance-sheet']) ? 'active' : '' ?>">Fee
            Balance Sheet</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/payment-details']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details']) ? 'active' : '' ?>">Transaction
            History</a>
      </li>
      <li>
         <a href="<?= Url::to(['/admin/payment-details/today-transactions']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details/today-transactions']) ? 'active' : '' ?>">Today's
            Transactions</a>
      </li>
      <li>
         <a href="<?= Url::to(['/admin/expenses']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details/fee-report-by-fee-type']) ? 'active' : '' ?>">
            Expenses</a>
      </li>
      <li>
         <a href="<?= Url::to(['/admin/payment-details/fee-report-by-fee-type']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details/fee-report-by-fee-type']) ? 'active' : '' ?>">Fee
            Report By Fee Structure</a>
      </li>
   </ul>
</li>
<li class="submenu ">
   <a href="#">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/academics-1.png"> -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
         <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
         <path
            d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
      </svg>
      <span> Academics</span><span class="menu-arrow"></span>
   </a>
   <ul>
      <li class="submenu ">
         <a href="#">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/lb12.png' ?>">-->Subject
            <br>Management <span class="menu-arrow">
         </a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/subjects']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/subjects']) ? 'active' : '' ?>">Subjects</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/subject-groups/create']) ?>"
                  style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/subject-groups/create']) ? 'active' : '' ?>">Subject
                  Groups</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/subject-timetable']) ?>"
                  style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/subject-timetable']) ? 'active' : '' ?>">Subject
                  Timetable</a>
            </li>
         </ul>
      </li>
      <li class="submenu ">
         <a href="#">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/techedit1.png' ?>">-->
            Teacher<br> Management <span class="menu-arrow">
         </a>

         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/teacher-details']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-details']) ? 'active' : '' ?>"
                  style="white-space: nowrap;"> Teachers Details</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/class-teacher']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/class-teacher']) ? 'active' : '' ?>"
                  style="white-space: nowrap;">Assign Class <br> Teacher</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/subject-timetable/teacher-time-table']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-timetable']) ? 'active' : '' ?>"
                  style="white-space: nowrap;">Teacher TimeTable</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/temporary-assign-teacher']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/temporary-assign-teacher']) ? 'active' : '' ?>"
                  style="white-space: nowrap;">Assign Substitute<br> Teacher</a>
            </li>
         </ul>
      </li>
      <li class="submenu ">
         <a href="#">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/clsmgt.png' ?>">-->Class
            <br>Management <span class="menu-arrow">
         </a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/student-class']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/student-class']) ? 'active' : '' ?>">Class</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/class-sections']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/class-sections']) ? 'active' : '' ?>">Sections</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/class-rooms']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/class-rooms']) ? 'active' : '' ?>"
                  style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>">Class Rooms</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/special-courses']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/special-courses']) ? 'active' : '' ?>"
                  style="white-space: nowrap;">Special Courses</a>
            </li>
         </ul>
      </li>
      <li class="submenu ">
         <a href="#">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/levmgm.png' ?>">-->Attendance
            <br>Management <span class="menu-arrow">
         </a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/attendance-settings']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/attendance-settings']) ? 'active' : '' ?>"
                  style="white-space: nowrap;">Attendance Settings</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/attendance-time-tables']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/attendance-time-tables']) ? 'active' : '' ?>"
                  style="white-space: nowrap;">Attendance Time<br> Tables</a>
            </li>

            <li>
               <a href="<?= Url::toRoute(['/admin/teacher-attenddence']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-attenddence']) ? 'active' : '' ?>"
                  style="white-space: nowrap;">Attendance Calendar</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/teacher-attenddence/index-old']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-attenddence/index-old']) ? 'active' : '' ?>"
                  style="white-space: nowrap;"> Attendance</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/teacher-details/not-marked-teachers']) ?>"
                  class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-details/not-marked-teachers']) ? 'active' : '' ?>"
                  style="white-space: nowrap;"> Absent Teachers</a>
            </li>
         </ul>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/academic-years']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/academic-years']) ? 'active' : '' ?>">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/academicmgm1.png' ?>">-->Academic
            Year
         </a>
      </li>
      <!-- <li>
         <a href="<?= Url::toRoute(['/admin/leave-types']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/leave-types']) ? 'active' : '' ?>">
            <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hm12.png' ?>"Leave
            Types
         </a>
      </li> -->
      <li>
         <a href="<?= Url::toRoute(['/admin/leave-requests']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/leave-types']) ? 'active' : '' ?>">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hm12.png' ?>">-->Leave
            Request
         </a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/special-days']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/special-days']) ? 'active' : '' ?>">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/academicmgm1.png' ?>">-->Special
            Days
         </a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/student-dairy']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-dairy']) ? 'active' : '' ?>">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hm12.png' ?>">-->Student
            Dairy
         </a>
         <a href="<?= Url::toRoute(['/admin/student-assessment']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-assessment']) ? 'active' : '' ?>">
            <!--<img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hm12.png' ?>">-->Student
            Assessment
         </a>
      </li>
   </ul>
</li>
<li class="submenu ">
   <a href="#">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
         <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
         <path
            d="M160 0a48 48 0 1 1 0 96 48 48 0 1 1 0-96zM88 384l-17.8 0c-10.9 0-18.6-10.7-15.2-21.1L93.3 248.1 59.4 304.5c-9.1 15.1-28.8 20-43.9 10.9s-20-28.8-10.9-43.9l53.6-89.2c20.3-33.7 56.7-54.3 96-54.3l11.6 0c39.3 0 75.7 20.6 96 54.3l53.6 89.2c9.1 15.1 4.2 34.8-10.9 43.9s-34.8 4.2-43.9-10.9l-33.9-56.3L265 362.9c3.5 10.4-4.3 21.1-15.2 21.1L232 384l0 96c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-96-16 0 0 96c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-96z" />
      </svg>
      <span> Student <br> Management</span><span class="menu-arrow">
   </a>
   <ul>
      <li>
         <a href="<?= Url::toRoute(['/admin/parent-details']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/parent-details']) ? 'active' : '' ?>">Parent
            Details</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/student-details']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-details']) ? 'active' : '' ?>">Student
            Details</a>
      </li>

      <li>
         <a href="<?= Url::toRoute(['/admin/student-details/student-form-print']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-details/student-form-print']) ? 'active' : '' ?>">Student
            Form</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/student-details/promote-students']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-details/promote-students']) ? 'active' : '' ?>">Promote
            Students</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/student-class-attendance']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-class-attendance']) ? 'active' : '' ?>">Student
            Attendance Calendar</a>
         <a href="<?= Url::toRoute(['/admin/student-class-attendance/index-old']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-class-attendance/index-old']) ? 'active' : '' ?>">Student
            Attendance</a>
         <a href="<?= Url::toRoute(['/admin/student-class-attendance/generate-attendance']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-class-attendance/generate-attendance']) ? 'active' : '' ?>">Generate
            & Update <br> Student's Attendance</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/student-details/left-student']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-details/left-student']) ? 'active' : '' ?>">Student
            Left List</a>
      </li>

      <li>
         <a href="<?= Url::toRoute(['/admin/student-details/hostel-students']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/student-details/hostel-students']) ? 'active' : '' ?>">Hostel Students</a>
      </li>

      <li>
         <a href="<?= Url::toRoute(['/admin/pocket-money']) ?>"
            class="<?= Yii::$app->request->url == Url::to(['/admin/pocket-money']) ? 'active' : '' ?>">Pocket Money Transactions</a>
      </li>

   </ul>
</li>
<?php
if (Yii::$app->hasModule('exam-management') && $campus_id !== 87) {

?>
   <li class="submenu">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png"> -->
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M192 0c-41.8 0-77.4 26.7-90.5 64L64 64C28.7 64 0 92.7 0 128L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64l-37.5 0C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM112 192l160 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-160 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
         </svg>
         <span> Exam <br>Management</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/teacher-class-and-subjects']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/leave-management/create']) ? 'active' : '' ?>">Update
               Teacher Details</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/exams']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/exams']) ? 'active' : '' ?>">Exams</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/marks-divition']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/exam-management/marks-divition']) ? 'active' : '' ?>">Marks
               Division</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/exam-schedules']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/exam-management/exam-schedules']) ? 'active' : '' ?>">Schedule
               Exam</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/exam-schedules/create-time-table']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/exam-management/exam-schedules/create-time-table']) ? 'active' : '' ?>">Exam
               Time Table</a>
         </li>

         <li>
            <a href="<?= Url::toRoute(['/admin/exams-result']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/exams-result']) ? 'active' : '' ?>">Exams Result</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/exam-student-marksheet']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/exam-management/exam-student-marksheet']) ? 'active' : '' ?>">Exam
               Wise MarksSheet</a>
         </li>

         <!-- <li>
            <a href="<?= Url::toRoute(['/exam-management/final-marksheet']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/final-marksheett']) ? 'active' : '' ?>">Final MarksSheet</a>
         </li> -->
         <li>
            <a href="<?= Url::toRoute(['/exam-management/exam-schedules/exam-hall-ticket']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/exam-management/exam-schedules/exam-hall-ticket']) ? 'active' : '' ?>">Exam
               Hall Ticket</a>
         </li>

         <li>
            <a href="<?= Url::toRoute(['/exam-management/marksheet-setting']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/exam-management/marksheet-settings']) ? 'active' : '' ?>">Marksheet
               Settings</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/grade']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/exam-management/grade']) ? 'active' : '' ?>">Grade
               Settings</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/online-assessment']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/online-assessment']) ? 'active' : '' ?>">Online
               Assessment</a>
         </li>
      </ul>
   </li>
<?php } ?>

<?php
if (Yii::$app->hasModule('document-generator')) {

?>
   <li class="submenu ">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/agement-management-1.png"> -->
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
         </svg>
         <span> Document <br> Generator </span><span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/studentcertificates/']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/document-generator/studentcertificates/']) ? 'active' : '' ?>">
               Certificate Templates</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/studentcertificates/generate-certificate']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/document-generator/studentcertificates/generate-certificate']) ? 'active' : '' ?>">Generate
               Certificate</a>
         </li>

         <li>
            <a href="<?= Url::toRoute(['/document-generator/bonafide-certificate']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/document-generator/bonafide-certificate/']) ? 'active' : '' ?>">
               Bonafide Templates</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/bonafide-certificate/generate-certificate']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/document-generator/bonafide-certificate/generate-certificate']) ? 'active' : '' ?>">Generate
               Bonafide</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/studentcertificates/index-certificate-list']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/document-generator/studentcertificates/index-certificate-list']) ? 'active' : '' ?>">Certificate
               List</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/id-card-template']) ?>"
               class="<?= Yii::$app->request->url == Url::toRoute(['/document-generator/id-card-template']) ? 'active' : '' ?>">ID
               Card Template</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/id-card-template/generate-id-card']) ?>"
               class="<?= Yii::$app->request->url == Url::toRoute(['/document-generator/generate-id-card']) ? 'active' : '' ?>">Generate
               ID Card</a>
         </li>
         <!-- <li>
         <a href="<?= Url::toRoute(['/admin/agent-student-join']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/agent-student-join']) ? 'active' : '' ?>">Agent Payment Details</a>
         </li> -->
      </ul>
   </li>
<?php } ?>
<!-- <li class="submenu ">
   <a href="#">
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/student-management-1.png' ?>">
      <span> Manage Exam</span><span class="menu-arrow">
   </a>
   <ul>
      <li>
         <a href="<?= Url::toRoute(['/admin/exams']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/exams']) ? 'active' : '' ?>">Exams</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/exams-result']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/exams-result']) ? 'active' : '' ?>">Exams Result</a>
      </li>
   </ul>

</li> -->

<?php
if ($campus_id !== 87) {
?>
   <li class="submenu ">
      <a href="#">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M288 0C422.4 0 512 35.2 512 80l0 16 0 32c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32l0 160c0 17.7-14.3 32-32 32l0 32c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-32-192 0 0 32c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-32c-17.7 0-32-14.3-32-32l0-160c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32c0 0 0 0 0 0l0-32s0 0 0 0l0-16C64 35.2 153.6 0 288 0zM128 160l0 96c0 17.7 14.3 32 32 32l112 0 0-160-112 0c-17.7 0-32 14.3-32 32zM304 288l112 0c17.7 0 32-14.3 32-32l0-96c0-17.7-14.3-32-32-32l-112 0 0 160zM144 400a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm288 0a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM384 80c0-8.8-7.2-16-16-16L208 64c-8.8 0-16 7.2-16 16s7.2 16 16 16l160 0c8.8 0 16-7.2 16-16z" />
         </svg>
         <span> Bus Management</span><span class="menu-arrow">
      </a>
      <ul>
         <li class="submenu ">
            <a href="#">Manage Bus Driver <span class="menu-arrow"></a>
            <ul>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/driver-create']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/driver-create']) ? 'active' : '' ?>">Create
                     Bus Driver</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/bus-driver']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/bus-driver']) ? 'active' : '' ?>">View
                     Bus Driver</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/driver-has-bus/create']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/driver-has-bus/create']) ? 'active' : '' ?>">Assign
                     Bus Driver</a>
               </li>
            </ul>
         </li>
         <li class="submenu ">
            <a href="#">Manage Bus <span class="menu-arrow"></a>
            <ul>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/create']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/create']) ? 'active' : '' ?>">Bus
                     Create</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details']) ? 'active' : '' ?>">Bus
                     Details</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-route/create']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/bus-route/create']) ? 'active' : '' ?>">Bus
                     Route Create</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-route']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/bus-route']) ? 'active' : '' ?>">View Bus
                     Stops</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/student-has-bus']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/student-has-bus']) ? 'active' : '' ?>">Assign
                     Student To Bus</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/bus-reports']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/bus-reports']) ? 'active' : '' ?>">Bus
                     Reports</a>
               </li>
            </ul>
         </li>
         <li class="submenu ">
            <a href="#">Manage Bus Coordinator <span class="menu-arrow"></a>
            <ul>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/coordinator-create']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/coordinator-create']) ? 'active' : '' ?>">Bus
                     Coordinator Create</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/bus-coordinator']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/bus-coordinator']) ? 'active' : '' ?>">Bus
                     Coordinator</a>
               </li>
            </ul>
         </li>
      </ul>
   </li>
<?php } ?>
<?php
if ($campus_id !== 87) {
?>
   <li class="submenu ">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/agement-management-1.png"> -->
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M96 128a128 128 0 1 0 256 0A128 128 0 1 0 96 128zm94.5 200.2l18.6 31L175.8 483.1l-36-146.9c-2-8.1-9.8-13.4-17.9-11.3C51.9 342.4 0 405.8 0 481.3c0 17 13.8 30.7 30.7 30.7l131.7 0c0 0 0 0 .1 0l5.5 0 112 0 5.5 0c0 0 0 0 .1 0l131.7 0c17 0 30.7-13.8 30.7-30.7c0-75.5-51.9-138.9-121.9-156.4c-8.1-2-15.9 3.3-17.9 11.3l-36 146.9L238.9 359.2l18.6-31c6.4-10.7-1.3-24.2-13.7-24.2L224 304l-19.7 0c-12.4 0-20.1 13.6-13.7 24.2z" />
         </svg>
         <span> Agent <br> Management</span><span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details-agent-lead/agents-create']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/student-details-agent-lead/agents-create']) ? 'active' : '' ?>">Create
               Agent</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details-agent-lead/agents']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/student-details-agent-lead/agents']) ? 'active' : '' ?>">View
               Agents</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details-agent-lead']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/student-details-agent-lead']) ? 'active' : '' ?>">Agent
               Admissions</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/agent-student-join']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/agent-student-join']) ? 'active' : '' ?>">Agent
               Payment Details</a>
         </li>
      </ul>
   </li>
<?php } ?>
<?php
if (Yii::$app->hasModule('inventory') && $campus_id !== 87) {

?>
   <li class="submenu ">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/agement-management-1.png"> -->
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M0 488L0 171.3c0-26.2 15.9-49.7 40.2-59.4L308.1 4.8c7.6-3.1 16.1-3.1 23.8 0L599.8 111.9c24.3 9.7 40.2 33.3 40.2 59.4L640 488c0 13.3-10.7 24-24 24l-48 0c-13.3 0-24-10.7-24-24l0-264c0-17.7-14.3-32-32-32l-384 0c-17.7 0-32 14.3-32 32l0 264c0 13.3-10.7 24-24 24l-48 0c-13.3 0-24-10.7-24-24zm488 24l-336 0c-13.3 0-24-10.7-24-24l0-56 384 0 0 56c0 13.3-10.7 24-24 24zM128 400l0-64 384 0 0 64-384 0zm0-96l0-80 384 0 0 80-384 0z" />
         </svg>
         <span> Inventory <br>Management </span><span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/item-supplier-list']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/item-supplier-list']) ? 'active' : '' ?>">
               Item Supplier</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/item-store']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/item-store']) ? 'active' : '' ?>">Item
               Store</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/item-category']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/item-category']) ? 'active' : '' ?>">Item
               Category</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/inventory-items']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/inventory-items']) ? 'active' : '' ?>">Inventory
               Items</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/add-item-stock']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/add-item-stock']) ? 'active' : '' ?>">Add
               Item Stock</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/issue-return-inventory']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/issue-return-inventory']) ? 'active' : '' ?>">Issue
               Item</a>
         </li>
      </ul>
   </li>
<?php } ?>

<?php
if (Yii::$app->hasModule('hostel-management') && $campus_id !== 87) {

?>
   <li class="submenu ">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/agement-management-1.png"> -->
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M0 32C0 14.3 14.3 0 32 0L480 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l0 384c17.7 0 32 14.3 32 32s-14.3 32-32 32l-176 0 0-48c0-26.5-21.5-48-48-48s-48 21.5-48 48l0 48L32 512c-17.7 0-32-14.3-32-32s14.3-32 32-32L32 64C14.3 64 0 49.7 0 32zm96 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zM240 96c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zM112 192c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM328 384c13.3 0 24.3-10.9 21-23.8c-10.6-41.5-48.2-72.2-93-72.2s-82.5 30.7-93 72.2c-3.3 12.8 7.8 23.8 21 23.8l144 0z" />
         </svg>
         <span> Hostel <br> Management</span><span class="menu-arrow">
      </a>
      <ul>
         <!-- <li>
         <a href="<?= Url::toRoute(['/hostel-management/hostels/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels/create']) ? 'active' : '' ?>">Create Hostel</a>
         </li> -->
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostels']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels']) ? 'active' : '' ?>">Hostels</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostels/create-chief-warden']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels/create-chief-warden']) ? 'active' : '' ?>">Create
               Chief Warden</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostels/create-warden']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels/create-warden']) ? 'active' : '' ?>">Create
               Warden</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostels/warden-list']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels/warden-list']) ? 'active' : '' ?>">Warden's
               List</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/floor']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/floor']) ? 'active' : '' ?>">Floor</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/warden-to-hostel']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/warden-to-hostel']) ? 'active' : '' ?>">Assign
               Warden to Hostel</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostler-attendance-settings']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostler-attendance-settings']) ? 'active' : '' ?>">Hostelers
               Attendance Settings</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/rooms']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/rooms']) ? 'active' : '' ?>">Rooms</a>
         </li>
         <!-- <li>
         <a href="<?= Url::toRoute(['/hostel-management/hostellers/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/rooms']) ? 'active' : '' ?>">Create Hostelers</a>
         </li> -->
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostellers']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostellers']) ? 'active' : '' ?>">Hostelers</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostellers-attandance']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostellers-attandance']) ? 'active' : '' ?>">Hostlers
               Attendance</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostellers-attandance/index-day-wise-attendance']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostellers-attandance/index-day-wise-attendance']) ? 'active' : '' ?>">Today's
               Hostlers Attendance</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/warden-attandance']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/warden-attandance']) ? 'active' : '' ?>">Warden
               Attendance</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/warden-attandance/index-day-wise-attendance']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/hostel-management/warden-attandance/index-day-wise-attendance']) ? 'active' : '' ?>">Today's
               Warden Attendance</a>
         </li>
      </ul>
   </li>
<?php } ?>
<?php
if (Yii::$app->hasModule('library-management') && $campus_id !== 87) {

?>
   <li class="submenu">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/lb12.png"> -->
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
         </svg>
         <span> Library <br> Management</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/library-management/library-books']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/library-management/library-books']) ? 'active' : '' ?>">Available
               Books</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/library-management/library-members/index-librarian']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/library-management/library-members/index-librarian']) ? 'active' : '' ?>">Create
               Librarian</a>
         </li>
         <!-- <li>
      <a href="<?= Url::toRoute(['/library-management/library-schools-wise']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/library-schools-wise']) ? 'active' : '' ?>">Library</a>
      </li> -->
         <li>
            <a href="<?= Url::toRoute(['/library-management/issue-books']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/library-management/issue-books']) ? 'active' : '' ?>">Issue
               Books </span></a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/library-management/library-racks']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/library-management/library-racks']) ? 'active' : '' ?>">Books
               Racks</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/library-management/library-members']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/library-management/library-members']) ? 'active' : '' ?>">Members</a>
         </li>
      </ul>
   </li>
<?php } ?>
<?php
if (Yii::$app->hasModule('leave-management')) {

?>
   <li class="submenu">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png"> -->
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M208 96a48 48 0 1 0 0-96 48 48 0 1 0 0 96zM123.7 200.5c1-.4 1.9-.8 2.9-1.2l-16.9 63.5c-5.6 21.1-.1 43.6 14.7 59.7l70.7 77.1 22 88.1c4.3 17.1 21.7 27.6 38.8 23.3s27.6-21.7 23.3-38.8l-23-92.1c-1.9-7.8-5.8-14.9-11.2-20.8l-49.5-54 19.3-65.5 9.6 23c4.4 10.6 12.5 19.3 22.8 24.5l26.7 13.3c15.8 7.9 35 1.5 42.9-14.3s1.5-35-14.3-42.9L281 232.7l-15.3-36.8C248.5 154.8 208.3 128 163.7 128c-22.8 0-45.3 4.8-66.1 14l-8 3.5c-32.9 14.6-58.1 42.4-69.4 76.5l-2.6 7.8c-5.6 16.8 3.5 34.9 20.2 40.5s34.9-3.5 40.5-20.2l2.6-7.8c5.7-17.1 18.3-30.9 34.7-38.2l8-3.5zm-30 135.1L68.7 398 9.4 457.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L116.3 441c4.6-4.6 8.2-10.1 10.6-16.1l14.5-36.2-40.7-44.4c-2.5-2.7-4.8-5.6-7-8.6zM550.6 153.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L530.7 224 384 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l146.7 0-25.4 25.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l80-80c12.5-12.5 12.5-32.8 0-45.3l-80-80z" />
         </svg>
         <span> Leave Management</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/leave-management/staff-leave-types/create']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/leave-management/create']) ? 'active' : '' ?>">Create Leave
               Types</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/leave-management/staff-leave-types/']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/leave-management/create']) ? 'active' : '' ?>">Leave
               Types</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/leave-management/staff-leave-applied/']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/leave-management/create']) ? 'active' : '' ?>">Leave
               Application</a>
         </li>
      </ul>
   </li>
<?php } ?>
<?php
if (Yii::$app->hasModule('child-assessment') && $campus_id !== 87) {

?>
   <li class="submenu">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png"> -->
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M320 0a40 40 0 1 1 0 80 40 40 0 1 1 0-80zm44.7 164.3L375.8 253c1.6 13.2-7.7 25.1-20.8 26.8s-25.1-7.7-26.8-20.8l-4.4-35-7.6 0-4.4 35c-1.6 13.2-13.6 22.5-26.8 20.8s-22.5-13.6-20.8-26.8l11.1-88.8L255.5 181c-10.1 8.6-25.3 7.3-33.8-2.8s-7.3-25.3 2.8-33.8l27.9-23.6C271.3 104.8 295.3 96 320 96s48.7 8.8 67.6 24.7l27.9 23.6c10.1 8.6 11.4 23.7 2.8 33.8s-23.7 11.4-33.8 2.8l-19.8-16.7zM40 64c22.1 0 40 17.9 40 40l0 40 0 80 0 40.2c0 17 6.7 33.3 18.7 45.3l51.1 51.1c8.3 8.3 21.3 9.6 31 3.1c12.9-8.6 14.7-26.9 3.7-37.8l-15.2-15.2-32-32c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l32 32 15.2 15.2c0 0 0 0 0 0l25.3 25.3c21 21 32.8 49.5 32.8 79.2l0 78.9c0 26.5-21.5 48-48 48l-66.7 0c-17 0-33.3-6.7-45.3-18.7L28.1 393.4C10.1 375.4 0 351 0 325.5L0 224l0-64 0-56C0 81.9 17.9 64 40 64zm560 0c22.1 0 40 17.9 40 40l0 56 0 64 0 101.5c0 25.5-10.1 49.9-28.1 67.9L512 493.3c-12 12-28.3 18.7-45.3 18.7L400 512c-26.5 0-48-21.5-48-48l0-78.9c0-29.7 11.8-58.2 32.8-79.2l25.3-25.3c0 0 0 0 0 0l15.2-15.2 32-32c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-32 32-15.2 15.2c-11 11-9.2 29.2 3.7 37.8c9.7 6.5 22.7 5.2 31-3.1l51.1-51.1c12-12 18.7-28.3 18.7-45.3l0-40.2 0-80 0-40c0-22.1 17.9-40 40-40z" />
         </svg>
         <span> Child Assessment</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/child-assessment/child-merit']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/child-assessment/child-merit']) ? 'active' : '' ?>">Child
               Merit</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/child-assessment/merits-assigned-to-class']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/child-assessment/merits-assigned-to-class']) ? 'active' : '' ?>">Assigned
               Merit</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/child-assessment/student-merit-marks']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/child-assessment/student-merit-marks']) ? 'active' : '' ?>">Student
               Merit Marks</a>
         </li>
      </ul>

   </li>
   <!-- <li class="submenu">
      <a href="#">
         <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
            !Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.
            <path
               d="M320 0a40 40 0 1 1 0 80 40 40 0 1 1 0-80zm44.7 164.3L375.8 253c1.6 13.2-7.7 25.1-20.8 26.8s-25.1-7.7-26.8-20.8l-4.4-35-7.6 0-4.4 35c-1.6 13.2-13.6 22.5-26.8 20.8s-22.5-13.6-20.8-26.8l11.1-88.8L255.5 181c-10.1 8.6-25.3 7.3-33.8-2.8s-7.3-25.3 2.8-33.8l27.9-23.6C271.3 104.8 295.3 96 320 96s48.7 8.8 67.6 24.7l27.9 23.6c10.1 8.6 11.4 23.7 2.8 33.8s-23.7 11.4-33.8 2.8l-19.8-16.7zM40 64c22.1 0 40 17.9 40 40l0 40 0 80 0 40.2c0 17 6.7 33.3 18.7 45.3l51.1 51.1c8.3 8.3 21.3 9.6 31 3.1c12.9-8.6 14.7-26.9 3.7-37.8l-15.2-15.2-32-32c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l32 32 15.2 15.2c0 0 0 0 0 0l25.3 25.3c21 21 32.8 49.5 32.8 79.2l0 78.9c0 26.5-21.5 48-48 48l-66.7 0c-17 0-33.3-6.7-45.3-18.7L28.1 393.4C10.1 375.4 0 351 0 325.5L0 224l0-64 0-56C0 81.9 17.9 64 40 64zm560 0c22.1 0 40 17.9 40 40l0 56 0 64 0 101.5c0 25.5-10.1 49.9-28.1 67.9L512 493.3c-12 12-28.3 18.7-45.3 18.7L400 512c-26.5 0-48-21.5-48-48l0-78.9c0-29.7 11.8-58.2 32.8-79.2l25.3-25.3c0 0 0 0 0 0l15.2-15.2 32-32c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-32 32-15.2 15.2c-11 11-9.2 29.2 3.7 37.8c9.7 6.5 22.7 5.2 31-3.1l51.1-51.1c12-12 18.7-28.3 18.7-45.3l0-40.2 0-80 0-40c0-22.1 17.9-40 40-40z" />
         </svg>
         <span> DayCare Assessment</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/daycare']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/daycare']) ? 'active' : '' ?>">Day Care</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/daycare-activitis']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/daycare-activitis']) ? 'active' : '' ?>">Daycare
               Teachers</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/daycare-teachers']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/daycare-teachers']) ? 'active' : '' ?>">Daycare
               Activities<a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/daycare-attendance']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/admin/daycare-attendance']) ? 'active' : '' ?>">Daycare
               Attendance<a>
         </li>
      </ul>

   </li> -->
<?php } ?>

<?php
// if (Yii::$app->hasModule('staff-management')) {
if ($campus_id !== 87) {

?>
   <li class="submenu">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png"> -->
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
            <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path
               d="M192 0c-41.8 0-77.4 26.7-90.5 64L64 64C28.7 64 0 92.7 0 128L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64l-37.5 0C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM128 256a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zM80 432c0-44.2 35.8-80 80-80l64 0c44.2 0 80 35.8 80 80c0 8.8-7.2 16-16 16L96 448c-8.8 0-16-7.2-16-16z" />
         </svg>
         <span> Staff Management</span>
         <span class="menu-arrow">
      </a>
      <ul>

         <li>
            <a href="<?= Url::toRoute(['/staff-management/staff-designations/']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-designations/']) ? 'active' : '' ?>">Designations</a>
         </li>

         <li>
            <a href="<?= Url::toRoute(['/staff-management/staff-details/']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-details/']) ? 'active' : '' ?>">Staffs</a>
         </li>

         <li>
            <a href="<?= Url::toRoute(['/staff-management/staff-attendence-settings/']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-attendence-settings/']) ? 'active' : '' ?>">Staff
               Attendance Settings</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/staff-management/staff-attendence/today-attandance']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-attendence/today-attandance']) ? 'active' : '' ?>">Today
               Attendance</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/staff-management/staff-attendence/']) ?>"
               class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-attendence/']) ? 'active' : '' ?>">Attendance
               History</a>
         </li>


         <li class="submenu ">
            <a href="#">Salary <span class="menu-arrow" style="color:#2448cb"></a>
            <ul>
               <li>
                  <a href="<?= Url::toRoute(['/staff-management/staff-salary/']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-salary/']) ? 'active' : '' ?>">Staff
                     Salaries</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/staff-management/monthly-payrolls/']) ?>"
                     style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/staff-management/monthly-payrolls/']) ? 'active' : '' ?>">Payrolls</a>
               </li>

            </ul>
         </li>

         <li class="submenu ">
            <a href="#">Payroll Settings <span class="menu-arrow" style="color:#2448bb"></a>
            <ul>
               <li>
                  <a href="<?= Url::toRoute(['/staff-management/salary-components/']) ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/staff-management/salary-components/']) ? 'active' : '' ?>">Payroll
                     Components</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/staff-management/salary-groups/']) ?>"
                     style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>"
                     class="<?= Yii::$app->request->url == Url::to(['/staff-management/salary-groups/']) ? 'active' : '' ?>">Payroll
                     Group</a>
               </li>

            </ul>
         </li>



      </ul>
   </li>

<?php }
?>