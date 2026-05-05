<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\ExamsResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\base\ClassSections;
use app\modules\admin\models\Exams;
use app\modules\admin\models\ExamsResult;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', isset($nameOfExam->name_of_exam) ? ($nameOfExam->name_of_exam) : "") . ' Result';
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>




<div class="exams-result-index">
    <div class="card">
        <div class="card-body">



            <div class="search-form">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>

    <h4><b>
            AcademicYears
            <?=
            (isset($academicYearTitle) && is_object($academicYearTitle) ? $academicYearTitle->title : "") . ' ' .
                (isset($className) && is_object($className) ? $className->title : "") . ' ' .
                (isset($sectionName) && is_object($sectionName) ? $sectionName->section_name : "")
            ?>
        </b></h4>



    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],




                // [
                //     'attribute' => 'exam_id',
                //     'label' => Yii::t('app', 'Exams'),
                //     'value' => function ($model) {
                //         return $model->exam->name_of_exam;
                //     },
                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Exams::find()
                //         ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                //         ->andWhere(['status' => ClassSections::STATUS_ACTIVE])
                //         ->asArray()->all(), 'id', 'name_of_exam'),
                //     'filterWidgetOptions' => [
                //         'pluginOptions' => ['allowClear' => true],
                //     ],
                //     'filterInputOptions' => ['placeholder' => 'Exams', 'id' => 'grid-exams-result-search-exam_id']
                // ],

                // [
                //     'attribute' => 'academic_year_id',
                //     'label' => Yii::t('app', 'Academic Year'),
                //     'value' => function ($model) {
                //         return $model->academicYear->title;
                //     },
                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
                //         ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                //         ->andWhere(['status' => ClassSections::STATUS_ACTIVE])
                //         ->asArray()->all(), 'id', 'title'),
                //     'filterWidgetOptions' => [
                //         'pluginOptions' => ['allowClear' => true],
                //     ],
                //     'filterInputOptions' => ['placeholder' => 'Academic years', 'id' => 'grid-exams-result-search-academic_year_id']
                // ],

                [
                    'attribute' => 'student_id',
                    'label' => Yii::t('app', 'Student'),
                    'value' => function ($model) {
                        return $model->student->student_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['status' => ClassSections::STATUS_ACTIVE])
                        ->asArray()->all(), 'id', 'student_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-exams-result-search-student_id']
                ],

                [
                    'attribute' => 'subject_id',
                    'label' => Yii::t('app', 'Subject'),
                    'value' => function ($model) {
                        return $model->subject->subject_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Subjects::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['status' => ClassSections::STATUS_ACTIVE])
                        ->asArray()->all(), 'id', 'subject_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Exams', 'id' => 'grid-exams-result-search-subject_id']
                ],
                [
                    'attribute' => 'total_marks',
                    'label' => Yii::t('app', 'Total Marks'),
                    'value' => function ($model) {
                        return $model->total_marks;
                    },

                ],

                [
                    'class' => 'kartik\grid\EditableColumn',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'attribute' => 'marks_scored',
                    'value' => function ($model) {
                        return strip_tags($model->marks_scored);
                    },
                    'readonly' => false,
                    'editableOptions' => function ($model, $key, $index) {
                        return [
                            'name' => 'note', // this will be sent to controller to process
                            'header' => 'marks_scored',
                            'asPopover' => false,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'data' => $model->marks_scored,
                            'beforeInput' => '<h6 style="font-weight: bold">Marks Scored</h6>',
                            'value' => function ($model) {
                                return strip_tags($model->marks_scored,);
                            }, // in this case, $model is an array. For others, $model->employer_score
                        ];
                    },
                ],
                [
                    'attribute' => 'pecentage',
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Percentage'),
                    'value' => function ($model) {
                        $percentage = "<div  class='text-center mb-1'><i id='loading" . $model->exams_result_id . "' class='fa fa-spinner fa-spin' style='display:none'></i><span id='percentage" . $model->exams_result_id . "'>" . $model->pecentage . "</span>%</div> ";
                        $calculateButton = "<button class='btn btn-sm btn-success' onclick='calculatePercentage(" . $model->exams_result_id . ")'>View Percentage</button>";
                        return $percentage . "<br>" . $calculateButton;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => (new ExamsResult())->getMarksTypeOptions(),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'marks type', 'id' => 'grid-state-search-marks_type']
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'attribute' => 'marks_type',
                    'value' => function ($model) {
                        if (empty($model->marks_type)) {
                            return "Not Set";
                        } else {
                            return strip_tags($model->getMarksTypeBadges());
                        }
                    },
                    'readonly' => false,
                    'editableOptions' => function ($model, $key, $index) {
                        return [
                            'name' => 'note', // this will be sent to controller to process
                            'header' => 'marks_type',
                            'asPopover' => false,
                            'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                            'data' => (new ExamsResult())->getMarksTypeOptions(),
                            'beforeInput' => '<h6 style="font-weight: bold">Marks Type</h6>',
                            'value' => function ($model) {
                                if (empty($model->marks_type)) {
                                    return "Not Set";
                                } else {
                                    return strip_tags($model->getMarksTypeBadges());
                                }
                            }, // in this case, $model is an array. For others, $model->employer_score
                        ];
                    },
                ],

                [
                    'class' => 'kartik\grid\EditableColumn',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'attribute' => 'grade',
                    'value' => function ($model) {
                        if (empty($model->grade)) {
                            return "Not Set";
                        } else {
                            return $model->grade;
                        }
                    },
                    'readonly' => false,
                    'editableOptions' => function ($model, $key, $index) {
                        return [
                            'name' => 'note', // this will be sent to controller to process
                            'header' => 'grade',
                            'asPopover' => false,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'data' => $model->grade,
                            'beforeInput' => '<h6 style="font-weight: bold">Grade</h6>',
                            'value' => function ($model) {
                                if (empty($model->grade)) {
                                    return "Not Set";
                                } else {
                                    return $model->grade;
                                }
                            },
                        ];
                    },
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'attribute' => 'cgpa',
                    'value' => function ($model) {
                        if (empty($model->cgpa)) {
                            return "Not Set";
                        } else {
                            return $model->cgpa;
                        }
                    },
                    'readonly' => false,
                    'editableOptions' => function ($model, $key, $index) {
                        return [
                            'name' => 'note', // this will be sent to controller to process
                            'header' => 'cgpa',
                            'asPopover' => false,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'data' => $model->cgpa,
                            'beforeInput' => '<h6 style="font-weight: bold">CGPA</h6>',
                            'value' => function ($model) {
                                if (empty($model->cgpa)) {
                                    return "Not Set";
                                } else {
                                    return $model->cgpa;
                                }
                            },
                        ];
                    },
                ],








                [
                    'attribute' => 'class_id',
                    'label' => Yii::t('app', 'Class'),
                    'value' => function ($model) {
                        return $model->class->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['status' => ClassSections::STATUS_ACTIVE])
                        ->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-exams-result-search-class_id']
                ],

                [
                    'attribute' => 'section_id',
                    'label' => Yii::t('app', 'Section'),
                    'value' => function ($model) {
                        return $model->section->section_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['status' => ClassSections::STATUS_ACTIVE])
                        ->asArray()->all(), 'id', 'section_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-exams-result-search-section_id']
                ],
                // [
                //     'label' => 'Marks-Sheet PDF',
                //     'format' => 'raw',
                //     'value' => function ($model) {
                //         return '<a href="' . $model->marks_sheet . '" target="_blank">
                //     <i class="far fa-file-pdf fa-2x"></i></a>';
                //     },
                // ],




                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{delete}',
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
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-exams-result']],
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

            url: "/Eschools_backend/gii/default/status-change",


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


<script>
    function calculatePercentage(id) {
        $('#loading' + id).show(); // Show loading icon
        $.ajax({
            type: "GET",
            url: "<?= Url::toRoute(['get-percentage']) ?>",
            data: {
                id: id,
                _cache: Date.now()
            }, // Add a random parameter to the URL
            dataType: "json",
            success: function(response) {
                $('#loading' + id).show();
                if (response.status == "OK") {
                    console.log(response);
                    $('#percentage' + id).html(response.Detail);
                } else {
                    // Handle error
                }
                $('#loading' + id).hide(); // Hide loading icon
            },
            error: function(xhr, status, error) {
                // Handle error
                $('#loading' + id).hide(); // Hide loading icon
            }
        });
    }
</script>