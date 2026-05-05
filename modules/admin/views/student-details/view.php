<?php
   use app\modules\admin\models\PaymentDetails;
   use yii\helpers\Html;
   use yii\widgets\DetailView;
   use kartik\grid\GridView;
   use yii\helpers\Url;
   use app\modules\admin\models\WebSetting;
   
   /* @var $this yii\web\View */
   /* @var $model app\modules\admin\models\StudentDetails */
   
   $this->title = $model->student_name;
   $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Details'), 'url' => ['index']];
   $this->params['breadcrumbs'][] = $this->title;
   ?>
<div class="student-details-view">
   <div class="row">
      <div class="col-sm-9">
         <h2><?= Yii::t('app', 'Student Details')?></h2>
      </div>
   </div>

   <div class="card">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="about-info">
                              <!-- <h4><?= $model->student_name ?></h4> -->
                           </div>
                           <div class="student-profile-head">
                              <div class="row">
                                 <div class="col-lg-4 col-md-4">
                                    <div class="profile-user-box">
                                       <div class="profile-user-img">
                                          <img src="<?=!empty($model->profile_photo) ? $model->profile_photo : Url::base().WebSetting::no_image ?>" alt="Profile">
                                          <!-- <?= Html::a(Yii::t('app', ''), ['update', 'id' => $model->id]) . 
                                            '<div class="form-group students-up-files profile-edit-icon mb-0">' . 
                                            '<div class="d-flex">' . 
                                            '<label class="file-upload profile-upbtn mb-0">' . 
                                            '<i class="feather-edit-3"></i>' . 
                                            '</label>' . 
                                            '</div>' . 
                                            '</div>' ?> -->

                                       </div>
                                       <div class="names-profiles">
                                          <h4><?= $model->student_name ?></h4>
                                          <h5><?= !empty($model->studentClass->title) ? $model->studentClass->title : '' ?></h5>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-md-4 d-flex align-items-center">


                                 
                                 </div>
                                 <div class="col-lg-4 col-md-4 d-flex align-items-center">
                                    <div class="follow-btn-group">
                                       <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-info follow-btns'])?>
                                       <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                                           'class' => 'btn btn-danger ',
                                           'data' => [
                                           'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                           'method' => 'post',
                                          ],
                                         ])
                                        ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-3">
                           <div class="student-personals-grp">
                              <div class="card">
                                 <div class="card-body">
                                    <div class="heading-detail">
                                       <h4>Admission Details :</h4>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/corner-down-right.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=$model->getStateOptionsBadges() ?></h4>
                                          <h5>Status</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/award.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= !empty($model->admission_number) ? $model->admission_number : '' ?></h4>
                                          <h5>Admission No</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/calendar.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= !empty($model->admission_date) ? date('Y-m-d',strtotime($model->admission_date)) : '' ?> </h4>
                                          <h5>Admission Date</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/clipboard.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= !empty($model->studentClass->title) ? $model->studentClass->title : '' ?> </h4>
                                          <h5>Stream</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/hash.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= !empty($model->rool_number) ? $model->rool_number : '' ?></h4>
                                          <h5>Roll Number</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/users.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=isset($model->parent->name_of_the_father) ? $model->parent->name_of_the_father: '' ?></h4>
                                          <h5>Parents Name</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/smartphone.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->parent->contact_number ) ? $model->parent->contact_number  : '' ?></h4>
                                          <h5>Parents Phone No</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/home.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= !empty($model->student_house) ? $model->student_house : '' ?></h4>
                                          <h5>Parents House</h5>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>      
                        </div>
                        <div class="col-lg-3">
                           <div class="student-personals-grp">
                              <div class="card">
                                 <div class="card-body">
                                    <div class="heading-detail">
                                       <h4>Personal Details :</h4>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/user-check.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= $model->student_name ?></h4>
                                          <h5>Full Name</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/scissors.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->gender) ? $model->gender : '' ?></h4>
                                          <h5>Gender</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/thermometer.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->height) ? $model->height : '' ?> </h4>
                                          <h5>Height</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/circle.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->weight) ? $model->weight : '' ?></h4>
                                          <h5>weight</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/calendar.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->date_of_birth) ? $model->date_of_birth : '' ?></h4>
                                          <h5>Date of Birth</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/user.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->category) ? $model->category : '' ?></h4>
                                          <h5>category</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/minus.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->religion) ? $model->religion : '' ?></h4>
                                          <h5>Religion</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/link-2.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->caste) ? $model->caste : '' ?></h4>
                                          <h5>Caste</h5>
                                       </div>
                                    </div>
                                    
                                 </div>
                              </div>
                           </div>      
                        </div>
                        <div class="col-lg-3">
                           <div class="student-personals-grp">
                              <div class="card">
                                 <div class="card-body">
                                    <div class="heading-detail">
                                       <h4></h4>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/at-sign.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= $model->email ?></h4>
                                          <h5>Email</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/droplet.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->bloodGroup->blood_group) ? $model->bloodGroup->blood_group : '' ?></h4>
                                          <h5>Blood Group</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/smartphone.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= $model->phone_number ?></h4>
                                          <h5>Phone</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/home.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= $model->current_address ?></h4>
                                          <h5>Current Address</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/home.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= $model->permanent_address ?></h4>
                                          <h5>Permanent Address</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/flag.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= $model->national_Identification_number ?></h4>
                                          <h5>NIN (National Identification Number)</h5>
                                       </div>
                                    </div>
                                    
                                 </div>
                              </div>
                           </div>      
                        </div>
                        <div class="col-lg-3">
                           <div class="student-personals-grp">
                              <div class="card">
                                 <div class="card-body">
                                    <div class="heading-detail">
                                       <h4>Bus Details</h4>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/truck.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->studentHasBuses[0]->bus->title) ? $model->studentHasBuses[0]->bus->title : '' ?></h4>
                                          <h5>Bus</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/map-pin.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->studentHasBuses[0]->busRoute->point_name) ? $model->studentHasBuses[0]->busRoute->point_name : '' ?></h4>
                                          <h5>Bus Route</h5>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>      
                        </div>
                        <?php foreach ($student_fee as $student_fee_data) { ?>
                        
                        <div class="col-lg-3">
                           <div class="student-personals-grp">
                              <div class="card">
                                 <div class="card-body">
                                    <div class="heading-detail">
                                       <h4>Fee Details</h4>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/book-open.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= !empty($student_fee_data->feeStructures->title) ? $student_fee_data->feeStructures->title : '' ?></h4>
                                          <h5></h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/arrow-right.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= (new PaymentDetails())->getPaymentAmount($student_fee_data->feeStructures->id, $model->id) ?></h4>
                                          <h5>Amount payble</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/arrow-right.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= !empty((new PaymentDetails())->getPaidAmount($model->id, $model->student_class_id, $model->section_id, $student_fee_data->id))?(new PaymentDetails())->getPaidAmount($model->id, $model->student_class_id, $model->section_id, $student_fee_data->id):0 ?></h4>
                                          <h5>Amount Paid</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/arrow-right.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=(new PaymentDetails())->getPaymentAmount($student_fee_data->feeStructures->id, $model->id)-(new PaymentDetails())->getPaidAmount($model->id, $model->student_class_id, $model->section_id, $student_fee_data->id) ?> </h4>
                                          <h5>Pending Amount</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/arrow-right.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?= !empty($student_fee_data->fees_cut)?$student_fee_data->fees_cut:0; ?> </h4>
                                          <h5>Fee Deduction</h5>
                                       </div>
                                    </div>
                                   
                                 
                                 </div>
                              </div>
                           </div>      
                        </div>
                        <?php } ?>
                     </div>
                  </div>
               </div>
               <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-4">Attendance and Payment Details</h4>
                                <ul class="nav nav-pills navtab-bg nav-justified">
                                    <li class="nav-item">
                                        <a href="#home1" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                        Student Class Attendances
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#profile1" data-bs-toggle="tab" aria-expanded="true"
                                            class="nav-link">
                                            Student Attendance Buses
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#messages1" data-bs-toggle="tab" aria-expanded="false"
                                            class="nav-link">
                                            Payment Details
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="home1">
                                    <?php  echo $this->render('../student-class-attendance/index_common', ['model' => $searchModelStudentClassAttendance,'dataProvider'=>$dataProviderStudentClassAttendance ,'searchModel'=>$searchModelStudentClassAttendance]); ?>
                                    </div>
                                    <div class="tab-pane show" id="profile1">
                                    <?php  echo $this->render('../student-attendance-bus/index_common', ['model' => $searchModelStudentBusAttendance,'dataProvider'=>$dataProviderStudentBusAttendance,'searchModel'=>$searchModelStudentBusAttendance]); ?>
                                    </div>
                                    <div class="tab-pane" id="messages1">
                                    <?php  echo $this->render('../payment-details/index_common', ['model' => $searchModelPaymentDetails,'dataProvider'=>$dataProviderPaymentDetails,'searchModel'=>$searchModelPaymentDetails]); ?>
                                    </div>   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <?php  echo $this->render('../exams-result/index_common', ['model' => $searchModelExamsResultSearch,'dataProvider'=>$dataProviderExamsResult,'searchModel'=>$searchModelExamsResultSearch]); ?>

   
   
</div>