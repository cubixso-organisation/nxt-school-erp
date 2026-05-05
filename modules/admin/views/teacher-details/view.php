<?php
   use app\modules\admin\models\WebSetting;
   use yii\helpers\Html;
   use yii\helpers\Url;
   use yii\widgets\DetailView;
   use kartik\grid\GridView;
   use app\models\User;
   
   /* @var $this yii\web\View */
   /* @var $model app\modules\admin\models\TeacherDetails */
   
   $this->title = $model->name;
   $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teacher Details'), 'url' => ['index']];
   $this->params['breadcrumbs'][] = $this->title;
   ?>
<div class="teacher-details-view">
   <!-- <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-sm-9">
               <h2><?= Yii::t('app',  Html::encode($this->title)).' '. 'Details'?></h2>
            </div>
            <div class="col-sm-3" style="margin-top: 15px">
               <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
               <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUBADMIN || \Yii::$app->user->identity->user_role==User::role_campus_sub_admin){ ?>
               <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                  'class' => 'btn btn-danger',
                  'data' => [
                      'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                      'method' => 'post',
                  ],
                  ])
                  ?>   
               <?php  } ?>
            </div>
         </div>
      </div>
   </div> -->
   <div class="card">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="about-info">
                              <h4><?= $model->name; ?> </h4>
                           </div>
                           <div class="student-profile-head">
                             
                              <div class="row">
                                 <div class="col-lg-4 col-md-4">
                                    <div class="profile-user-box">
                                       <div class="profile-user-img">
                                          <!-- <img src="<?= Html::img($model->profile_image) ?>" alt="Profile"> -->
                                          <img src="<?= $model->profile_image ?>" alt="Profile">

                                          <div class="form-group students-up-files profile-edit-icon mb-0">
                                             <div class="uplod d-flex">
                                                <label class="file-upload profile-upbtn mb-0">
                                                <i class="feather-edit-3"></i>
                                             </div>
                                          </div>
                                       </div>
                                     
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-md-4 d-flex align-items-center">
                                    <div class="follow-group">
                                  
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-md-4 d-flex align-items-center">
                                    <div class="follow-btn-group">
                                    <div class="col-sm-3" style="margin-top: 15px">
                                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                                    <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUBADMIN){ ?>
                                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                                       'class' => 'btn btn-danger',
                                       'data' => [
                                          'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                          'method' => 'post',
                                       ],
                                       ])
                                       ?>  
               <?php  } ?>
            </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  <div class="row">
                     <div class="col-lg-4">
                           <div class="student-personals-grp">
                              <div class="card">
                                 <div class="card-body">
                                    <div class="heading-detail">
                                       <h4>Teacher Details :</h4>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/user-check.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->name) ? $model->name : '' ?></h4>
                                          <h5>Full Name</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/at-sign.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->user->username) ? $model->user->username : '' ?></h4>
                                          <h5>User</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/book-open.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->class->title) ? $model->class->title : '' ?> </h4>
                                          <h5>Class</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/book-open.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->section->id) ? $model->section->id : '' ?></h4>
                                          <h5>Section</h5>
                                       </div>
                                    </div>                         
                                 </div>
                              </div>
                           </div>      
                        </div>
                        <div class="col-lg-4">
                           <div class="student-personals-grp">
                              <div class="card">
                                 <div class="card-body">
                                    <div class="heading-detail">
                                       <h4></h4>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/user-check.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->id_number) ? $model->id_number : '' ?></h4>
                                          <h5>Id Number</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/calendar.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->date_of_birth) ? $model->date_of_birth : '' ?></h4>
                                          <h5>Date of Birth</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/calendar.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->academicYear->title) ? $model->academicYear->title : '(Not Set)' ?> </h4>
                                          <h5>Acedemic Year</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/scissors.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                        <h4><?= $model->getGenderOptionsBadges()?></h4> 
                                          <h5>Gender</h5>
                                       </div>
                                    </div>                         
                                 </div>
                              </div>
                           </div>      
                        </div>
                        <div class="col-lg-4">
                           <div class="student-personals-grp">
                              <div class="card">
                                 <div class="card-body">
                                    <div class="heading-detail">
                                       <h4></h4>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/at-sign.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->email) ? $model->email : '' ?></h4>
                                          <h5>email</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/users.svg' ?>" alt="">
                                          <!-- <i class="feather-user"></i> -->
                                       </div>
                                       <div class="views-personal">
                                          <h4><?=!empty($model->father_name) ? $model->father_name : '' ?></h4>
                                          <h5>Father Name</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/map-pin.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                       <h4><?=!empty($model->address) ? $model->address : '' ?></h4>

                                          <h5>Address</h5>
                                       </div>
                                    </div>
                                    <div class="personal-activity">
                                       <div class="personal-icons">
                                       <img src="<?= Url::base().'/themes/school-management/assets/img/dashicons/droplet.svg' ?>" alt="">
                                       </div>
                                       <div class="views-personal">
                                        <h4><?= !empty($model->bloodGroup->title) ? $model->bloodGroup->title : '' ?></h4>
                                          <h5>Blood Group</h5>
                                       </div>
                                    </div>                         
                                 </div>
                              </div>
                           </div>      
                        </div>   
                     </div> 
                  </div>
               </div>
   
   <!-- <div class="card">
      <div class="card-body">
         <div class="row">
            <?php 
               $gridColumn = [
                   ['attribute' => 'id', 'visible' => false],
                   [
                       'attribute' => 'user.username',
                       'label' => Yii::t('app', 'User'),
                   ],
               
                   'name',
               
               
               
                   [
                       'attribute' => 'profile_image', 
                       'format' => 'html', 
                       'value' => function ($model) {
                           return Html::img($model->profile_image, ['width' => '100']);
                       },
                   ],
               
               
                   [
                       'attribute' => 'class.title',
                       'label' => Yii::t('app', 'Class'),
                   ],
                   [
                       'attribute' => 'section.id',
                       'label' => Yii::t('app', 'Section'),
                   ],
                   'id_number',
                   'date_of_birth',
                   [
                       'attribute' => 'academicYear.title',
                       'label' => Yii::t('app', 'Academic Year'),
                   ],
               
               
                   [
               
               
                
               
               
                           'attribute' => 'gender',
                           'format' => 'raw',
                           'value' => function($model){                   
                               return $model->getGenderOptionsBadges();                   
                           },
                          
                          
                       ],
                   [
                       'attribute' => 'bloodGroup.title',
                       'label' => Yii::t('app', 'Blood Group'),
                   ],
                   'father_name',
                   'contact_number',
                   'email:email',
                   'address:ntext',
               ];
               echo DetailView::widget([
                   'model' => $model,
                   'attributes' => $gridColumn
               ]);
               ?>
         </div>
      </div>
   </div> -->
   <div class="row">
                    <!-- <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-4">Teacher Details</h4>
                                <ul class="nav nav-pills navtab-bg nav-justified">
                                    <li class="nav-item">
                                        <a href="#home1" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                        Student Class Attendances
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#messages1" data-bs-toggle="tab" aria-expanded="false"
                                            class="nav-link">
                                           Teacher Provider
                                        </a>
                                    </li>
                                </ul>
                               
                            </div>
                        </div>
                    </div> -->
                </div>

                <?php  echo $this->render('../teacher-attenddence/index_commoun', ['model' => $teacherAttendenceSearchModel,'dataProvider'=>$teacherAttendencedataProvider,'searchModel'=>$teacherAttendenceSearchModel]); ?>
 
</div>