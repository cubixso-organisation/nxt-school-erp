<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\TeacherAttenddenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

$this->title = Yii::t('app', 'Teacher Attenddences');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 

?>
<div class="teacher-attenddence-index">
    <div class="card">
       <div class="card-body">
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
        ['attribute' => 'id', 'visible' => false],
   
        // [
        //         'attribute' => 'teacher_details_id',
        //         'label' => Yii::t('app', 'Teacher Details'),
        //         'value' => function($model){                   
        //             return $model->teacherDetails->name;                   
        //         },
        //         'filterType' => GridView::FILTER_SELECT2,
        //         'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()->asArray()->all(), 'id', 'name'),
        //         'filterWidgetOptions' => [
        //             'pluginOptions' => ['allowClear' => true],
        //         ],
        //         'filterInputOptions' => ['placeholder' => 'Teacher details', 'id' => 'grid-teacher-attenddence-search-teacher_details_id']
        //     ],
   
        'teacher_present_date_and_time',
   
        'date',
   
        'lat',
   
        'lng',

        'checkout_date_time',

        'checkout_lat',

        'checkout_lng',
   
        // [
        //         'attribute' => 'status',
        //         'format' => 'raw',
        //         'value' => function($model){                   
        //             return $model->getStateOptionsBadges();                   
        //         },
               
               
        //     ],
        // [
        //     'class' => 'kartik\grid\ActionColumn',
        //      'template' => '{view} {update} {delete}',
        //      'buttons' => [
        //     'view'=> function($url,$model) {
        //     if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
        //             return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
        //         } 
        //         },
        //     'update'=> function($url,$model) {
        //     if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
        //             return Html::a( '<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);

        //         } 
        //         },
        //     'delete'=> function($url,$model) {
        //     if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
        //             return Html::a( '<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url,[
        //                 'data' => [
        //                             'method' => 'post',
        //                              // use it if you want to confirm the action
        //                              'confirm' => 'Are you sure?',
        //                          ],
        //                         ]);
        //         } 
        //         },


        // ]
            
           

        // ],
    ];   
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-teacher-attenddence']],
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
$(document).on('change','select[id^=status_list_]',function(){
var id=$(this).attr('data-id');
var val=$(this).val();

$.ajax({
	  type: "POST",
	 
      url: "/Eschools_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>