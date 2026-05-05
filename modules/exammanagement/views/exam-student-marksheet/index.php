<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\exammanagement\models\search\ExamStudentMarksheetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\ExamsResult;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = 'Exam Student Marksheets';
$this->params['breadcrumbs'][] = $this->title;
// $search = "$('.search-button').click(function(){
// 	$('.search-form').toggle(1000);
// 	return false;
// });";
// $this->registerJs($search);


?>
<div class="exam-student-marksheet-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <!-- <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) { ?>
                    <?= Html::a('Create Final MarkSheet', ['/exam-management/final-marksheet'], ['class' => 'btn btn-success']) ?>
                   
                <?php  } ?>
            </p> -->
            <div class="search-form">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
    <?php
    $get = Yii::$app->request->get();
    ?>
    <button class="btn btn-success btn-sm" onclick="updateGrade( <?= $get['ExamStudentMarksheetSearch']['section_id'] ?? null ?>, <?= $exam_id = $get['ExamStudentMarksheetSearch']['exam_id'] ?? null ?>)">Update All Grade & CGPA</button>

    <div class="card">
        <div class="card-body">
            <?php

            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id', 'visible' => false],

                [
                    'attribute' => 'student_id',
                    'label' => 'Student',
                    'value' => function ($model) {
                        return isset($model->student->student_name) ? $model->student->student_name : "NA";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()->where(['campus_id' => (new User)->getCampusId()])->asArray()->all(), 'id', 'student_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-exam-student-marksheet-search-student_id']
                ],

                [
                    'attribute' => 'session_id',
                    'label' => 'Session',
                    'value' => function ($model) {
                        return $model->session->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Academic years', 'id' => 'grid-exam-student-marksheet-search-session_id']
                ],

                [
                    'attribute' => 'class_id',
                    'label' => 'Class',
                    'value' => function ($model) {
                        return $model->class->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-exam-student-marksheet-search-class_id']
                ],

                [
                    'attribute' => 'section_id',
                    'label' => 'Section',
                    'value' => function ($model) {
                        return $model->section->section_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'section_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-exam-student-marksheet-search-section_id']
                ],

                [
                    'attribute' => 'exam_id',
                    'label' => 'Exam',
                    'value' => function ($model) {
                        return $model->exam->name_of_exam ?? "";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Exams::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'name_of_exam'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Exams', 'id' => 'grid-exam-student-marksheet-search-exam_id']
                ],

                'total_marks',

                'total_obtained_marks',



                [
                    'attribute' => 'total_percentage',
                    'label' => 'Total Percentage',
                    'value' => function ($model) {
                        return round($model->total_percentage ?? "", 2);
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Exams::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'name_of_exam'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Exams', 'id' => 'grid-exam-student-marksheet-search-exam_id']
                ],




                [
                    'class' => 'kartik\grid\EditableColumn',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'attribute' => 'total_grade',
                    'value' => function ($model) {
                        if (empty($model->total_grade)) {
                            return "Not Set";
                        } else {
                            return $model->total_grade;
                        }
                    },
                    'readonly' => false,
                    'editableOptions' => function ($model, $key, $index) {
                        return [
                            'name' => 'note', // this will be sent to controller to process
                            'header' => 'grade',
                            'asPopover' => false,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'data' => $model->total_grade,
                            'beforeInput' => '<h6 style="font-weight: bold">Overall Grade</h6>',
                            'value' => function ($model) {
                                if (empty($model->total_grade)) {
                                    return "Not Set";
                                } else {
                                    return $model->total_grade;
                                }
                            },
                        ];
                    },
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'attribute' => 'total_cgpa',
                    'value' => function ($model) {
                        if (empty($model->total_cgpa)) {
                            return "Not Set";
                        } else {
                            return $model->total_cgpa;
                        }
                    },
                    'readonly' => false,
                    'editableOptions' => function ($model, $key, $index) {
                        return [
                            'name' => 'note', // this will be sent to controller to process
                            'header' => 'cgpa',
                            'asPopover' => false,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'data' => $model->total_cgpa,
                            'beforeInput' => '<h6 style="font-weight: bold">Overall CGPA</h6>',
                            'value' => function ($model) {
                                if (empty($model->total_cgpa)) {
                                    return "Not Set";
                                } else {
                                    return $model->total_cgpa;
                                }
                            },
                        ];
                    },
                ],


                [
                    'attribute' => 'marksheet_url',
                    'format' => 'raw',
                    'value' => function ($model) {
                        // if ((new User())->getCampusId() == 71 || (new User())->getCampusId() == 57 || (new User())->getCampusId() == 81 || (new User())->getCampusId() == 67) {
                        return "<a target='_blank' href='" . Url::toRoute(['silver-crest-marksheet', 'id' => $model->id]) . "'  ><div class='btn btn-success'>Generate Marksheet</div></a>";
                        // } else {
                        //     if (empty($model->marksheet_url)) {

                        //         return "<a target='_blank' href='" . Url::toRoute(['generate-exam-wise-marksheet', 'id' => $model->id]) . "'  ><div class='btn btn-success'>Generate Marksheet</div></a>";
                        //     } else {
                        //         return "<a target='_blank' href='" . Url::toRoute(['generate-exam-wise-marksheet', 'id' => $model->id]) . "'  ><div class='btn btn-success'>Re-Generate Marksheet</div></a>";
                        //     }
                        // }
                    },


                ],

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
                'pjax' => false,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-exam-student-marksheet']],
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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



    function updateGrade(sectionId, examid) {

        const data = {
            sectionId: sectionId,
            examid: examid
        };

        console.log(data);

        // Show loader
        Swal.fire({
            title: 'Updating...',
            html: 'Please wait while we update the grades.',
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: "post",
            url: "update-all-grade",
            data: data,
            dataType: "json",
            success: function(response) {
                // Hide loader and show success message

                console.log()
                if (response.status == "OK") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Grades updated successfully.',
                        showConfirmButton: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                        showConfirmButton: true
                    });
                }

            },
            error: function(xhr, status, error) {
                // Hide loader and show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                    showConfirmButton: true
                });
            }
        });
    }
</script>