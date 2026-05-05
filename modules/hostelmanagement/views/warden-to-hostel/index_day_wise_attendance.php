<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hostelmanagement\models\search\WardenToHostelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

$this->title = Yii::t('app', 'Warden To Hostels');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 

?>
<div class="warden-to-hostel-index">
<div class="card">
       <div class="card-body">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_CHEF_WARDEN){ ?>
        <?= Html::a(Yii::t('app', 'Assign Warden To Hostel'), ['create'], ['class' => 'btn btn-success']) ?>
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
   
        ['attribute' => 'campus_id', 'visible' => false],
   
        [
                'attribute' => 'hostel_id',
                'label' => Yii::t('app', 'Hostel'),
                'value' => function($model){
                    if ($model->hostel)
                    {return $model->hostel->name;}
                    else
                    {return NULL;}
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Hostels::find()->where([
                    'or',
                    ['campus_id' => User::getCampusId()],
                    ['campus_id' => User::getUserCampusId()],
                ])->asArray()->all(), 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Hostels', 'id' => 'grid-warden-to-hostel-search-hostel_id']
            ],
   
        [
                'attribute' => 'warden_id',
                'label' => Yii::t('app', 'Warden'),
                'value' => function($model){
                    if ($model->warden)
                    {return $model->warden->username;}
                    else
                    {return NULL;}
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(User::find()->where([
                    'or',
                    ['campus_id' => User::getCampusId()],
                    ['campus_id' => User::getUserCampusId()],
                ])
                ->andWhere(['in', 'user_role', [User::ROLE_CHEF_WARDEN, User::ROLE_WARDEN]])
                ->asArray()
                ->all(),
            'id',
            'username'
        ),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-warden-to-hostel-search-warden_id']
            ],
   
        [
                'attribute' => 'floor_id',
                'label' => Yii::t('app', 'Floor'),
                'value' => function($model){
                    if ($model->floor)
                    {return $model->floor->name_of_floor;}
                    else
                    {return NULL;}
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Floor::find()->where([
                    'or',
                    ['campus_id' => User::getCampusId()],
                    ['campus_id' => User::getUserCampusId()],
                ])->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Floor', 'id' => 'grid-warden-to-hostel-search-floor_id']
            ],
   
        [
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
            'view'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER || \Yii::$app->user->identity->user_role==User::ROLE_CHEF_WARDEN) {
                    return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                } 
                },
            'update'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER || \Yii::$app->user->identity->user_role==User::ROLE_CHEF_WARDEN) {
                    return Html::a( '<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);

                } 
                },
            'delete'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_CHEF_WARDEN) {
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-warden-to-hostel']],
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
	 
      url: "/Estudent_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>