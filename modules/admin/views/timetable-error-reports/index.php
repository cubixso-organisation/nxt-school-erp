<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\TimetableErrorReportsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

$this->title = Yii::t('app', 'Timetable Error Reports');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 

?>
<div class="timetable-error-reports-index">
<div class="card">


 

    </div>
    <div class="card">
       <div class="card-body">
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
        ['attribute' => 'id', 'visible' => false],
   
        [
                'attribute' => 'subject_timetable_id',
                'label' => Yii::t('app', 'Subject Timetable'),
                'value' => function($model){                   
                    return $model->subjectTimetable->id;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectTimetable::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Subject timetable', 'id' => 'grid-timetable-error-reports-search-subject_timetable_id']
            ],
   
        'class',
   
        'room',
   
        'section',
   
        'subject',
   
        'teacher',
   
        'time_from',
   
        'time_to',
   
        'error_type',
   
   
        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view} {delete}',
             'buttons' => [
            'view'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-timetable-error-reports']],
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
	 
      url: "/school_management_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>