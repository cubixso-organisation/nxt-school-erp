<?php

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use app\modules\admin\widgets\Menu;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\PaymentDetails;
use app\modules\admin\models\Roles;
use app\modules\admin\models\User;
use yii\helpers\Url;
use app\modules\admin\models\WebSetting;

$setting = new WebSetting();
$demo_location = $setting->getSettingBykey('demo_location');

$module =  (new Campus())->checkModuleActivationSTatus(Institutes::activation_modules_agent);

$activation_modules_bus_tracking_module =  (new Campus())->checkModuleActivationSTatus(Institutes::activation_modules_bus_tracking);

$activation_modules_fee_module_module =  (new Campus())->checkModuleActivationSTatus(Institutes::activation_modules_fee_module);

$checkIndividualCampus = (new Campus())->checkIndividualCampus();

$roles =  new Roles();
?>
<li class="<?= Yii::$app->request->url == Url::to(['/admin/dashboard', $schema = true]) ? 'active' : '' ?>">
   <a href="<?= Url::toRoute(['/admin/dashboard', $schema = true]) ?>">
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/db2.png' ?>">
      <span>Dashboard</span>
   </a>
</li>
<!-- <li class="<?= Yii::$app->request->url == Url::to(['/admin/campus/my-campus', $schema = true]) ? 'active' : '' ?>">
   <a href="<?= Url::toRoute(['/admin/campus/my-campus', $schema = true]) ?>">
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/campus1.png' ?>">
      <span>Campus</span>
   </a>
</li> -->
<?php if ($roles->userHasPermission(['createUser', 'updateUser', 'readUser', 'createRoles', 'updateRoles', 'readRoles', 'deleteRoles', 'readKeyPerson', 'createKeyPerson', 'updateKeyPerson'])) { ?>
   <li class="submenu ">


      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/users.png"> -->
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/sm2.png' ?>">
         <span> Users</span> <span class="menu-arrow">
      </a>
      <ul>

         <?php
         if ($roles->userHasPermission(['createRoles', 'updateRoles', 'readRoles', 'deleteRoles'])) {  ?>
            <li>
               <a href="<?= Url::toRoute(['/admin/roles']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/roles']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/user2.png' ?>">Roles</a>
            </li>

         <?php }  ?>

         <?php if ($roles->userHasPermission(['createRoles', 'updateRoles', 'readRoles', 'deleteRoles'])) {  ?>
            <li>
               <a href="<?= Url::toRoute(['/admin/role-has-permissions']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/role-has-permissions']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/user2.png' ?>">Assign Permission To Roles</a>
            </li>

         <?php } ?>

         <li>
            <?php if ($roles->can(['createUser'])) {  ?>

               <a href="<?= Url::toRoute(['/admin/users/create-subrole-user']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/users/create-subrole-user']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/user2.png' ?>">Create Users</a>
            <?php } ?>
            <?php if ($roles->can(['readUser'])) {  ?>
               <a href="<?= Url::toRoute(['/admin/users']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/users']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/user2.png' ?>">Users</a>
            <?php } ?>

         </li>
         <?php if ($roles->userHasPermission(['readKeyPerson', 'createKeyPerson', 'updateKeyPerson'])) { ?>

            <li>
               <a href="<?= Url::toRoute(['/admin/users/key-persons']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/users/key-persons']) ? 'active' : '' ?>" style="white-space: nowrap;"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/sm2.png' ?>">Key Persons</a>
            </li>
         <?php } ?>
         <?php if ($roles->can(['updateUser'])) {  ?>

            <li>
               <a href="<?= Url::toRoute(['/admin/users/index-teacher']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/users/index-teacher']) ? 'active' : '' ?>" style="white-space: nowrap;"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/techedit1.png' ?>"> Teachers Profile Edit </a>
            </li>

            <li>
               <a href="<?= Url::toRoute(['/admin/users/index-parent']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/users/index-parent']) ? 'active' : '' ?>" style="white-space: nowrap;"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/techedit1.png' ?>"> Parent Profile Edit </a>
            </li>
         <?php } ?>

      </ul>


   </li>

<?php } ?>

<?php if ($roles->userHasPermission(['createNotice', 'readNotice'])) {  ?>

   <li class="submenu ">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/notice-management-1.png"> -->
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/time1.png' ?>">
         <span> Notice Management</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/notice-boards']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/notice-boards']) ? 'active' : '' ?>">Notice Boards </span></a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/student-notice-boards']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-notice-boards']) ? 'active' : '' ?>">Class Wise Notice</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/notice-boards/index-student-notice']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/notice-boards/index-student-notice']) ? 'active' : '' ?>">Student Notice</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/notice-boards/index-teacher-notice']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/notice-boards/index-teacher-notice']) ? 'active' : '' ?>">Teacher Notice</a>
         </li>
      </ul>
   </li>

