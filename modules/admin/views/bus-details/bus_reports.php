<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\BusDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\User;
use app\modules\admin\models\BusDetails;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Bus Details');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?> 
<div class="bus-details-index">





    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],


        [
            'attribute' => 'title',
            'label' => Yii::t('app', 'Bus Name'),
            'value' => function ($model) {
                return $model->title;
            },


        ],


        'vehicle_number',
        'route_no',
        'start_point',
        'end_point',



        [
            'attribute' => 'status_direction',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getStateDirectionOptionsBadges();
            },


        ],



        [


            'attribute' => 'status',
            "format" => 'raw',
            'label' => Yii::t('app', 'Status'),
            'filter'  =>  (new BusDetails())->getStateOptions(),
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
            'attribute' => 'current_status',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getCurrentStateOptionsBadges();
            },


        ],




        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view}',
             'buttons' => [
            'view'=> function ($url, $model) {
                $url = 'view-bus-reports?id='.$model->id;
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
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
    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-bus-details']],
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
