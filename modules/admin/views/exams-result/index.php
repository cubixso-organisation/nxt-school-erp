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

    <?php
    $get = Yii::$app->request->get();

    if (!empty($get) && isset($get['ExamsResultSearch'])) { ?>
        <button class="btn btn-success btn-sm" onclick="updateGrade( <?= $get['ExamsResultSearch']['section_id'] ?? null ?>, <?= $exam_id = $get['ExamsResultSearch']['exam_id'] ?? null ?>)">Update All Grade & CGPA</button>
        <button class="btn btn-success btn-sm" onclick="updatePercentage( <?= $get['ExamsResultSearch']['section_id'] ?? null ?>, <?= $exam_id = $get['ExamsResultSearch']['exam_id'] ?? null ?>)">Update All Grade & CGPA</button>

    <?php } ?>
    <?php

    if (!empty($get) && isset($get['ExamsResultSearch'])) {
        $academic_year_id = $get['ExamsResultSearch']['academic_year_id'] ?? null;
        $class_id = $get['ExamsResultSearch']['class_id'] ?? null;
        $section_id = $get['ExamsResultSearch']['section_id'] ?? null;
        $exam_id = $get['ExamsResultSearch']['exam_id'] ?? null;

        if (!empty($academic_year_id) && !empty($class_id) && !empty($section_id)) {
            $query = \app\modules\admin\models\StudentDetails::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['status' => ClassSections::STATUS_ACTIVE]);

            if (!empty($class_id)) {
                $query->andWhere(['student_class_id' => $class_id]);
            }
            if (!empty($section_id)) {
                $query->andWhere(['section_id' => $section_id]);
            }

            $studentFilter = \yii\helpers\ArrayHelper::map($query->asArray()->all(), 'id', 'student_name');
    ?>

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
                            'filter' => $studentFilter,  // Directly use the pre-fetched student list here
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-exams-result-search-student_id'],
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
                            'attribute' => 'marks_scored',
                            'format' => 'raw',
                            'label' => Yii::t('app', 'Marks Scored'),
                            'value' => function ($model) {


                                $marksTable = '<table class="table table-bordered table-sm">';
                                $totalMarks = 0;

                                if ($model->marksDevisionResult) {
                                    foreach ($model->marksDevisionResult as $marksDevision) {
                                        $marksTable .= "<tr>";
                                        $marksTable .= "<td>{$marksDevision->marksDevision->short_hand}</td>";
                                        $marksTable .= "<td>{$marksDevision->marks_scored}</td>";
                                        $marksTable .= "</tr>";
                                        $totalMarks += $marksDevision->marks_scored;
                                    }

                                    // Add a row for the total
                                    $marksTable .= "<tr>";
                                    $marksTable .= "<td><strong>Total</strong></td>";
                                    $marksTable .= "<td><strong>{$totalMarks}</strong></td>";
                                    $marksTable .= "</tr>";
                                } else {
                                    $marksTable .= "<tr><td colspan='2'>N/A</td></tr>";
                                }

                                $marksTable .= '</table>';

                                // Add the update button below the table
                                $calculateButton = "<button class='btn btn-sm btn-success' onclick='updateMarksModal(" . $model->exams_result_id . "," . $model->exam_scheduled_id . "," . $model->exam_id . "," . $model->student_id . ")'>Update Marks</button>";

                                return $marksTable . $calculateButton;
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => (new ExamsResult())->getMarksTypeOptions(),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => 'marks type', 'id' => 'grid-state-search-marks_type']
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
                        // [
                        //     'class' => 'kartik\grid\EditableColumn',
                        //     'vAlign' => 'middle',
                        //     'hAlign' => 'center',
                        //     'attribute' => 'marks_type',
                        //     'value' => function ($model) {
                        //         if (empty($model->marks_type)) {
                        //             return "Not Set";
                        //         } else {
                        //             return strip_tags($model->getMarksTypeBadges());
                        //         }
                        //     },
                        //     'readonly' => false,
                        //     'editableOptions' => function ($model, $key, $index) {
                        //         return [
                        //             'name' => 'note', // this will be sent to controller to process
                        //             'header' => 'marks_type',
                        //             'asPopover' => false,
                        //             'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                        //             'data' => (new ExamsResult())->getMarksTypeOptions(),
                        //             'beforeInput' => '<h6 style="font-weight: bold">Marks Type</h6>',
                        //             'value' => function ($model) {
                        //                 if (empty($model->marks_type)) {
                        //                     return "Not Set";
                        //                 } else {
                        //                     return strip_tags($model->getMarksTypeBadges());
                        //                 }
                        //             }, // in this case, $model is an array. For others, $model->employer_score
                        //         ];
                        //     },
                        // ],

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


<!-- Modal -->
<div class="modal fade" id="updateMarks" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <h5 class="modal-title">Update Marks</h5>
                <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Marks Type</th>
                            <th>Enter Marks</th>
                        </tr>
                    </thead>
                    <tbody id="marksTableBody">
                        <!-- Dynamic rows will be appended here -->
                    </tbody>
                </table>
                <!-- Hidden fields for additional data -->
                <input type="hidden" id="studentId" name="student_id" />
                <input type="hidden" id="examId" name="exam_id" />
                <input type="hidden" id="examScheduledId" name="exam_scheduled_id" />
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeModal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitMarks">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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


    // Update Marks Modal


    function updateMarksModal(id, exam_scheduled_id, exam_id, student_id) {
        $('#loading' + id).show(); // Show loading icon

        $('#updateMarks').modal('show');
        $.ajax({
            type: "GET",
            url: "<?= Url::toRoute(['get-scheduled-marks']) ?>",
            data: {
                exam_schedule_id: exam_scheduled_id,
                _cache: Date.now()
            },
            dataType: "json",
            success: function(response) {
                $('#loading' + id).hide(); // Hide loading icon

                if (response.status == "OK") {
                    console.log(response);

                    // Clear the previous content
                    $('#marksTableBody').empty();

                    // Iterate over the response data and create rows
                    $.each(response.data, function(index, mark) {
                        let row = `
                    <tr data-max-marks="${mark.max_marks_devision}" data-devision-id="${mark.devision_id}">
                        <td>${mark.non_editable_field} - (${mark.max_marks_devision})</td>
                        <td><input type="text" class="form-control" name="mark_${mark.id}" value="${mark.mark}" /></td>
                    </tr>`;
                        $('#marksTableBody').append(row);
                    });

                    // Set hidden fields
                    $('#studentId').val(student_id);
                    $('#examId').val(exam_id);
                    $('#examScheduledId').val(exam_scheduled_id);
                } else {
                    // Handle error
                }
            },
            error: function(xhr, status, error) {
                // Handle error
                $('#loading' + id).hide(); // Hide loading icon
            }
        });

        // Bind the submit button click event here to capture the correct `id`
        $('#submitMarks').off('click').on('click', function() {
            let studentId = $('#studentId').val();
            let examId = $('#examId').val();
            let examScheduledId = $('#examScheduledId').val();
            let formData = {
                student_id: studentId,
                exam_id: examId,
                exam_scheduled_id: examScheduledId,
                exam_result_id: id, // `id` is correctly passed here
                marks: []
            };

            // Collect all mark inputs and their associated data
            $('#marksTableBody tr').each(function() {
                let maxMarks = $(this).data('max-marks');
                let devisionId = $(this).data('devision-id');
                $(this).find('input').each(function() {
                    let markId = $(this).attr('name').split('_')[1];
                    formData.marks.push({
                        id: markId,
                        mark: $(this).val(),
                        max_marks_devision: maxMarks,
                        devision_id: devisionId
                    });
                });
            });

            // Show SweetAlert loader
            Swal.fire({
                title: 'Saving...',
                text: 'Please wait while we save the data.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: "POST",
                url: "<?= Url::toRoute(['save-marks']) ?>",
                data: formData,
                success: function(response) {
                    if (response.status == "OK") {
                        // Hide the loader and show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            text: 'The marks have been successfully saved.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload the page
                            location.reload();
                        });
                    } else {
                        // Handle error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'There was an error saving the marks.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred.',
                    });
                }
            });
        });
    }



    $(document).on('click', '.closeModal', function() {

        $('#updateMarks').modal('hide');
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
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Grades updated successfully.',
                    showConfirmButton: true
                });
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

<?php }
    } else { ?>
<h4>Please Filter to get data</h4>

<?php }
?>