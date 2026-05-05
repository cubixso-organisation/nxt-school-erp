<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\NoticeBoardsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\NoticeBoards;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Teachers Notice Boards');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 
 
?>
<div class="notice-boards-index">
<div class="card">
       <div class="card-body">
    

    <p>
    <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_CAMPUS_ADMIN){ ?>
        <?= Html::a(Yii::t('app', 'Create Teacher Notice'), ['create-teacher'], ['class' => 'btn btn-success']) ?>
        <?php  } ?>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button d-none']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?=  $this->render('_search_teacher_notice', ['model' => $searchModel]); ?>
    </div>
        </div>
    </div>
    <div class="card">
       <div class="card-body">
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
        ['attribute' => 'id', 'visible' => false],
   
        'title',
   
       

        [
            'attribute' => 'description',
            'format'=>'raw',
            'label' => Yii::t('app', 'description'),
            'value' => function($model){                   
                return $model->description;                   
            },
       
        ],
        [
            'attribute' => 'notice_image',
            'format' => 'raw',
            'label' => Yii::t('app', 'Notice Doc'),
            'value' => function ($model) {
                // Check if notice_image is not empty
                if (!empty($model->notice_image)) {
                    // Return HTML anchor tag with the image as the href
                    return '<a href="' . $model->notice_image . '" target="_blank"><img src="' . $model->notice_image . '" style="max-width: 100px; max-height: 100px;" /></a>';
                } else {
                    return ''; // Return empty string if no image is available
                }
            },
        ],
        
    

        [
            'attribute' => 'teacher_id',
            'format'=>'raw',
            'label' => Yii::t('app', 'Name'),
            'value' => function($model){                   
                return $model->teacher->name??"";                   
            },
       
        ],

   
   
        // [
        //         'attribute' => 'section_id',
        //         'label' => Yii::t('app', 'Section'),
        //         'value' => function($model){                   
        //             $section_name_class =  $model->section->studentClass->title.'-'.$model->section->section_name;  
                    
        //          return $section_name_class;                 

        //         },
        //         'filterType' => GridView::FILTER_SELECT2,
        //         'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'section_name'),
        //         'filterWidgetOptions' => [
        //             'pluginOptions' => ['allowClear' => true],
        //         ],
        //         'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-notice-boards-search-section_id']
        //     ],
   
        'expiry_date',
   
        [


            'attribute' => 'status',
            "format" => 'raw',
            'label' => Yii::t('app', 'Status'),
            'filter'  =>  (new NoticeBoards())->getStateOptions(),
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
             'template' => '{delete}',
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-notice-boards']],
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