<?php } ?>

<?php if ($roles->userHasPermission([
   'createSubject',
   'updateSubject',
   'readSubject',
   'createTimetable',
   'updateTimetable',
   'readTimetable',
   'createTeacherDetails',
   'updateTeacherDetails',
   'readTeacherDetails',
   'deleteTeacherDetails',
   'createClassSection',
   'updateClassSection',
   'readClassSection',
   'deleteClassSection',
   'createTeacherAttendance',
   'updateTeacherAttendance',
   'readTeacherAttendance',
   'deleteTeacherAttendance',
])) {  ?>

   <li class="submenu ">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/academics-1.png"> -->
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hat1.png' ?>">
         <span> Academics</span><span class="menu-arrow">
      </a>
      <ul>

         <?php if ($roles->userHasPermission(['createSubject', 'updateSubject', 'updateSubject', 'updateSubject', 'createTimetable', 'updateTimetable', 'readTimetable'])) {  ?>

            <li class="submenu ">
               <a href="#"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/lb12.png' ?>">Subject Management <span class="menu-arrow"></a>

               <ul>
                  <?php if ($roles->userHasPermission(['createSubject', 'updateSubject', 'updateSubject', 'updateSubject'])) {  ?>

                     <li>
                        <a href="<?= Url::toRoute(['/admin/subjects']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/subjects']) ? 'active' : '' ?>">Subjects</a>
                     </li>
                     <li>
                        <a href="<?= Url::toRoute(['/admin/subject-groups/create']) ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/subject-groups/create']) ? 'active' : '' ?>">Subject Groups</a>
                     </li>

                  <?php } ?>
                  <?php if ($roles->userHasPermission(['createTimetable', 'updateTimetable', 'readTimetable'])) {  ?>

                     <li>
                        <a href="<?= Url::toRoute(['/admin/subject-timetable']) ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/subject-timetable']) ? 'active' : '' ?>">Subject Timetable</a>
                     </li>
                  <?php } ?>

               </ul>
            </li>

         <?php } ?>

         <?php if ($roles->userHasPermission([

            'createTeacherDetails',
            'updateTeacherDetails',
            'readTeacherDetails',
            'deleteTeacherDetails',
         ])) {  ?>

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
         <?php } ?>

         <?php if ($roles->userHasPermission([

            'createTeacherAttendance',
            'updateTeacherAttendance',
            'readTeacherAttendance',
            'deleteTeacherAttendance',
         ])) {  ?>
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

         <?php } ?>

         <?php if ($roles->userHasPermission([
            'createTeacherAttendance',
            'updateTeacherAttendance',
            'readTeacherAttendance',
            'deleteTeacherAttendance',
         ])) {  ?>
            <li class="submenu ">
               <a href="#"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/levmgm.png' ?>">Attendance Management <span class="menu-arrow"></a>
               <ul>

                  <?php if ($roles->can('createTeacherAttendance')) {  ?>
                     <li>
                        <a href="<?= Url::toRoute(['/admin/attendance-settings']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/attendance-settings']) ? 'active' : '' ?>" style="white-space: nowrap;">Attendance Settings</a>
                     </li>

                  <?php } ?>
                  <li>
                     <a href="<?= Url::toRoute(['/admin/attendance-time-tables']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/attendance-time-tables']) ? 'active' : '' ?>" style="white-space: nowrap;">Attendance Time Tables</a>
                  </li>

                  <li>
                     <a href="<?= Url::toRoute(['/admin/teacher-attenddence']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/teacher-attenddence']) ? 'active' : '' ?>" style="white-space: nowrap;">Teacher's Attendance</a>
                  </li>
               </ul>
            </li>
         <?php } ?>
         <!-- <li>
            <a href="<?= Url::toRoute(['/admin/academic-years']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/academic-years']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/academicmgm1.png' ?>">Academic Year</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/leave-types']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/leave-types']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hm12.png' ?>">Leave Types</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/special-days']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/special-days']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/academicmgm1.png' ?>">Special Days</a>
         </li> -->
         <?php if ($roles->userHasPermission([
            'createDiary',
            'updateDiary',
            'readDiary',
            'deleteDiary',
         ])) {  ?>
            <li>
               <a href="<?= Url::toRoute(['/admin/student-dairy']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-dairy']) ? 'active' : '' ?>"><img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hm12.png' ?>">Student Diary</a>
            </li>

         <?php } ?>
      </ul>
   </li>

