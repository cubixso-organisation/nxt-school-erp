<?php

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use app\modules\admin\widgets\Menu;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\User;
use app\modules\admin\models\WebSetting;
use yii\helpers\Url;

$setting = new WebSetting();

$demo_location = $setting->getSettingBykey('demo_location');

$module_manage_campus = (new User())->userHasModuleAccess(User::module_manage_campus);
$module_student_management = (new User())->userHasModuleAccess(User::module_student_management);
$module_bus_management = (new User())->userHasModuleAccess(User::module_bus_management);
$module_payment = (new User())->userHasModuleAccess(User::module_payment);
$module_agent = (new User())->userHasModuleAccess(User::module_agent);
$module_fee_structure = (new User())->userHasModuleAccess(User::module_fee_structure);
$module_fee_assign = (new User())->userHasModuleAccess(User::module_fee_assign);
$module_fee_payments = (new User())->userHasModuleAccess(User::module_fee_payments);
// echo "$module_agent";
?>
<li class="<?= Yii::$app->request->url == Url::to(['/admin/dashboard', $schema = true]) ? 'active' : '' ?>">
   <a href="<?= Url::toRoute(['/admin/dashboard', $schema = true]) ?>">
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/dashboard-1.png' ?>">
      <span>Dashboard</span>
   </a>
</li>
<?php if ($module_manage_campus == true) { ?>
   <li class="<?= Yii::$app->request->url == Url::to(['/admin/campus/my-campus', $schema = true]) ? 'active' : '' ?>">
      <a href="<?= Url::toRoute(['/admin/campus/my-campus', $schema = true]) ?>">
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/campus-1.png' ?>">
         <span>Campus</span>
      </a>
   </li>
<?php } ?>
<?php
/*
?>
<li class="submenu ">
   <a href="#">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/users.png"> -->
      <img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/users.png' ?>">
      <span> Users</span> <span class="menu-arrow">
   </a>
   <ul>
      <li>
         <a href="<?= Url::toRoute(['/admin/users']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/users']) ? 'active' : '' ?>">Users</a>
      </li>
      <li>                      
         <a href="<?= Url::toRoute(['/admin/users/key-persons']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/users/key-persons']) ? 'active' : '' ?>">Key Persons</a>
      </li>
   </ul>
</li>

<li class="submenu ">
   <a href="#">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/notice-management-1.png"> -->
      <img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/notice-management-1.png' ?>">
      <span> Notice Management</span>
      <span class="menu-arrow">
   </a>
   <ul>
   <li>
   <a href="<?= Url::toRoute(['/admin/notice-boards']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/notice-boards']) ? 'active' : '' ?>">Notice Boards </span></a>
   </li>
   <li>
   <a href="<?= Url::toRoute(['/admin/student-notice-boards']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-notice-boards']) ? 'active' : '' ?>">Student Notice</a>
   </li>
   </ul>
</li>
<li class="submenu ">
   <a href="#">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/academics-1.png"> -->
      <img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/academics-1.png' ?>">
      <span> Academics</span><span class="menu-arrow">
   </a>
   <ul>
      <li class="submenu ">
         <a href="#">Subject Management <span class="menu-arrow"></a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/subjects']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/subjects']) ? 'active' : '' ?>">Subjects</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/subject-groups/create']) ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/subject-groups/create']) ? 'active' : '' ?>">Subject Groups</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/subject-timetable']) ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/subject-timetable']) ? 'active' : '' ?>">Subject Timetable</a>
            </li>
         </ul>
      </li>
      <li class="submenu ">
         <a href="#"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/teacher-management.png' ?>"> Teacher Mgmt <span class="menu-arrow"></a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/teacher-details']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-details']) ? 'active' : '' ?>" style="white-space: nowrap;"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/teacher-details.png' ?>"> Teachers Details</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/class-teacher']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/class-teacher']) ? 'active' : '' ?>" style="white-space: nowrap;"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/assign-class-teacher.png' ?>">Assign Class Teacher</a>
            </li>
         </ul>
      </li>
      <li class="submenu ">
         <a href="#"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/teacher-management.png' ?>">Class Management <span class="menu-arrow"></a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/student-class']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-class']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/class.png' ?>">Class</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/class-sections']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/class-sections']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/sections.png' ?>">Sections</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/class-rooms']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/class-rooms']) ? 'active' : '' ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/classroom.png' ?>">Class Rooms</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/special-courses']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/special-courses']) ? 'active' : '' ?>"style="white-space: nowrap;"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/special-courses.png' ?>">Special Courses</a>
            </li>
         </ul>
      </li>
      <li class="submenu ">
         <a href="#"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/attendance-settings.png' ?>">Attendance Mgmt <span class="menu-arrow"></a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/attendance-settings']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/attendance-settings']) ? 'active' : '' ?>"style="white-space: nowrap;"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/attendance-settings.png' ?>">Attendance Settings</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/attendance-time-tables']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/attendance-time-tables']) ? 'active' : '' ?>"style="white-space: nowrap;"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/attendance-time-tables.png' ?>">Attendance Time Tables</a>
            </li>
         </ul>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/academic-years']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/academic-years']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/academic-year.png' ?>">Academic Year</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/leave-types']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/leave-types']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/leave-type.png' ?>">Leave Types</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/special-days']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/special-days']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/special-days.png' ?>">Special Days</a>
      </li>
   </ul>
</li>
*/
?>
<?php if ($module_student_management == true) { ?>
   <li class="submenu ">
      <a href="#">
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/student-management-1.png' ?>">
         <span> Student Management</span><span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/parent-details']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/parent-details']) ? 'active' : '' ?>">Parent Details</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details']) ? 'active' : '' ?>">Student Details</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details/student-form-print']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details/student-form-print']) ? 'active' : '' ?>">Student Form</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details/promote-students']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details/promote-students']) ? 'active' : '' ?>">Promote Students</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-class-attendance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-class-attendance']) ? 'active' : '' ?>">Student Attendance</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/notice-boards']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/notice-boards']) ? 'active' : '' ?>">Teacher Notice</a>
         </li>
      </ul>
   </li>

   
