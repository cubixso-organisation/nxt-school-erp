<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\TemporaryAssignTeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Teacher on leave');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="temporary-assign-teacher-index">
    <!-- <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role==User::role_campus_sub_admin) { ?>
                    <?= Html::a(Yii::t('app', 'Create Temporary Assign Teacher'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
            </p>
            <div class="search-form" style="display:none">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div> -->
    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id', 'visible' => false],

                [
                    'attribute' => 'teacher_detail_id',
                    'label' => Yii::t('app', 'Teacher Detail'),
                    'value' => function ($model) {
                        return $model->teacherDetail->name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Teacher details', 'id' => 'grid-temporary-assign-teacher-search-teacher_detail_id']
                ],


                'date',

                'day_id',

                'period',

                'time_from',

                'time_to',

                [
                    'attribute' => 'class_id',
                    'label' => Yii::t('app', 'Class'),
                    'value' => function ($model) {
                        return $model->class->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-temporary-assign-teacher-search-class_id']
                ],

                [
                    'attribute' => 'section_id',
                    'label' => Yii::t('app', 'Section'),
                    'value' => function ($model) {
                        return $model->section->section_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()->asArray()->all(), 'id', 'section_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-temporary-assign-teacher-search-section_id']
                ],
                [
                    'attribute' => 'subject_id',
                    'label' => Yii::t('app', 'Subject'),
                    'value' => function ($model) {
                        return $model->subject->subject_name;
                    }],
                    [
                        'header' => Yii::t('app', 'Actions'),
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<button type="button" class="btn btn-primary waves-effect waves-light mt-1 replace-button" data-bs-toggle="modal" data-bs-target="#con-close-modal" data-teacher-id="' . $model->teacher_detail_id . '" data-id="' . $model->id . '">Replace</button>';
                        }
                    ],
                    
                    
                    
                    

                // [
                //     'class' => 'kartik\grid\ActionColumn',
                //     'template' => '{view} {update} {delete}',
                //     'buttons' => [
                //         'view' => function ($url, $model) {
                //             if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                //                 return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                //             }
                //         },
                //         'update' => function ($url, $model) {
                //             if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                //                 return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                //             }
                //         },
                //         'delete' => function ($url, $model) {
                //             if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                //                 return Html::a('<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url, [
                //                     'data' => [
                //                         'method' => 'post',
                //                         // use it if you want to confirm the action
                //                         'confirm' => 'Are you sure?',
                //                     ],
                //                 ]);
                //             }
                //         },


                //     ]



                // ],
            ];
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-temporary-assign-teacher']],
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
    <!-- Below Card Is Model for Replacing teacher form from Dashboard Starts Here  -->
    <div class="card">
        <div class="card-body">
            <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Replace Substitute Teacher</h4>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="teacher-select" class="form-label">Available Teachers</label>
                                        <select class="form-control" id="teacher-select">
                                            <!-- Options will be populated dynamically via AJAX -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="submitTeacher" class="btn btn-info waves-effect waves-light" id="assign-teacher-button">Assign Teachers</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Below Card Is Model for Replacing teacher form from Dashboard Ends Here  -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('.replace-button').click(function() {
            var teacherId = $(this).data('teacher-id');
            var id = $(this).data('id');
            console.log(teacherId);
            console.log(id);

            $('#con-close-modal').attr('data-id', id);
            $.ajax({
                type: 'GET',
                url: '<?php echo Url::toRoute(["leave-management/staff-leave-applied/replace"]) ?>',
                data: {
                    id: id,
                    teacherId: teacherId
                },
                success: function(response) {
                    var teachers = JSON.parse(response);

                    // Clear existing options
                    $('#teacher-select').empty();

                    // Iterate over the response data and create options
                    teachers.forEach(function(teacher) {
                        $('#teacher-select').append('<option value="' + teacher.teacher_detail_id + '">' + teacher.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error
                }
            });
        });

        $('#submitTeacher').click(function() {
            var selectedTeacherId = $('#teacher-select').val();
            var id = $('#con-close-modal').data('id');

            // Show loading indicator using SweetAlert
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make a separate AJAX request to save the selected teacher ID
            $.ajax({
                type: 'GET',
                url: '<?php echo Url::toRoute(["leave-management/staff-leave-applied/replaced-teacher"]) ?>',
                data: {
                    teacherId: selectedTeacherId,
                    id: id,
                },
                success: function(response) {
                    // Close loading indicator
                    Swal.close();

                    // Show success message using SweetAlert
                    Swal.fire({
                        title: 'Success!',
                        text: 'Teacher Replaced successfully.',
                        icon: 'success'
                    }).then(() => {
                        // Reload the page upon success
                        location.reload();
                    });

                    // Handle any further actions upon success if needed
                },
                error: function(xhr, status, error) {
                    // Close loading indicator
                    Swal.close();

                    // Show error message using SweetAlert
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while Replacing teacher.',
                        icon: 'error'
                    }).then(() => {
                        // Reload the page upon success
                        location.reload();
                    });
                }
            });
        });

    });
</script>