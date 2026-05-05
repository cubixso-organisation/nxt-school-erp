<?php
   /* @var $this \yii\web\View */
   
   use app\models\User;
   use app\modules\admin\models\Campus;
   use app\modules\admin\models\Institutes;
   use app\modules\admin\models\PaymentDetails;
   use app\modules\admin\models\WebSetting;
   use yii\helpers\Url;
   
   $this->title = 'Dashboard';
   $this->params['subheading'] = '';
   
  
   
   ?>
<?php if (User::isInstituteAdmin()) { ?>
    <div class="container">
    <div class="row">
   <div class="col-md-12">
      <h4>Campus</h4>
   </div>
   <?php if(!empty($data['all_group_of_campus'])){
      foreach($data['all_group_of_campus'] as $all_campus_data){
      ?>
   <div class="col-md-4">
      <div class="card card-primary collapsed-card">
         <div class="card-header" >
            <h3 class="card-title"><?= $all_campus_data->name_of_the_educational_Institution ?></h3>
            <h6 class="text-danger">
                        Expiry Date:
                        <?= !empty($all_campus_data->expiry_date) && $all_campus_data->expiry_date != '0000-00-00'
                           ? date('d F Y', strtotime($all_campus_data->expiry_date))
                           : "Not Set" ?>
                     </h6>
            <div class="card-tools">
               <?php
                  $login_type_campus = User::login_type_campus;
                      ?>
               <a href="<?=  Url::to(['/admin/users/auto-login', 'id' => $all_campus_data->id, 'type' =>$login_type_campus])?>">
               <button type="button" class="btn btn-primary">Login</button>
               </a>
               <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
               <i class="fas fa-plus"></i>
               </a>
            </div>
         </div>
         <div class="collapse" id="collapseExample">
            <div class="card card-body">
               <?= campus::getCampusDashBoardCards($all_campus_data->id) ?>  
            </div>
         </div>
      </div>
   </div>
   <?php } } ?>
</div>
</div>
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
                                    <!-- <i class="fas">&#xf19d;</i> -->
                                        <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/students.png">

                                    </div>
                                    <div class="db-info">
                                        <h6>Total Students</h6>
                                        <h3><?= !empty($student_details_institutes) ? $student_details_institutes : 0 ?></h3>
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
                                        <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                                        <!-- <i class="fa fa-address-book" data-bs-toggle="tooltip" title="fa fa-address-book"></i> -->
                                        <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/no-of-classes.png">
                                    
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
                                    <!-- <i class="fas">&#xf0db;</i> -->
                                        <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                                            <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/no-of-section.png">

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
                                    <!-- <i class="fas">&#xf0c0;</i> -->
                                         <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/Total-parent.png">
                                       
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
      
   </div>
</div>

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
                                        <!-- <i class="fas">&#xf207;</i> -->
                                        <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                                         <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-bus.png">
                                    
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
                                    <!-- <i class="fas">&#xf0d6;</i> -->
                                        <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
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
                                    
                                    <!-- <i class="fas">&#xf080;</i> -->
                                        <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
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


</div>
<?php } ?>