<?php } ?>

<?php if ($roles->userHasPermission([
   'createStudentDetails',
   'updateStudentDetails',
   'readStudentDetails',
   'deleteStudentDetails',
   'updateStudentAttendance',
   'readStudentAttendance'
])) {  ?>
   <li class="submenu ">
      <a href="#">
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/sm1.png' ?>">
         <span> Student Management</span><span class="menu-arrow">
      </a>
      <ul>

         <?php if ($roles->userHasPermission([
            'createStudentDetails',
            'updateStudentDetails',
            'readStudentDetails',
            'deleteStudentDetails',

         ])) {  ?>
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
               <a href="<?= Url::toRoute(['/admin/student-details/left-student']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details/left-student']) ? 'active' : '' ?>">Student Left List</a>
            </li>
         <?php } ?>
         <?php if ($roles->can('updateStudentDetails')) {  ?>

            <li>
               <a href="<?= Url::toRoute(['/admin/student-details/promote-students']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details/promote-students']) ? 'active' : '' ?>">Promote Students</a>
            </li>
         <?php } ?>

         <?php if ($roles->userHasPermission([
            'updateStudentAttendance',
            'readStudentAttendance'

         ])) {  ?>
            <li>
               <a href="<?= Url::toRoute(['/admin/student-class-attendance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-class-attendance']) ? 'active' : '' ?>">Student Attendance</a>
               <a href="<?= Url::toRoute(['/admin/student-class-attendance/generate-attendance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-class-attendance/generate-attendance']) ? 'active' : '' ?>">Generate & Update <br> Student's Attendance</a>
            </li>

         <?php } ?>


      </ul>
   </li>
<?php }  ?>

<li class="submenu ">
   <a href="#">
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/bus1.png' ?>">
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
<li class="submenu ">
   <a href="#" style="display: <?= $activation_modules_fee_module_module == 'ok' ? 'block' : 'none' ?>">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/fee-management-1.png"> -->
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/fee1.png' ?>">
      <span> Fee Management</span><span class="menu-arrow">
   </a>
   <ul>
      <li>
         <a href="<?= Url::toRoute(['/admin/pay-fees/assign-fee-details']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/pay-fees/assign-fee-details']) ? 'active' : '' ?> ">Pay Fee</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/pay-fees/pay-old-fee']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/pay-fees/pay-old-fee']) ? 'active' : '' ?>">Pay Old Fee</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/fees-typs/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/fees-typs/create']) ? 'active' : '' ?>">Fees Types</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/fee-structures/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/fee-structures/fee-structure']) ? 'active' : '' ?>">Fee Structure</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/pay-fees']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/pay-fees']) ? 'active' : '' ?>">Bulk Fee Assign</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/payment-details/fees-reports']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details/fees-reports']) ? 'active' : '' ?>">Fees Reports</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/fee-structures/balance-sheet']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/fee-structures/balance-sheet']) ? 'active' : '' ?>">Fee Balance Sheet</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/admin/payment-details']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details']) ? 'active' : '' ?>">Transaction History</a>
      </li>
      <li>
         <a href="<?= Url::to(['/admin/payment-details/today-transactions']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/payment-details/today-transactions']) ? 'active' : '' ?>">Today's Transactions</a>
      </li>
   </ul>
</li>
<li class="submenu ">
   <a href="#" style="display: <?= $module == 'ok' ? 'block' : 'none' ?>">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/agement-management-1.png"> -->
      <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/sm2.png' ?>">
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
<?php
if (Yii::$app->hasModule('inventory')) {

?>
   <li class="submenu ">
      <a href="#" style="display: <?= $module == 'ok' ? 'block' : 'none' ?>">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/agement-management-1.png"> -->
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/inmgmt1.png' ?>">
         <span> Inventory Management </span><span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/item-supplier-list']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/item-supplier-list']) ? 'active' : '' ?>"> Item Supplier</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/item-store']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/item-store']) ? 'active' : '' ?>">Item Store</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/item-category']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/item-category']) ? 'active' : '' ?>">Item Category</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/inventory-items']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/inventory-items']) ? 'active' : '' ?>">Inventory Items</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/add-item-stock']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/add-item-stock']) ? 'active' : '' ?>">Add Item Stock</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/inventory/issue-return-inventory']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/inventory/issue-return-inventory']) ? 'active' : '' ?>">Issue Item</a>
         </li>
      </ul>
   </li>
