<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\CampusTimingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\ClassSections;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Campus Timings');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="campus-timing-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <p>
        <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) { ?>
            <?= Html::a(Yii::t('app', 'Create Campus Timing'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php  } ?>
    </p>
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
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
                'attribute' => 'section_id',
                'label' => Yii::t('app', 'Section'),
                'value' => function ($model) {
                    // Assuming you have the relation set up correctly in your model
                    return $model->section->studentClass->title . ' - ' . $model->section->section_name; // Adjust as necessary
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(
                    \app\modules\admin\models\ClassSections::find()

                        ->joinWith(['studentClass as sc'])
                        ->where(['sc.campus_id' => (new User())->getCampusId()])
                        ->andWhere(['class_sections.status' => ClassSections::STATUS_ACTIVE]) // Assuming the relation is named 'studentClass'
                        ->asArray()
                        ->all(),
                    'id',
                    function ($model) {
                        return $model['studentClass']['title'] . ' - ' . $model['section_name']; // Adjust according to your structure
                    }
                ),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-grade-search-section_id']
            ],

            'start_time',

            'end_time',

            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getStateOptionsBadges();
                },


            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                            return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                        }
                    },
                    'update' => function ($url, $model) {
                        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                            return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
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
            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-campus-timing']],
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

    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).on('change', 'select[id^=status_list_]', function() {
            var id = $(this).attr('data-id');
            var val = $(this).val();

            $.ajax({
                type: "POST",

                url: "/estudent_backend/gii/default/status-change",


                data: {
                    id: id,
                    val: val
                },
                success: function(data) {
                    swal("Good job!", "Status Successfully Changed!", "success");
                }
            });
        });
    </script>