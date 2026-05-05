<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\exammanagement\models\search\ExamSchedulesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\Exams;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Exam Schedules');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="exam-schedules-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) { ?>
                    <?= Html::a(Yii::t('app', 'Create Exam Schedules'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
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
                    'attribute' => 'session_id',
                    'label' => Yii::t('app', 'Session'),
                    'value' => function ($model) {
                        return $model->session->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Academic years', 'id' => 'grid-exam-schedules-search-session_id']
                ],

                [
                    'attribute' => 'campus_id',
                    'label' => Yii::t('app', 'Campus'),
                    'value' => function ($model) {
                        return $model->campus->name_of_the_educational_Institution;
                    },
                ],

                [
                    'attribute' => 'exam_id',
                    'label' => Yii::t('app', 'Exam'),
                    'value' => function ($model) {
                        return $model->exam->name_of_exam ?? "";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Exams::find()->andWhere(['campus_id' => (new User())->getCampusId()])->andWhere(['status' => Exams::STATUS_ACTIVE])->asArray()->all(), 'id', 'name_of_exam'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Exams', 'id' => 'grid-exam-schedules-search-exam_id']
                ],

                [
                    'attribute' => 'class_id',
                    'label' => Yii::t('app', 'Class'),
                    'value' => function ($model) {
                        return $model->class->title ?? "";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-exam-schedules-search-class_id']
                ],

                [
                    'attribute' => 'section_id',
                    'label' => Yii::t('app', 'Section'),
                    'value' => function ($model) {
                        return $model->section->section_name ?? "";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'section_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-exam-schedules-search-section_id']
                ],

                [
                    'attribute' => 'subject_id',
                    'label' => Yii::t('app', 'Subject'),
                    'value' => function ($model) {
                        return $model->subject->subject_name ?? "";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Subjects::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'subject_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Subjects', 'id' => 'grid-exam-schedules-search-subject_id']
                ],


                'exam_date',
                'start_time',
                'end_time',

                ['attribute' => 'exam_duration', 'filter' => false,],

                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->getStateOptionsBadges();
                    },
                    'filter' => false,


                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                                return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                            }
                        },
                        'update' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                                return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
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
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-exam-schedules']],
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
    </div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();

        $.ajax({
            type: "POST",

            url: "/Estudent_backend/gii/default/status-change",


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