<?php } ?>
<?php
if (Yii::$app->hasModule('document-generator')) {

?>
   <li class="submenu ">
      <a href="#" style="display: <?= $module == 'ok' ? 'block' : 'none' ?>">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/agement-management-1.png"> -->
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/docgn11.png' ?>">
         <span> Document Generator </span><span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/studentcertificates/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/document-generator/studentcertificates/']) ? 'active' : '' ?>"> Certificate Templates</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/studentcertificates/generate-certificate']) ?>" class="<?= Yii::$app->request->url == Url::to(['/document-generator/studentcertificates/generate-certificate']) ? 'active' : '' ?>">Generate Certificate</a>
         </li>

         <li>
            <a href="<?= Url::toRoute(['/document-generator/bonafide-certificate']) ?>" class="<?= Yii::$app->request->url == Url::to(['/document-generator/bonafide-certificate/']) ? 'active' : '' ?>"> Bonafide Templates</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/bonafide-certificate/generate-certificate']) ?>" class="<?= Yii::$app->request->url == Url::to(['/document-generator/bonafide-certificate/generate-certificate']) ? 'active' : '' ?>">Generate Bonafide</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/studentcertificates/index-certificate-list']) ?>" class="<?= Yii::$app->request->url == Url::to(['/document-generator/studentcertificates/index-certificate-list']) ? 'active' : '' ?>">Certificate List</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/id-card-template']) ?>" class="<?= Yii::$app->request->url == Url::toRoute(['/document-generator/id-card-template']) ? 'active' : '' ?>">ID Card Template</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/document-generator/id-card-template/generate-id-card']) ?>" class="<?= Yii::$app->request->url == Url::toRoute(['/document-generator/generate-id-card']) ? 'active' : '' ?>">Generate ID Card</a>
         </li>
         <!-- <li>
         <a href="<?= Url::toRoute(['/admin/agent-student-join']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/agent-student-join']) ? 'active' : '' ?>">Agent Payment Details</a>
         </li> -->
      </ul>
   </li>
<?php } ?>
<?php
if (Yii::$app->hasModule('hostel-management')) {

?>
   <li class="submenu ">
      <a href="#" style="display: <?= $module == 'ok' ? 'block' : 'none' ?>">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/agement-management-1.png"> -->
         <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/hm12.png' ?>">
         <span> Hostel Management</span><span class="menu-arrow">
      </a>
      <ul>
         <!-- <li>
         <a href="<?= Url::toRoute(['/hostel-management/hostels/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels/create']) ? 'active' : '' ?>">Create Hostel</a>
         </li> -->
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostels']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels']) ? 'active' : '' ?>">Hostels</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostels/create-chief-warden']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels/create-chief-warden']) ? 'active' : '' ?>">Create Chief Warden</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostels/create-warden']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels/create-warden']) ? 'active' : '' ?>">Create Warden</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostels/warden-list']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels/warden-list']) ? 'active' : '' ?>">Warden's List</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/floor']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/floor']) ? 'active' : '' ?>">Floor</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/warden-to-hostel']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/warden-to-hostel']) ? 'active' : '' ?>">Assign Warden to Hostel</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostler-attendance-settings']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostler-attendance-settings']) ? 'active' : '' ?>">Hostelers Attendance Settings</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/rooms']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/rooms']) ? 'active' : '' ?>">Rooms</a>
         </li>
         <!-- <li>
         <a href="<?= Url::toRoute(['/hostel-management/hostellers/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/rooms']) ? 'active' : '' ?>">Create Hostelers</a>
         </li> -->
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostellers']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostellers']) ? 'active' : '' ?>">Hostelers</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostellers-attandance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostellers-attandance']) ? 'active' : '' ?>">Hostlers Attendance</a>
         </li>
         <!-- <li>
            <a href="<?= Url::toRoute(['/hostel-management/hostellers-attandance/index-day-wise-attendance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostellers-attandance/index-day-wise-attendance']) ? 'active' : '' ?>">Today's Hostlers Attendance</a>
         </li> -->
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/warden-attandance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/warden-attandance']) ? 'active' : '' ?>">Warden Attendance</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/hostel-management/warden-attandance/index-day-wise-attendance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/warden-attandance/index-day-wise-attendance']) ? 'active' : '' ?>">Today's Warden Attendance</a>
         </li>
      </ul>
   </li>
