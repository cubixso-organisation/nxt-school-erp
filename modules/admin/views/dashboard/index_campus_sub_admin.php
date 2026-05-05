<?php
/* @var $this \yii\web\View */

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\PaymentDetails;
use app\modules\admin\models\WebSetting;
use yii\helpers\Url;


$module_manage_campus = (new User())->userHasModuleAccess(User::module_manage_campus);
$module_student_management = (new User())->userHasModuleAccess(User::module_student_management);
$module_bus_management = (new User())->userHasModuleAccess(User::module_bus_management);
$module_payment = (new User())->userHasModuleAccess(User::module_payment);
$module_agent = (new User())->userHasModuleAccess(User::module_agent);
$module_fee_structure = (new User())->userHasModuleAccess(User::module_fee_structure);
$module_fee_assign = (new User())->userHasModuleAccess(User::module_fee_assign);
$module_fee_payments = (new User())->userHasModuleAccess(User::module_fee_payments);




$this->title = 'Dashboard';
$this->params['subheading'] = '';


?>
<?php if (User::isCampusAdmin() || User::isCampusSubAdmin()) { ?>
   <?php if ($module_student_management == 1) { ?>

      <div class="container">
         <div class="row">
            <div class="col-lg-12 col-12">
               <h3>Student Management</h3>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img class="rounded-circle" alt="Total Image" src="../themes/school-management/assets/img/dashimage/students.png">

                           <!-- <i class="fas">&#xf19d;</i> -->
                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>Total Students</h6>
                           <h3><?= !empty($student_details) ? $student_details : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/no-of-classes.png">

                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                           <!-- <i class="fa fa-address-book" data-bs-toggle="tooltip" title="fa fa-address-book"></i> -->
                        </div>
                        <div class="db-info">
                           <h6>No Of Classes</h6>
                           <h3><?= !empty($data['no_of_classes']) ? round($data['no_of_classes'], 2) : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/no-of-section.png">

                           <!-- <i class="fas">&#xf0db;</i> -->
                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>No Of Sections</h6>
                           <h3><?= !empty($data['no_of_sections']) ? round($data['no_of_sections'], 2) : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/Total-parent.png">

                           <!-- <i class="fas">&#xf0c0;</i> -->
                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>Total Parents</h6>
                           <h3><?= !empty($data['total_parents']) ? $data['total_parents'] : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee.png">
                           <!-- <i class="fas">&#xf0c0;</i> -->
                        </div>
                        <div class="db-info">
                           <h6>Total Discount</h6>
                           <h3><?= !empty($data['total_discount']) ? $data['total_discount'] : 0 ?></h3>
                        </div>
                     </div>
                  </div>

               </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <a href="<?= Url::toRoute(['/admin/payment-details']) ?>">
                     <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                           <div class="db-icon avatar-img rounded-circle">
                              <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee.png">
                              <!-- <i class="fas">&#xf0c0;</i> -->
                           </div>
                           <div class="db-info">
                              <h6>Monthly Amount</h6>
                              <h3><?= !empty($data['month_amount']) ? $data['month_amount'] : 0 ?></h3>
                           </div>
                        </div>
                     </div>
                  </a>
               </div>
            </div>


         </div>
      </div>


      <!-- <div class="container">
 <div class="row">
   <div class="col-lg-12 col-12">
      <h3>Student Management</h3>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['/admin/student-details']) ?>">
         <div class="small-box bg-success">
            <div class="inner">
               <h3><?= !empty($student_details) ? $student_details : 0 ?></h3>
               <p>Total Students</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['/admin/student-class']) ?>">
         <div class="small-box bg-warning">
            <div class="inner">
               <h3><?= !empty($data['no_of_classes']) ? round($data['no_of_classes'], 2) : 0 ?></h3>
               <p>No Of Classes</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['/admin/class-sections']) ?>">
         <div class="small-box bg-secondary">
            <div class="inner">
               <h3><?= !empty($data['no_of_sections']) ? round($data['no_of_sections'], 2) : 0 ?></h3>
               <p>No Of Sections</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['/admin/student-details/parent']) ?>">
         <div class="small-box bg-info">
            <div class="inner">
               <h3><?= !empty($data['total_parents']) ? $data['total_parents'] : 0 ?></h3>
               <p>Total Parents</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
 </div>
</div> -->
   <?php } ?>
   <?php if ($module_bus_management == 1) { ?>

      <div class="container">
         <div class="row">
            <div class="col-lg-12 col-12">
               <h3>Bus Management</h3>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-bus.png">

                           <!-- <i class="fas">&#xf207;</i> -->
                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>Total Bus</h6>
                           <h3><span><?= !empty($total_bus_campus) ? $total_bus_campus : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-driver.png">

                           <!-- <i class="fas">&#xf0c0;</i> -->
                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>Total Drivers</h6>
                           <h3><?= !empty($data['total_drivers']) ? $data['total_drivers'] : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- <div class="row">
   <div class="col-lg-12 col-12">
      <h3>Bus Management</h3>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['/admin/bus-details']) ?>">
         <div class="small-box bg-primary">
            <div class="inner">
               <h3><?= !empty($total_bus_campus) ? $total_bus_campus : 0 ?></h3>
               <p>Total Bus</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['/admin/bus-details/bus-driver']) ?>">
         <div class="small-box bg-secondary">
            <div class="inner">
               <h3><?= !empty($data['total_drivers']) ? $data['total_drivers'] : 0 ?></h3>
               <p>Total Drivers</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
