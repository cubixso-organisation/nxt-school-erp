<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\FeesTypsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\FeesTyps;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Fees Types');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search); ?>

<?php
$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],

    ['attribute' => 'id', 'visible' => false],



    'fees_type_name',

    'year_from',

    'year_to',
    'months',

    [


        'attribute' => 'status',
        "format" => 'raw',
        'label' => Yii::t('app', 'Status'),
        'filter'  => (new FeesTyps())->getStateOptions(),
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'status', 'id' => 'grid-state-search-status'],


        'attribute' => 'status',
        'format' => 'raw',
        'value' => function ($model) {
            return $model->getStateOptionsBadges();
        },


    ],


    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view}{update}{delete}',
        'buttons' => [
            'view' => function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                }
            },
            'update' => function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url, [
                        'data-pjax' => 0

                    ]);
                }
            },
            'delete' => function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN  || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-fees-typs']],
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