<?php } ?>

<?php
/*
<li class="submenu ">
   <a href="#">
   <img alt="img" src="<?= Url::base().'/themes/school-management/assets/img/dashicons/student-management-1.png' ?>">
   <span> Manage Exam</span><span class="menu-arrow">
   </a>
   <ul>
      <li>
         <a href="<?= Url::toRoute(['/admin/exams']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/exams']) ? 'active' : '' ?>">Exams</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/exams-result']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/exams-result']) ? 'active' : '' ?>">Exams Result</a>
      </li>
      </li>
   </ul>
</li>

*/
?>
<?php if ($module_bus_management == true) { ?>
   <li class="submenu ">
      <a href="#">
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/bus-management-1.png' ?>">
         <span> Bus Management</span><span class="menu-arrow">
      </a>
      <ul>
         <li class="submenu ">
            <a href="#">Manage Bus Driver <span class="menu-arrow"></a>
            <ul>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/driver-create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/driver-create']) ? 'active' : '' ?>">Create Bus Driver</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/bus-driver']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/bus-driver']) ? 'active' : '' ?>">View Bus Driver</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/driver-has-bus/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/driver-has-bus/create']) ? 'active' : '' ?>">Assign Bus Driver</a>
               </li>
            </ul>
         </li>
         <li class="submenu ">
            <a href="#">Manage Bus <span class="menu-arrow"></a>
            <ul>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/create']) ? 'active' : '' ?>">Bus Create</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details']) ? 'active' : '' ?>">Bus Details</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-route/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/bus-route/create']) ? 'active' : '' ?>">Bus Route Create</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-route']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/bus-route']) ? 'active' : '' ?>">View Bus Stops</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/student-has-bus']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-has-bus']) ? 'active' : '' ?>">Assign Student To Bus</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/bus-reports']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/bus-reports']) ? 'active' : '' ?>">Bus Reports</a>
               </li>
            </ul>
         </li>
         <li class="submenu ">
            <a href="#">Manage Bus Coordinator <span class="menu-arrow"></a>
            <ul>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/coordinator-create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/coordinator-create']) ? 'active' : '' ?>">Bus Coordinator Create</a>
               </li>
               <li>
                  <a href="<?= Url::toRoute(['/admin/bus-details/bus-coordinator']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/bus-details/bus-coordinator']) ? 'active' : '' ?>">Bus Coordinator</a>
               </li>
            </ul>
         </li>
      </ul>
   </li>
