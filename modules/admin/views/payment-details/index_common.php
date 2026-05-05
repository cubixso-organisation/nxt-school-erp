<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\PaymentDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\Campus;
use app\modules\admin\models\PaymentDetails;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;

$this->title = Yii::t('app', 'Payment Details');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="payment-details-index">



    <div class="card">
       <div class="card-body">
<?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        ['attribute' => 'id', 'visible' => false],



        [
            'attribute' => 'class_id',
            'label' => Yii::t('app', 'Class'),
            'value' => function ($model) {
                if ($model->class) {
                    return $model->class->title;
                } else {
                    return null;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
               ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])

            ->andWhere(['is_agent'=>null])
            ->asArray()->all(), 'id', 'title'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-payment-details-search-class_id']
        ],

        [
            'attribute' => 'section_id',
            'label' => Yii::t('app', 'Section'),
            'value' => function ($model) {
                if ($model->section) {
                    return $model->section->section_name;
                } else {
                    return null;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
               ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])

            ->asArray()->all(), 'id', 'section_name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-payment-details-search-section_id']
        ],

        [
                'attribute' => 'student_id',
                'label' => Yii::t('app', 'Student'),
                'value' => function ($model) {
                    return $model->student->student_name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                   ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])

                ->asArray()->all(), 'id', 'student_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-payment-details-search-student_id']
            ],



            [
                'attribute' => 'name_of_the_father',
                'label' => Yii::t('app', 'Parent Name'),
                'value' => function ($model) {
                    return $model->student->parent->name_of_the_father;
                },
              
            ],


            [
                'attribute' => 'permanent_address',
                'label' => Yii::t('app', 'Address'),
                'value' => function ($model) {
                    return $model->student->parent->permanent_address;
                },
              
            ],





        [
                'attribute' => 'pay_fees_id',
                'label' => Yii::t('app', 'Pay Fees'),
                'value' => function ($model) {
                    return $model->payFees->feeStructures->title;
                },
                'filterType' => GridView::FILTER_SELECT2,

                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\PayFees::find()
                   ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])

                ->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Pay fees', 'id' => 'grid-payment-details-search-pay_fees_id'],

            ],



        [
            'attribute' => 'payment_mode',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getPaymentModeOptionsBadges();
            },


        ],

        [
            'attribute' => 'total_fee_amount',
            'pageSummary' => true,
            'label' => Yii::t('app', 'Total Fee'),
            'value' => function ($model) {
                $student_id = $model->student->id;
          


                $total_fee = (new PaymentDetails())->getTotalFeeByStudentId($student_id);
                return $total_fee;
              
            }
        ],


        

        [
            'attribute' => 'paid_amount',
            'pageSummary' => true


        ],

            'remarks:ntext',


        [
            'attribute' => 'fee_collected_by',
            'label' => Yii::t('app', 'fee collected by'),
            'value' => function ($model) {
                return isset($model->feeCollectedBy) && isset($model->feeCollectedBy->first_name, $model->feeCollectedBy->last_name)
    ? $model->feeCollectedBy->first_name . ' ' . $model->feeCollectedBy->last_name
    : 'N/A';

            },

        ],




        [
            'attribute' => 'created_on',
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


            'attribute' => 'status',
            "format" => 'raw',
            'label' => Yii::t('app', 'Status'),
            'filter'  =>  (new PaymentDetails())->getStateOptions(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'status', 'id' => 'grid-state-search-status'],


                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){                   
                    return $model->getStateOptionsBadges();                   
                },
               
               
            ],


        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view} {update} {delete}',
             'buttons' => [
            'view'=> function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                }
            },
            'update'=> function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                }
            },
            'delete'=> function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
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
    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-payment-details']],
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
    ],
    'showPageSummary' => true,
    'export' => false,
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
        ]) ,
    ],
]); ?>


</div>
</div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).on('change','select[id^=status_list_]',function(){
var id=$(this).attr('data-id');
var val=$(this).val();

$.ajax({
	  type: "POST",
	 
      url: "/school_management_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>