<?php } ?>
<?php
if (Yii::$app->hasModule('library-management')) {

?>
   <li class="submenu">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/lb12.png"> -->
         <img alt="Total Image" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/Library management.png' ?>">
         <span> Library Management</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/library-management/library-books']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/library-books']) ? 'active' : '' ?>">Available Books</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/library-management/library-members/index-librarian']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/library-members/index-librarian']) ? 'active' : '' ?>">Create Librarian</a>
         </li>
         <!-- <li>
      <a href="<?= Url::toRoute(['/library-management/library-schools-wise']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/library-schools-wise']) ? 'active' : '' ?>">Library</a>
      </li> -->
         <li>
            <a href="<?= Url::toRoute(['/library-management/issue-books']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/issue-books']) ? 'active' : '' ?>">Issue Books </span></a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/library-management/library-racks']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/library-racks']) ? 'active' : '' ?>">Books Racks</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/library-management/library-members']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/library-members']) ? 'active' : '' ?>">Members</a>
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
         <img alt="Total Image" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/Leave management.png' ?>">
         <span> Leave Management</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/leave-management/staff-leave-types/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/leave-management/create']) ? 'active' : '' ?>">Create Leave Types</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/leave-management/staff-leave-types/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/leave-management/create']) ? 'active' : '' ?>">Leave Types</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/leave-management/staff-leave-applied/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/leave-management/create']) ? 'active' : '' ?>">Leave Application</a>
         </li>
      </ul>
   </li>
<?php } ?>
<?php
if (Yii::$app->hasModule('child-assessment')) {

?>
   <li class="submenu">
      <a href="#">
         <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png"> -->
         <img alt="Total Image" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/Child assessment.png' ?>">
         <span> Child Assessment</span>
         <span class="menu-arrow">
      </a>
      <ul>
         <li>
            <a href="<?= Url::toRoute(['/admin/child-assessment/child-merit']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/child-assessment/child-merit']) ? 'active' : '' ?>">Child Merit</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/child-assessment/merits-assigned-to-class']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/child-assessment/merits-assigned-to-class']) ? 'active' : '' ?>">Assigned Merit</a>
         </li>
         <li>
            <a href="<?= Url::toRoute(['/admin/child-assessment/student-merit-marks']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/child-assessment/student-merit-marks']) ? 'active' : '' ?>">Student Merit Marks</a>
         </li>
      </ul>

   </li>
<?php } ?>
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

         <!-- <li>
               <a href="<?= Url::toRoute(['/exam-management/final-marksheet']) ?>" class="<?= Yii::$app->request->url == Url::to(['/exam-management/final-marksheett']) ? 'active' : '' ?>">Final MarksSheet</a>
            </li> -->
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
<?php
// if (Yii::$app->hasModule('staff-management')) {

?>
<li class="submenu">
   <a href="#">
      <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png"> -->
      <img alt="Total Image" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/Staff Management.png' ?>">
      <span> Staff Management</span>
      <span class="menu-arrow">
   </a>
   <ul>

      <li>
         <a href="<?= Url::toRoute(['/staff-management/staff-designations/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-designations/']) ? 'active' : '' ?>">Designations</a>
      </li>

      <li>
         <a href="<?= Url::toRoute(['/staff-management/staff-details/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-details/']) ? 'active' : '' ?>">Staffs</a>
      </li>

      <li>
         <a href="<?= Url::toRoute(['/staff-management/staff-attendence-settings/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-attendence-settings/']) ? 'active' : '' ?>">Staff Attendance Settings</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/staff-management/staff-attendence/today-attandance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-attendence/today-attandance']) ? 'active' : '' ?>">Today Attendance</a>
      </li>
      <li>
         <a href="<?= Url::toRoute(['/staff-management/staff-attendence/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-attendence/']) ? 'active' : '' ?>">Attendance History</a>
      </li>


      <li class="submenu ">
         <a href="#">Salary <span class="menu-arrow"></a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/staff-management/staff-salary/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/staff-management/staff-salary/']) ? 'active' : '' ?>">Staff Salaries</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/staff-management/monthly-payrolls/']) ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>" class="<?= Yii::$app->request->url == Url::to(['/staff-management/monthly-payrolls/']) ? 'active' : '' ?>">Payrolls</a>
            </li>

         </ul>
      </li>

      <li class="submenu ">
         <a href="#">Payroll Settings <span class="menu-arrow"></a>
         <ul>
            <li>
               <a href="<?= Url::toRoute(['/staff-management/salary-components/']) ?>" class="<?= Yii::$app->request->url == Url::to(['/staff-management/salary-components/']) ? 'active' : '' ?>">Payroll Components</a>
            </li>
            <li>
               <a href="<?= Url::toRoute(['/staff-management/salary-groups/']) ?>" style="display: <?= $demo_location == '1' ? 'none' : 'block' ?>" class="<?= Yii::$app->request->url == Url::to(['/staff-management/salary-groups/']) ? 'active' : '' ?>">Payroll Group</a>
            </li>

         </ul>
      </li>



   </ul>
</li>

<?php // } 
?>