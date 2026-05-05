<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hostelmanagement\models\search\HostelsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

$this->title = Yii::t('app', 'Hostels');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="hostels-index">
<div class="card">
       <div class="card-body">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUB_ADMIN){ ?>
        <?= Html::a(Yii::t('app', 'Create Hostels'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'campus_id',
                'label' => Yii::t('app', 'Campus'),
                'value' => function($model){                   
                    return $model->campus->id;                   
                },

                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Campus::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-hostels-search-campus_id']
            ],
   
        'user_id',
   
        'image_file',
   
        'name',
   
        'email:email',
   
        'name_of_the_hostel',
   
        'area',
   
        'pincode',
   
        'address',
   
        'country',
   
        'state',
   
        'city_id',
   
        'lat',
   
        'lng',
   
        'coordinates',
   
        [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){                   
                    return $model->getStateOptionsBadges();                   
                },
                 'filterType' => GridView::FILTER_SELECT2,
                'filter' =>\app\modules\hostelmanagement\models\Hostels::getStateOptions(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'status']
               
            ],
        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view} {update} {delete}',
             'buttons' => [
            'view'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUB_ADMIN ) {
                    return Html::a( '<button type="button" class="btn btn-inverse-primary btn-sm btn-rounded btn-icon"><i class="ti-eye"></i></button>', $url);
                } 

                
                },
            'update'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUB_ADMIN ) {
                    return Html::a( '<button type="button" class="btn btn-inverse-primary btn-sm btn-rounded btn-icon"><i class="ti-pencil"></i></button>', $url);

                } 
                },
            'delete'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUB_ADMIN) {
                    return Html::a( '<button type="button" class="btn btn-inverse-primary btn-sm btn-rounded btn-icon"><i class="ti-trash"></i></button>', $url,[
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
        'bordered' => false,
        'class' => 'table table-striped mb-0',
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-hostels']],
        'panel' => [
            'type' => 'light',
            'heading' => '<span class=""></span>  ' . Html::encode($this->title),
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

