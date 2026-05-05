<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\PaymentDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\Campus;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Payment Details');
$this->params['breadcrumbs'][] = $this->title;



?>
<?php
$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],

    ['attribute' => 'id', 'visible' => false],



    // [
    // 'attribute' => 'class_id',
    // 'label' => Yii::t('app', 'Class'),
    // 'value' => function($model){
    // if ($model->class)
    // {return $model->class->title;}
    // else
    // {return NULL;}
    // },
    // 'filterType' => GridView::FILTER_SELECT2,
    // 'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
    // ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])

    // ->asArray()->all(), 'id', 'title'),
    // 'filterWidgetOptions' => [
    // 'pluginOptions' => ['allowClear' => true],
    // ],
    // 'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-payment-details-search-class_id']
    // ],

    // [
    // 'attribute' => 'section_id',
    // 'label' => Yii::t('app', 'Section'),
    // 'value' => function($model){
    // if ($model->section)
    // {return $model->section->section_name;}
    // else
    // {return NULL;}
    // },
    // 'filterType' => GridView::FILTER_SELECT2,
    // 'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
    // ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])

    // ->asArray()->all(), 'id', 'section_name'),
    // 'filterWidgetOptions' => [
    // 'pluginOptions' => ['allowClear' => true],
    // ],
    // 'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-payment-details-search-section_id']
    // ],


    [
        'attribute' => 'id',
        'label' => Yii::t('app', 'Payment Id'),
        'value' => function ($model) {
            return $model->id;
        },

    ],


    [
        'attribute' => 'fee_collected_by',
        'label' => Yii::t('app', 'Collected By'),
        'value' => function ($model) {
            $userId = $model->fee_collected_by;

            $userDetails = User::find()->where(['id' => $userId])->one();
            if (!empty($userDetails)) {
                return $userDetails->first_name;
            }
        },

    ],


    [
        'attribute' => 'created_on',
        'label' => Yii::t('app', 'Paid On'),
        'value' => function ($model) {
            // Use PHP's date function to format the date as "d-m-Y"
            return date('d-m-Y', strtotime($model->created_on));
        },
    ],




[
    'attribute' => 'student_id',
    'label' => Yii::t('app', 'Student ID'),
    'value' => function ($model) {
        return $model->student_id;
    },
],



    [
        'attribute' => 'student_id',
        'label' => Yii::t('app', 'Student'),
        'value' => function ($model) {
            return $model->student->student_name;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
            ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

            ->asArray()->all(), 'id', 'student_name'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-payment-details-search-student_id']
    ],







    [
        'attribute' => 'pay_fees_id',
        'label' => Yii::t('app', 'Fee Type'),
        'value' => function ($model) {
            return $model->payFees->feeStructures->title;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\PayFees::find()
            ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

            ->asArray()->all(), 'id', 'id'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Pay fees', 'id' => 'grid-payment-details-search-pay_fees_id']
    ],




    [
        'attribute' => 'payment_mode',
        'format' => 'raw',
        'value' => function ($model) {
            return $model->getPaymentModeOptionsBadges();
        },


    ],


    [
        'attribute' => 'remarks',
        'label' => Yii::t('app', 'Notes'),
        'format' => 'raw',
        'value' => function ($model) {
            return $model->remarks;
        },


    ],


    [
        'attribute' => 'paid_amount',
        'label' => Yii::t('app', 'Amount'),
        'format' => 'raw',
        'value' => function ($model) {
            return '₹ ' . $model->paid_amount;
        },


    ],











    [
        'attribute' => 'status',
        'format' => 'raw',
        'value' => function ($model) {
            return $model->getStateOptionsBadges();
        },


    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view}',
        'buttons' => [
            'view' => function ($url, $model) {
                $url = 'pay-fees-view-history?id=' . $model->id . '&academic_year_id=' . $model->payFees->academic_year_id;
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {

                    // return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                    return Html::a('Receipt', $url);
                }
            },
            'update' => function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                }
            },
            'delete' => function ($url, $model) {
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


<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();

        $.ajax({
            type: "POST",

            url: "/school_management_backend/gii/default/status-change",


            data: {
                id: id,
                val: val
            },
            success: function(data) {
                swal("Good job!", "Status Successfully Changed!", "success");
            }
        });
    });
</script>
