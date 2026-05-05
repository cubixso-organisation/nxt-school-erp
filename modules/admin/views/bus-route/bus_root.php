<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\BusRouteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\Campus;
use kartik\grid\GridView;
use yii\helpers\Url;

    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
        ['attribute' => 'id', 'visible' => false],
   
  


   
        [
                'attribute' => 'bus_id',
                'label' => Yii::t('app', 'Bus'),
                'value' => function($model){                   
                    return $model->bus->title;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusDetails::find()
                ->where(['campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)])
                ->asArray()->all(), 'id', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Bus details', 'id' => 'grid-bus-route-search-bus_id']
            ],
   
        'point_name',
   

        'short_order',
   
   
        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view} {update} ',
             'buttons' => [
            'view'=> function($url,$model) {
                $url = Url::toRoute(['/admin/bus-route/view','id'=>$model->id]);

            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                } 
                },
            'update'=> function($url,$model) {
                $url = Url::toRoute(['/admin/bus-route/update','id'=>$model->id]);
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a( '<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url,[
                        'data-pjax' => 0
                    ]);

                } 
                },
            'delete'=> function($url,$model) {
                $url = Url::toRoute(['/admin/bus-route/delete','id'=>$model->id]);

            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-bus-route']],
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



