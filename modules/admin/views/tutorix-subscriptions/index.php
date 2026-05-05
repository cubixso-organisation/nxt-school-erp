<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\TutorixSubscriptionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

$this->title = Yii::t('app', 'Tutorix Subscriptions');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 

?>
<div class="tutorix-subscriptions-index">
<div class="card">
       <div class="card-body">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_CAMPUS_ADMIN){ ?>
        <?= Html::a(Yii::t('app', 'Create Tutorix Subscriptions'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php  } ?>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>
        </div>
    </div>
    <div class="card">
       <div class="card-body">
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
        ['attribute' => 'id', 'visible' => false],
   
   
        [
            'attribute' => 'student_id',
            'label' => Yii::t('app', 'Student'),
            'value' => function($model){                   
                return $model->student->student_name;                   
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()->asArray()->all(), 'id', 'student_name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-tutorix-subscription-items-search-student_id']
        ],
   
        [
            'attribute' => 'parent_id',
            'label' => Yii::t('app', 'Parent'),
            'value' => function($model){                   
                return $model->parent->name_of_the_father;                   
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ParentDetails::find()->asArray()->all(), 'id', 'name_of_the_father'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Parent details', 'id' => 'grid-tutorix-subscription-items-search-parent_id']
        ],
   
   
   
   
   
   
   
   
   
        'coupon_code',
   
        'coupon_discount',
   
        'total_amount',
   'created_on',

   
        [
            'attribute' => 'payment_status',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getPaymentStateOptionsBadges();
            },
            'filter' => \yii\helpers\ArrayHelper::map(
                \app\modules\admin\models\TutorixSubscriptionItems::find()->select('payment_status')->distinct()->asArray()->all(),
                'payment_status',
                function ($model) {
                    return $model['payment_status'] == \app\modules\admin\models\TutorixSubscriptionItems::PAYMENT_PENDING
                        ? 'Pending'
                        : ($model['payment_status'] == \app\modules\admin\models\TutorixSubscriptionItems::PAYMENT_PAID ? 'Completed' : 'Failed');
                }
            ),
        ],
   
   
   
        
        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{invoice} ',
             'buttons' => [
            'invoice' => function ($url, $model) {
    if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
        // Add a data attribute with the download URL
        return Html::a(
            '<span class="fas fa-money-bill" aria-hidden="true"></span>',
            '#',
            [
                'class' => 'download-invoice',
                'data-id' => $model->id,  // Pass the model ID
                // 'data-url' => "https://test-api.estudent.tech/api/v1/tutorix/download-invoice/{$model->id}",
                'data-url' => "https://test-api.estudent.tech/api/v1/tutorix/download-invoice/{$model->id}",
                'title' => 'Download Invoice',
            ]
        );
    }
},
            'update'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a( '<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);

                } 
                },
            'delete'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                    return Html::a( '<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url,[
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-tutorix-subscriptions']],
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
            ]) ,
        ],
    ]); ?>
</div>
</div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).on('click', '.download-invoice', function(e) {
    e.preventDefault(); // Prevent default link behavior
    
    var url = $(this).data('url'); // Get the API URL from the button

    // Show the SweetAlert loader
    swal({
        title: "Generating Invoice",
        text: "Please wait...",
        buttons: false, // Hide buttons
        closeOnClickOutside: false,
        closeOnEsc: false,
        icon: "info",
        content: {
            element: "div",
            attributes: {
                innerHTML: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            }
        }
    });

    // Make the AJAX call to fetch the invoice
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            // Close the SweetAlert loader
            swal.close();

            if (response.status === "OK" && response.invoiceUrl) {
                // Trigger file download
                window.open(response.invoiceUrl, '_blank');
            } else {
                // Show error if response is not OK
                swal("Error", "Failed to generate the invoice. Please try again later.", "error");
            }
        },
        error: function() {
            // Handle errors
            swal("Error", "An error occurred while generating the invoice.", "error");
        }
    });
});


</script>