</div> -->
   <?php } ?>
   <?php if ($module_agent == 1) { ?>

      <div class="container">
         <div class="row">
            <div class="col-lg-12 col-12">
               <h3>Agent Management</h3>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/agent.png">

                           <!-- <i class="fas">&#xf0c0;</i> -->
                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>Total Agents</h6>
                           <h3><span><?= !empty($data['total_agents']) ? $data['total_agents'] : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/agent-addmission.png">

                           <!-- <i class="fas">&#xf201;</i> -->
                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>Total Agent Admissions</h6>
                           <h3><span><?= !empty($data['total_agents_admissions']) ? $data['total_agents_admissions'] : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/pending-request.png">

                           <!-- <i class="fas">&#xf009;</i> -->
                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>Pending Fee Requests</h6>
                           <h3><span><?= !empty($data['payment_details_pending']) ? $data['payment_details_pending'] : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/rejected-payment-requests.png">

                           <!-- <i class="fas">&#xf00d;</i> -->

                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>Rejected Payment requests</h6>
                           <h3><span><?= !empty($data['payment_details_failed']) ? $data['payment_details_failed'] : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>

         </div>
      </div>

      <!-- <div class="row">
   <div class="col-lg-12 col-12">
      <h3>Agent Management</h3>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['/admin/student-details-agent-lead/agents']) ?>">
         <div class="small-box bg-danger">
            <div class="inner">
               <h3><?= !empty($data['total_agents']) ? $data['total_agents'] : 0 ?></h3>
               <p>Total Agents</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['/admin/student-details-agent-lead']) ?>">
         <div class="small-box bg-warning">
            <div class="inner">
               <h3><?= !empty($data['total_agents_admissions']) ? $data['total_agents_admissions'] : 0 ?></h3>
               <p>Total Agent Admissions</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['payment-details/payment-details-pending', 'PaymentDetailsSearch[status]' => PaymentDetails::status_pending]) ?>">
         <div class="small-box bg-info">
            <div class="inner">
               <h3><?= !empty($data['payment_details_pending']) ? $data['payment_details_pending'] : 0 ?></h3>
               <p>Pending Fee Requests</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
   <div class="col-lg-3 col-6">
      <a href="<?= Url::toRoute(['payment-details/payment-details-pending', 'PaymentDetailsSearch[status]' => PaymentDetails::status_failed]) ?>">
         <div class="small-box bg-danger">
            <div class="inner">
               <h3><?= !empty($data['payment_details_failed']) ? $data['payment_details_failed'] : 0 ?></h3>
               <p>rejected Payment requests</p>
            </div>
            <div class="icon">
               <i class="ion ion-bag"></i>
            </div>
         </div>
      </a>
   </div>
</div> -->
   <?php } ?>
   <?php if ($module_payment == 1) {



      if ((new User())->getCampusId(\Yii::$app->user->identity->id) == 68) {
      }
   ?>
      <div class="container">
         <div class="row">
            <div class="col-lg-12 col-12">
               <h3>Fee Management</h3>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee.png">

                        </div>
                        <div class="db-info">
                           <h6>Total Fee</h6>
                           <h3><span><?= !empty($data['total_fee']) ? round($data['total_fee'], 2) : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee-collection.png">

                        </div>
                        <div class="db-info">
                           <h6>Total Fee Collection</h6>
                           <h3><span><?= !empty($data['total_fee_collection']) ? round($data['total_fee_collection'], 2) : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
               <div class="card bg-comman w-100">
                  <div class="card-body">
                     <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                           <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/pending-fee.png">

                           <!-- <i class="fas">&#xf02e;</i> -->

                           <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                        </div>
                        <div class="db-info">
                           <h6>Pending Fee</h6>
                           <h3><span><?= !empty($data['pending_fee']) ? round($data['pending_fee'], 2) : 0 ?></h3>
                        </div>

                     </div>
                  </div>
               </div>
            </div>

         </div>
      </div>

      <!-- <div class="row">
   <div class="col-lg-12 col-12">
      <h3>Fee Management</h3>
   </div>
   <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
         <div class="inner">
            <h3><?= !empty($data['total_fee']) ? round($data['total_fee'], 2) : 0 ?>/-</h3>
            <p>Total Fee</p>
         </div>
         <div class="icon">
            <i class="ion ion-bag"></i>
         </div>
      </div>
   </div>
   <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
         <div class="inner">
            <h3><?= !empty($data['total_fee_collection']) ? round($data['total_fee_collection'], 2) : 0 ?>/-</h3>
            <p>Total Fee Collection</p>
         </div>
         <div class="icon">
            <i class="ion ion-bag"></i>
         </div>
      </div>
   </div>
   <div class="col-lg-3 col-6">
      <div class="small-box bg-danger">
         <div class="inner">
            <h3><?= !empty($data['pending_fee']) ? round($data['pending_fee'], 2) : 0 ?>/-</h3>
            <p>Pending Fee</p>
         </div>
         <div class="icon">
            <i class="ion ion-bag"></i>
         </div>
      </div>
   </div>
</div> -->
   <?php } ?>
<?php } ?>