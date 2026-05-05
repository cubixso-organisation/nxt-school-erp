   <?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\StudentDetailsAgentLeadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\AgentStudentJoin;
use app\modules\admin\models\base\StudentDetailsAgentLead;

use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Student Details Agent Leads');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
   	$('.search-form').toggle(1000);
   	return false;
   });";
$this->registerJs($search);


?>
<div class="row">
   <div class="col-xl-3 col-sm-6 col-12 d-flex">
      <div class="card bg-comman w-100">
         <a href="<?= Url::toRoute(['/admin/student-details']) ?>">
            <div class="card-body">
               <div class="db-widgets d-flex justify-content-between align-items-center">
                  <div class="db-info">
                     <h6>Total Pending Payments</h6>
                     <h3><?= !empty($data['total_pending_payments']) ? $data['total_pending_payments'] : 0  ?></h3>
                  </div>
                  <div class="db-icon avatar-img rounded-circle">
                     <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/pending-request.png">
                  </div>
               </div>
            </div>
         </a>
      </div>
   </div>
   <!-- <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
         <div class="inner">
            <h3><?= !empty($data['total_pending_payments']) ? $data['total_pending_payments'] : 0  ?></h3>
            <p>Total Pending Payments</p>
         </div>
         <div class="icon">
            <i class="ion ion-bag"></i>
         </div>
      </div>
      </div> -->
   <div class="col-xl-3 col-sm-6 col-12 d-flex">
      <div class="card bg-comman w-100">
         <a href="<?= Url::toRoute(['/admin/student-details']) ?>">
            <div class="card-body">
               <div class="db-widgets d-flex justify-content-between align-items-center">
                  <div class="db-info">
                     <h6>Total Failed</h6>
                     <h3><?= !empty($data['total_failed_payments']) ? $data['total_failed_payments'] : 0  ?></h3>
                  </div>
                  <div class="db-icon avatar-img rounded-circle">
                     <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/rejected-payment-requests.png">
                  </div>
               </div>
            </div>
         </a>
      </div>
   </div>
   <!-- <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
         <div class="inner">
            <h3><?= !empty($data['total_failed_payments']) ? $data['total_failed_payments'] : 0  ?></h3>
            <p>Total  Failed</p>
         </div>
         <div class="icon">
            <i class="ion ion-bag"></i>
         </div>
      </div>
      </div> -->
   <div class="col-xl-3 col-sm-6 col-12 d-flex">
      <div class="card bg-comman w-100">
         <a href="<?= Url::toRoute(['/admin/student-details']) ?>">
            <div class="card-body">
               <div class="db-widgets d-flex justify-content-between align-items-center">
                  <div class="db-info">
                     <h6>Total Success</h6>
                     <h3><?= !empty($data['total_success_payments']) ? $data['total_success_payments'] : 0  ?></h3>
                  </div>
                  <div class="db-icon avatar-img rounded-circle">
                     <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee-collection.png">
                  </div>
               </div>
            </div>
         </a>
      </div>
   </div>
   <!-- <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
         <div class="inner">
            <h3><?= !empty($data['total_success_payments']) ? $data['total_success_payments'] : 0  ?></h3>
            <p>Total  Success</p>
         </div>
         <div class="icon">
            <i class="ion ion-bag"></i>
         </div>
      </div>
      </div> -->
   <div class="col-xl-3 col-sm-6 col-12 d-flex">
      <div class="card bg-comman w-100">
         <a href="<?= Url::toRoute(['/admin/student-details']) ?>">
            <div class="card-body">
               <div class="db-widgets d-flex justify-content-between align-items-center">
                  <div class="db-info">
                     <h6>Total Received Amount</h6>
                     <h3><?= !empty($data['total_received_amount']) ? round($data['total_received_amount'], 2) : 0  ?>/-</h3>
                  </div>
                  <div class="db-icon avatar-img rounded-circle">
                     <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/total-fee.png">
                  </div>
               </div>
            </div>
         </a>
      </div>
   </div>
   <!-- <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
         <div class="inner">
            <h3><?= !empty($data['total_received_amount']) ? round($data['total_received_amount'], 2) : 0  ?>/-</h3>
            <p>Total Received Amount</p>
         </div>
         <div class="icon">
            <i class="ion ion-bag"></i>
         </div>
      </div>
      </div> -->