<?php } ?>


<li class="submenu ">
   <a href="#">
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/fee-management-1.png' ?>">
      <span> Fee Management</span><span class="menu-arrow">
   </a>
   <ul>
      <?php if ($module_fee_payments == true) { ?>
         <li>
            <a href="<?= Url::toRoute(['/admin/pay-fees/assign-fee-details']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/pay-fees/assign-fee-details']) ? 'active' : '' ?> ">Pay Fee</a>
         </li>
      <?php } ?>
      <li>
         <a href="<?= Url::toRoute(['/admin/pay-fees/pay-old-fee']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/pay-fees/pay-old-fee']) ? 'active' : '' ?>">Pay Old Fee</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/fees-typs/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/fees-typs/create']) ? 'active' : '' ?>">Fees Types</a>
      </li>
      <?php if ($module_fee_structure == true) { ?>
         <li>
            <a href="<?= Url::toRoute(['/admin/fee-structures/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/fee-structures/create']) ? 'active' : '' ?>">Fee Structure</a>
         </li>
      <?php } ?>
      <?php if ($module_fee_assign == true) { ?>
         <li>
            <a href="<?= Url::toRoute(['/admin/pay-fees']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/pay-fees']) ? 'active' : '' ?>">Bulk Fee Assign</a>
         </li>
      <?php } ?>
      <li>
         <a href="<?= Url::toRoute(['/admin/payment-details/fees-reports']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details/fees-reports']) ? 'active' : '' ?>">Fees Reports</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/fee-structures/balance-sheet']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/fee-structures/balance-sheet']) ? 'active' : '' ?>">Fee Balance Sheet</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/payment-details']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details']) ? 'active' : '' ?>">Transaction History</a>
      </li>

   </ul>
</li>
<li class="submenu ">
   <a href="#">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/academics-1.png"> -->
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hat1.png' ?>">
      <span> Academics</span><span class="menu-arrow">
   </a>
   <ul>
      <li class="submenu ">
         <a href="#"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/lb12.png' ?>">Subject Management <span class="menu-arrow"></a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/subjects']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/subjects']) ? 'active' : '' ?>">Subjects</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/subject-groups/create']) ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/subject-groups/create']) ? 'active' : '' ?>">Subject Groups</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/subject-timetable']) ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/subject-timetable']) ? 'active' : '' ?>">Subject Timetable</a>
            </li>
         </ul>
      </li>
      <li class="submenu ">
         <a href="#"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/techedit1.png' ?>"> Teacher Management <span class="menu-arrow"></a>

         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/teacher-details']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-details']) ? 'active' : '' ?>" style="white-space: nowrap;"> Teachers Details</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/class-teacher']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/class-teacher']) ? 'active' : '' ?>" style="white-space: nowrap;">Assign Class Teacher</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/subject-timetable/teacher-time-table']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-timetable']) ? 'active' : '' ?>" style="white-space: nowrap;">Teacher TimeTable</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/temporary-assign-teacher']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/temporary-assign-teacher']) ? 'active' : '' ?>" style="white-space: nowrap;">Assign Substitute Teacher</a>
            </li>
         </ul>
      </li>
      <li class="submenu ">
         <a href="#"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/clsmgt.png' ?>">Class Management <span class="menu-arrow"></a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/student-class']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-class']) ? 'active' : '' ?>">Class</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/class-sections']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/class-sections']) ? 'active' : '' ?>">Sections</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/class-rooms']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/class-rooms']) ? 'active' : '' ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>">Class Rooms</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/special-courses']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/special-courses']) ? 'active' : '' ?>" style="white-space: nowrap;">Special Courses</a>
            </li>
         </ul>
      </li>
      <li class="submenu ">
         <a href="#"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/levmgm.png' ?>">Attendance Management <span class="menu-arrow"></a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/admin/attendance-settings']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/attendance-settings']) ? 'active' : '' ?>" style="white-space: nowrap;">Attendance Settings</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/admin/attendance-time-tables']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/attendance-time-tables']) ? 'active' : '' ?>" style="white-space: nowrap;">Attendance Time Tables</a>
            </li>

            <li>
               <a href="<?= Url::toRoute(['/admin/teacher-attenddence']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-attenddence']) ? 'active' : '' ?>" style="white-space: nowrap;">Teacher's Attendance</a>
            </li>
         </ul>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/academic-years']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/academic-years']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/academicmgm1.png' ?>">Academic Year</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/leave-types']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/leave-types']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hm12.png' ?>">Leave Types</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/special-days']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/special-days']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/academicmgm1.png' ?>">Special Days</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/student-dairy']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-dairy']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hm12.png' ?>">Student Dairy</a>
      </li>
   </ul>
