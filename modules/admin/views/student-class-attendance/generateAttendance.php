<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\StudentClassAttendanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentClassAttendance;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\SubjectTimetable;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Student Class Attendances');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="student-class-attendance-index">
    <div class="card">
        <div class="card-body">
            <h5>Select the date of which you want to generate the attendance.</h3>

                <?php echo $this->render('_generateAttendance', ['model' => $searchModel]);
                ?>


        </div>
    </div>

    <div class="card">
        <div class="row pt-3 pl-3">
            <div class="col-2">
                <button id="markpresent" class="btn btn-success">Mark Present</button>
            </div>
            <div class="col-3">
                <button id="markabsent" class="btn btn-danger">Mark Absent</button>
            </div>
        </div>
        <div class="card-body">

            <?php
            $gridColumn = [
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return ['value' => $key];
                    },
                ],
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id', 'visible' => false],

                [
                    'attribute' => 'student_id',
                    'label' => Yii::t('app', 'Student'),
                    'value' => function ($model) {

                        // var_dump($model);exit;
                        return isset($model->student->student_name)? $model->student->student_name: '';
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->asArray()->all(), 'id', 'student_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-student-class-attendance-search-student_id']
                ],

                [
                    'attribute' => 'teacher_id',
                    'label' => Yii::t('app', 'Teacher'),
                    'value' => function ($model) {
                        return $model->teacher->name ?? "";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Teacher details', 'id' => 'grid-student-class-attendance-search-teacher_id']
                ],

                [
                    'attribute' => 'subject_timetable_id',
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Subject Timetable'),
                    'value' => function ($model) {


                        $day_id = '<b style="color:rgb(78, 54, 163)">' . $model->subjectTimetable->day_id . '</b>';

                        $time_from = $model->subjectTimetable->time_from;
                        $time_to = $model->subjectTimetable->time_to;


                        $class_id = $model->subjectTimetable->class_id;
                        $section_id = $model->subjectTimetable->section_id;
                        $subject_id = $model->subjectTimetable->subject_id;
                        $subjects = Subjects::find()->where(['id' => $subject_id])->one();
                        $student_class = StudentClass::find()->where(['id' => $class_id])->one();
                        $class_sections = ClassSections::find()->where(['id' => $section_id])->one();

                        $getTimeOfDay = SubjectTimetable::getTimeOfDay($time_from);

                        $time_table_details  = $subjects->subject_name . ' ' . $student_class->title . ' ' . $class_sections->section_name . ' ' . $day_id . ' ' . $getTimeOfDay . ' ' . $time_from . '-' . $time_to;

                        return $time_table_details;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectTimetable::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->asArray()->all(), 'id', 'id'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Subject timetable', 'id' => 'grid-student-class-attendance-search-subject_timetable_id']
                ],

                [
                    'attribute' => 'academic_year_id',
                    'label' => Yii::t('app', 'Academic Year'),
                    'value' => function ($model) {
                        return $model->academicYear->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Academic years', 'id' => 'grid-student-class-attendance-search-academic_year_id']
                ],

                // [
                //     'attribute' => 'subject_group_id',
                //     'label' => Yii::t('app', 'Subject Group'),
                //     'value' => function ($model) {
                //         return $model->subjectGroup->subject_group_name ?? "";
                //     },
                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectGroups::find()
                //         ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                //         ->asArray()->all(), 'id', 'id'),
                //     'filterWidgetOptions' => [
                //         'pluginOptions' => ['allowClear' => true],
                //     ],
                //     'filterInputOptions' => ['placeholder' => 'Subject groups', 'id' => 'grid-student-class-attendance-search-subject_group_id']
                // ],

                [
                    'attribute' => 'subject_id',
                    'label' => Yii::t('app', 'Subject'),
                    'value' => function ($model) {
                        return $model->subject->subject_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Subjects::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->asArray()->all(), 'id', 'subject_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Subjects', 'id' => 'grid-student-class-attendance-search-subject_id']
                ],

                'date',


                [


                    'attribute' => 'status',
                    "format" => 'raw',
                    'label' => Yii::t('app', 'Status'),
                    'filter'  => (new StudentClassAttendance())->getStateOptions(),
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
                    'template' => '{view}',
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
                'pjax' => false,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-class-attendance']],
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
    $(document).ready(function() {
        // Function to handle click on "Mark Present" button
        $('#markpresent').click(function() {
            // Array to store selected row IDs
            var selectedRows = [];

            // Iterate over each checked checkbox and store its value (row ID)
            $('input[name="selection[]"]:checked').each(function() {
                selectedRows.push($(this).val());
            });

            // If no checkboxes are checked, show error and return
            if (selectedRows.length === 0) {
                Swal.fire("Oops!", "No students selected. Please select at least one student.", "error");
                return;
            }

            // Show a loader while the request is processing
            Swal.fire({
                title: 'Updating Attendance...',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Call the function to update attendance status
            changeAttendanceStatus(selectedRows, <?= StudentClassAttendance::STATUS_PRESENT ?>);
        });
    });

    $(document).ready(function() {
        // Function to handle click on "Mark Present" button
        $('#markabsent').click(function() {
            // Array to store selected row IDs
            var selectedRows = [];

            // Iterate over each checked checkbox and store its value (row ID)
            $('input[name="selection[]"]:checked').each(function() {
                selectedRows.push($(this).val());
            });

            // If no checkboxes are checked, show error and return
            if (selectedRows.length === 0) {
                Swal.fire("Oops!", "No students selected. Please select at least one student.", "error");
                return;
            }

            // Show a loader while the request is processing
            Swal.fire({
                title: 'Updating Attendance...',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Call the function to update attendance status
            changeAttendanceStatus(selectedRows, <?= StudentClassAttendance::STATUS_ABSENT ?>);
        });
    });

    // Function to make an AJAX request to change attendance status
    function changeAttendanceStatus(data, status) {
        $.ajax({
            type: "POST",
            url: "<?= Url::toRoute(['update-attendance']) ?>",
            data: {
                data: data,
                status: status
            },
            dataType: "json",
            success: function(response) {
                Swal.close(); // Close the loader
                if (response.status) {
                    Swal.fire("Success!", response.message, "success");
                } else {
                    Swal.fire("Error!", response.error, "error");
                }
            },
            error: function(xhr, status, error) {
                Swal.close(); // Close the loader in case of error
                Swal.fire("Error!", "Something went wrong. Please try again.", "error");
            }
        });
    }
</script>