</div>
<div class="student-details-agent-lead-index">
   <p>
      <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) { ?>
         <?php Html::a(Yii::t('app', 'Create Student Details Agent Lead'), ['create'], ['class' => 'btn btn-success']) ?>
      <?php  } ?>
      <?php Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
   </p>
   <div class="search-form" style="display:none">
      <?= $this->render('_search', ['model' => $searchModel]); ?>
   </div>
   <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item">
         <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">All</a>
      </li>
      <li class="nav-item">
         <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Admission Allotted</a>
      </li>
      <li class="nav-item">
         <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Admission Not Allotted</a>
      </li>
   </ul>
   <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
         <?php
         $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'id', 'visible' => false],



            [
               'attribute' => 'agent_id',
               'label' => Yii::t('app', 'Agent'),
               'value' => function ($model) {
                  return $model->agent->first_name;
               },
               'filterType' => GridView::FILTER_SELECT2,
               'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()
                  ->where(['user_role' => User::ROLE_AGENT])
                  ->asArray()->all(), 'id', 'first_name'),
               'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Agent Name', 'id' => 'grid-student-details-agent-lead-search-agent_id']
            ],


            [
               'attribute' => 'status',
               "format" => 'raw',
               'label' => Yii::t('app', 'Status'),
               'filter'  => (new StudentDetailsAgentLead())->getStateOptions(),
               'filterType' => GridView::FILTER_SELECT2,
               'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'status', 'id' => 'grid-state-search-status'],



               'value' => function ($data) {
                  $html = '';

                  $html .= '<select id="status_list_' . $data->id . '" data-id="' . $data->id . '" >';
                  $lists = $data->getStateOptions();

                  foreach ($lists as $key => $list) {
                     if ($key == $data->status) {
                        $html .= '<option value="' . $key . '" selected>' . $list . '</option>';
                     } else {
                        $html .= '<option value="' . $key . '">' . $list . '</option>';
                     }
                  }
                  $html .= '</select>';

                  return $html;
               }
            ],





            'student_name',

            'gender',

            'date_of_birth',

            'name_of_the_parent',

            'phone_number',




            [
               'attribute' => 'verified_phone',
               'format' => 'raw',
               'value' => function ($model) {
                  return $model->getStatePhoneNumberVerified();
               },


            ],


            'previous_school_name:ntext',

            'previous_school_address',
            
            'previous_student_class',

            [
               'attribute' => 'student_class_id',
               'label' => Yii::t('app', 'Student Class'),
               'value' => function ($model) {
                  return isset($model->studentClass->title)? $model->studentClass->title:'';
               },
               'filterType' => GridView::FILTER_SELECT2,
               'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                  ->where(['is_agent' => 1])
                  ->andWhere(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])
                  ->asArray()->all(), 'id', 'title'),
               'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-agent-lead-search-student_class_id']
            ],




            [
               'attribute' => 'amount',
               'label' => Yii::t('app', 'amount'),
               'value' => function ($model) {
                  if (!empty($model->agentStudentJoins->amount)) {
                     return $model->agentStudentJoins->amount;
                  }
               },
               'filterType' => GridView::FILTER_SELECT2,
               'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AgentStudentJoin::find()->asArray()->all(), 'id', 'amount'),
               'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'paid amount', 'id' => 'grid-student-details-agent-lead-search-amount']
            ],







            [
               'attribute' => 'special_courses_id',
               'label' => Yii::t('app', 'Student Special Course'),
               'value' => function ($model) {
                  return !empty($model->specialCourses->course_name) ? $model->specialCourses->course_name : '';
               },
               'filterType' => GridView::FILTER_SELECT2,
               'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SpecialCourses::find()->asArray()->all(), 'id', 'course_name'),
               'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Student Special Courses', 'id' => 'grid-student-details-agent-lead-search-special_courses_id']
            ],


            [
               'attribute' => 'hostal_is_required',
               'format' => 'raw',
               'value' => function ($model) {
                  return $model->getStateHostel();
               },


            ],





            [
               'attribute' => 'bus_transport_required',
               'format' => 'raw',
               'value' => function ($model) {
                  return $model->getStateTransport();
               },


            ],



            [
               'attribute' => 'created_on',
               'label' => Yii::t('app', 'Admission Date'),
               'value' => function ($model, $key, $index, $widget) {
                  return date("Y-m-d", strtotime($model->created_on));
               },
               'filterType' => GridView::FILTER_DATE_RANGE,
               'filterWidgetOptions' => ([
                  'attribute' => 'created_on',

                  'pluginOptions' => [
                     'format' => 'YYYY-MM-DD',

                     'locale' => [
                        'format' => 'YYYY-MM-DD',

                     ],

                  ],

               ]),

            ],







            [
               'attribute' => 'payment_status',
               'label' => Yii::t('app', 'Payment Status'),
               'format' => 'raw',

               'value' => function ($model) {
                  return $model->agentStudentJoins->getStateOptionsBadges();
               },

               'filterType' => GridView::FILTER_SELECT2,
               'filter' => (new AgentStudentJoin())->getStateOptions(),
               'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'payment status', 'id' => 'grid-doctor-specializations-search-payment_status']


            ],







            [
               'class' => 'kartik\grid\ActionColumn',
               'template' => '{view} {update} {delete}',
               'buttons' => [
                  'view' => function ($url, $model) {
                     if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                        return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                     }
                  },
                  'update' => function ($url, $model) {
                     if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                        return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                     }
                  },
                  'delete' => function ($url, $model) {
                     if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                        return Html::a('<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url, [
                           'data' => [
                              'method' => 'post',
                              // use it if you want to confirm the action
                              'confirm' => 'Are you sure?',
                           ],
                        ]);
                     }
                  },


               ]
            ],
         ];
         ?>
         <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumn,
            'pjax' => true,
            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-details-agent-lead']],
            'panel' => [
               'type' => GridView::TYPE_PRIMARY,
               'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
            ],
            'export' => false,
            // your toolbar can include the additional full export menu
            'toolbar' => [
               '{export}',
               ExportMenu::widget([
                  'dataProvider' => $dataProvider,
                  'columns' => $gridColumn,
                  'target' => ExportMenu::TARGET_BLANK,
                  'fontAwesome' => true,
                  'dropdownOptions' => [
                     'label' => 'Full',
                     'class' => 'btn btn-default',
                     'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                     ],
                  ],
                  'exportConfig' => [
                     ExportMenu::FORMAT_PDF => false
                  ]
               ]),
            ],
         ]); ?>
      </div>
      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
         <?php echo $this->render('_admission_allotted', ['dataProvider_status_status_admission_ok' => $dataProvider_status_status_admission_ok, 'searchModel' => $searchModel]); ?>
      </div>
      <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
         <?php echo $this->render('_admission_not_allotted', ['dataProvider_status_status_admission_not_ok' => $dataProvider_status_status_admission_not_ok, 'searchModel' => $searchModel]); ?>
      </div>
   </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
   $(document).on('change', 'select[id^=status_list_]', function() {
      var id = $(this).attr('data-id');
      var val = $(this).val();
      $.ajax({
         type: "POST",

         url: "<?= Url::toRoute(['student-details-agent-lead/status-change']) ?>",


         data: {
            id: id,
            val: val
         },
         success: function(data) {
            swal("Good job!", "Status Successfully Changed!", "success");
            window.location.reload();

         }
      });
   });
</script>