</li>
<?php
if (Yii::$app->hasModule('exam-management')) {

?>
   <li class="submenu">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png"> -->
         <img alt="Total Image" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/Exam management.png' ?>">
         <span> Exam Management</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/teacher-class-and-subjects']) ?>" class="<?= Yii::$app->request->url == Url::to(['/leave-management/create']) ? 'active' : '' ?>">Update Teacher Details</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/exams']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/exams']) ? 'active' : '' ?>">Exams</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/marks-divition']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/marks-divition']) ? 'active' : '' ?>">Marks Division</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/exam-schedules']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/exam-schedules']) ? 'active' : '' ?>">Schedule Exam</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/exam-schedules/create-time-table']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/exam-schedules/create-time-table']) ? 'active' : '' ?>">Exam Time Table</a>
         </li>

         <li>
            <a href="<?= Url::toRoute(['/admin/exams-result']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/exams-result']) ? 'active' : '' ?>">Exams Result</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/exam-student-marksheet']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/exam-student-marksheet']) ? 'active' : '' ?>">Exam Wise MarksSheet</a>
         </li>

         <li>
            <a href="<?= Url::toRoute(['/exam-management/final-marksheet']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/final-marksheett']) ? 'active' : '' ?>">Final MarksSheet</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/exam-schedules/exam-hall-ticket']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/exam-schedules/exam-hall-ticket']) ? 'active' : '' ?>">Exam Hall Ticket</a>
         </li>

         <li>
            <a href="<?= Url::toRoute(['/exam-management/marksheet-setting']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/marksheet-settings']) ? 'active' : '' ?>">Marksheet Settings</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/exam-management/grade']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/grade']) ? 'active' : '' ?>">Grade Settings</a>
         </li>
      </ul>
   </li>
<?php } ?>

<?php if ($module_agent == true) { ?>
   <li class="submenu ">
      <a href="#">
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/agement-management-1.png' ?>">
         <span> Agent Management</span><span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details-agent-lead/agents-create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details-agent-lead/agents-create']) ? 'active' : '' ?>">Create Agent</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details-agent-lead/agents']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details-agent-lead/agents']) ? 'active' : '' ?>">View Agents</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details-agent-lead']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details-agent-lead']) ? 'active' : '' ?>">Agent Admissions</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/agent-student-join']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/agent-student-join']) ? 'active' : '' ?>">Agent Payment Details</a>
         </li>

      </ul>
   </li>
<?php } ?>
<?php if ($module_agent == true) { ?>
   <li class="submenu ">
      <a href="#">
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/agement-management-1.png' ?>">
         <span> Agent Management</span><span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details-agent-lead/agents-create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details-agent-lead/agents-create']) ? 'active' : '' ?>">Create Agent</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details-agent-lead/agents']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details-agent-lead/agents']) ? 'active' : '' ?>">View Agents</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-details-agent-lead']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details-agent-lead']) ? 'active' : '' ?>">Agent Admissions</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/agent-student-join']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/agent-student-join']) ? 'active' : '' ?>">Agent Payment Details</a>
         </li>

      </ul>
   </li>
<?php } ?>
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