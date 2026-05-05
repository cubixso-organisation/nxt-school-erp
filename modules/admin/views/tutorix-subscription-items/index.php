<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\TutorixSubscriptionItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

$this->title = Yii::t('app', 'Tutorix Subscription Items');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 

?>
<div class="tutorix-subscription-items-index">
<div class="card">
       <div class="card-body">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <!-- <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_CAMPUS_ADMIN){ ?>
        <?= Html::a(Yii::t('app', 'Create Tutorix Subscription Items'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php  } ?>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>
        </div>
    </div> -->
    <div class="card">
       <div class="card-body">
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
        ['attribute' => 'id', 'visible' => false],
   
        'subscription_id',
   
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
            'attribute' => 'class_id',
            'label' => Yii::t('app', 'Parent'),
            'value' => function($model){                   
                return $model->class->name;                   
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TutorixClass::find()->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'tutorix class', 'id' => 'grid-tutorix-subscription-items-search-class_id']
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
   
       
   
            [
                'attribute' => 'item_price',
                'footer' => 'Total: ' . ($totalItemPriceForCurrentPage ?? 0), // Correctly use totalItemPrice passed from the controller
                'footerOptions' => ['style' => 'font-weight:bold;'],
            ],
            
      
        'start_date',
   
        'expiry_date',
        [
            'attribute' => 'campus_name',
            'label' => 'Campus Name',
            'value' => 'campus_name', // Use the alias defined in the query
            'filter' => Html::activeTextInput($searchModel, 'campus_name', ['class' => 'form-control']),
        ],
        [
            'attribute' => 'is_free_trail',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getTrailStateOptionsBadges();
            },
            'filter' => \yii\helpers\ArrayHelper::map(
                \app\modules\admin\models\TutorixSubscriptionItems::find()->select('is_free_trail')->distinct()->asArray()->all(),
                'is_free_trail',
                function ($model) {
                    return $model['is_free_trail'] == \app\modules\admin\models\TutorixSubscriptionItems::IS_FREE_TRAIL
                        ? 'Free Trial'
                        : 'Activation';
                }
            ),
        ],
        
        
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
        
        
        
   
        // 'tutorix_user_access_token:ntext',
   
        // 'unique_id',
   
        'year_id',
   
        [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){                   
                    return $model->getStateOptionsBadges();                   
                },
               
               
            ],
           
            
        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view}',
             'buttons' => [
            'view'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-tutorix-subscription-items']],
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
        'showFooter' => true, // Add this line to enable footer display
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
	 
      url: "/estudent_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>