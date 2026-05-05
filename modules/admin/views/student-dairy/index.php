<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\StudentDairySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

$this->title = Yii::t('app', 'Student Diaries');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="student-dairy-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

        
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id', 'visible' => false],

                // [
                //         'attribute' => 'campus_id',
                //         'label' => Yii::t('app', 'Campus'),
                //         'value' => function($model){                   
                //             return $model->campus->id;                   
                //         },
                //         'filterType' => GridView::FILTER_SELECT2,
                //         'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->asArray()->all(), 'id', 'id'),
                //         'filterWidgetOptions' => [
                //             'pluginOptions' => ['allowClear' => true],
                //         ],
                //         'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-student-dairy-search-campus_id']
                //     ],

                [
                    'attribute' => 'teacher_details_id',
                    'label' => Yii::t('app', 'Teacher Details'),
                    'value' => function ($model) {
                        return $model->teacherDetails->name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()->where(['campus_id' => (new User())->getCampusId()])->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Teacher details', 'id' => 'grid-student-dairy-search-teacher_details_id']
                ],

                // [
                //         'attribute' => 'subject_timetable_id',
                //         'label' => Yii::t('app', 'Subject Timetable'),
                //         'value' => function($model){                   
                //             return $model->subjectTimetable->id;                   
                //         },
                //         'filterType' => GridView::FILTER_SELECT2,
                //         'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectTimetable::find()->asArray()->all(), 'id', 'id'),
                //         'filterWidgetOptions' => [
                //             'pluginOptions' => ['allowClear' => true],
                //         ],
                //         'filterInputOptions' => ['placeholder' => 'Subject timetable', 'id' => 'grid-student-dairy-search-subject_timetable_id']
                //     ],

                [
                    'attribute' => 'academic_year_id',
                    'label' => Yii::t('app', 'Academic Year'),
                    'value' => function ($model) {
                        return $model->academicYear->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->where(['campus_id' => (new User())->getCampusId()])->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Academic years', 'id' => 'grid-student-dairy-search-academic_year_id']
                ],

                [
                    'attribute' => 'class_id',
                    'label' => Yii::t('app', 'Class'),
                    'value' => function ($model) {
                        return $model->class->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->where(['campus_id' => (new User())->getCampusId()])->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-dairy-search-class_id']
                ],

                [
                    'attribute' => 'section_id',
                    'label' => Yii::t('app', 'Section'),
                    'value' => function ($model) {
                        return $model->section->section_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()->where(['campus_id' => (new User())->getCampusId()])->asArray()->all(), 'id', 'section_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-student-dairy-search-section_id']
                ],

                [
                    'attribute' => 'subject_id',
                    'label' => Yii::t('app', 'Subject'),
                    'value' => function ($model) {
                        return $model->subject->subject_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Subjects::find()->where(['campus_id' => (new User())->getCampusId()])->asArray()->all(), 'id', 'subject_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Subjects', 'id' => 'grid-student-dairy-search-subject_id']
                ],

                'dairy:ntext',

                'remarks:ntext',

                // 'submission_date',
                [
                    'attribute' => 'submission_date',
                    'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    'filter' => \yii\jui\DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'submission_date',
                        'options' => ['class' => 'form-control'],
                        'dateFormat' => 'yyyy-MM-dd',
                    ]),
                ],

                // 'document',

                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->getStateOptionsBadges();
                    },


                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view} {update}',
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
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role==User::role_campus_sub_admin) {
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
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-dairy']],
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

            url: "/school_management_backend/gii/default/status-change",


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