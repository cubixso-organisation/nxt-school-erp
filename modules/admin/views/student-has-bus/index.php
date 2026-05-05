<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\StudentHasBusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\base\BusDetails;
use app\modules\admin\models\Campus;
use app\modules\admin\models\StudentHasBus;
use kartik\depdrop\DepDrop;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Assign Student To Bus');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="student-has-bus-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) { ?>
                    <?= Html::a(Yii::t('app', 'Create Student Has Bus'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <a href="javascript:void(0)" class="btn btn-info" id='import-all-staff'>Import Students</a>
                <a href="javascript:void(0)" class="btn btn-info" id="assign-bus-button">Assign Bus</a>
                <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-primary search-button d-none']) ?>
            </p>
            <div class="search-form" style="display:none">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>

            <div class="assign-form-bus" style="display:none">
                <?php
                $model = new StudentHasBus();
                $form = ActiveForm::begin([
                    'id' => 'assign-bus-form',
                    'action' => 'javascript:void(0)',
                ]); ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'bus_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(BusDetails::find()
                                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                                ->orderBy('id')->asArray()->all(), 'id', 'title'),
                            'options' => ['placeholder' => 'Choose Bus details', 'id' => 'bus-id'],
                            'pluginOptions' => ['allowClear' => true],
                        ])->label(false); ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'bus_route_id')->widget(DepDrop::classname(), [
                            'options' => ['id' => 'bus-route-id'],
                            'pluginOptions' => [
                                'depends' => ['bus-id'],
                                'placeholder' => 'Select...',
                                'url' => Url::to(['/admin/student-has-bus/bus-route-data']),
                            ],
                        ])->label(false); ?>
                    </div>
                </div>

                <?= Html::hiddenInput('selected_students', '', ['id' => 'selected-students']); ?>

                <div class="form-group">
                    <?= Html::submitButton('Assign', ['class' => 'btn btn-primary']); ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?php
        $gridColumn = [

            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $key];
                },
            ],
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
            //         'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-student-has-bus-search-campus_id']
            //     ],


            [
                'attribute' => 'student_class_id',
                'label' => Yii::t('app', 'Student Class'),
                'value' => function ($model) {

                    return $model->student->studentClass->title ?? "";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                    ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    ->asArray()->all(), 'id', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-search-student_class_id']
            ],


            [
                'attribute' => 'class_section_id',
                'label' => Yii::t('app', 'Student Section'),
                'value' => function ($model) {

                    return $model->student->section->section_name ?? "";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                    ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    ->asArray()->all(), 'id', 'section_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Student Section', 'id' => 'grid-student-details-search-section_id']
            ],




            [
                'attribute' => 'student_id',
                'label' => Yii::t('app', 'Student'),
                'value' => function ($model) {
                    return $model->student->student_name ?? "";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                    ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

                    ->asArray()->all(), 'id', 'student_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-student-has-bus-search-student_id']
            ],

            [
                'attribute' => 'bus_id',
                'label' => Yii::t('app', 'Bus'),
                'value' => function ($model) {
                    return $model->bus->title ?? "";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusDetails::find()
                    ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

                    ->asArray()->all(), 'id', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Bus details', 'id' => 'grid-student-has-bus-search-bus_id']
            ],

            [
                'attribute' => 'bus_route_id',
                'label' => Yii::t('app', 'Bus Route'),
                'value' => function ($model) {
                    return $model->busRoute->point_name ?? "";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusRoute::find()
                    ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

                    ->asArray()->all(), 'id', 'point_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Bus route', 'id' => 'grid-student-has-bus-search-bus_route_id']
            ],


            [


                'attribute' => 'status',
                "format" => 'raw',
                'label' => Yii::t('app', 'Status'),
                'filter'  => (new StudentHasBus())->getStateOptions(),
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
            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-has-bus']],
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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Attach a click event to the "Import Students" button
        $('#import-all-staff').click(function() {
            // Show full-screen loading overlay
            Swal.fire({
                title: "Loading...",
                text: "Importing Students.",
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            });

            // Your AJAX call goes here
            $.ajax({
                type: "POST",
                url: "<?= Url::toRoute('import-students') ?>",
                data: {
                    // Your data goes here
                },
                success: function(data) {
                    // Close loading overlay on success

                    parseData = JSON.parse(data);
                    Swal.close();
                    console.log(parseData.status);
                    // Check the response status
                    if (parseData.status == "OK") {
                        // Display success message if students are imported successfully
                        Swal.fire({
                            title: "Success",
                            text: parseData.message,
                            icon: "success",
                            timer: 3000, // Show the message for 3 seconds
                            showConfirmButton: false
                        }).then(function() {
                            // Refresh the page after the success message disappears
                            location.reload();
                        });
                    } else {
                        // Display an error message if import fails or no students are imported
                        Swal.fire("Oops!", parseData.detail, "error");
                    }
                },
                error: function() {
                    // Close loading overlay on error
                    Swal.close();

                    // Display an error message if the AJAX call fails
                    Swal.fire("Oops!", "Something went wrong. Please try again later.", "error");
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Function to handle click on "Assign Bus" button
        $('#assign-bus-button').click(function() {
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

            // Set the value of hidden input field to selected rows (comma-separated)
            $('#selected-students').val(selectedRows.join(','));

            // Show the assign form
            $('.assign-form-bus').show();
        });

        // Function to handle form submission
        $('#assign-bus-form').on('submit', function(e) {
            e.preventDefault();

            // Perform AJAX submit of the form
            $.ajax({
                type: 'POST',
                url: "<?= Url::to(['assign-bus']) ?>",
                data: $(this).serialize(), // Serialize form data
                success: function(response) {
                    // Handle success response
                    Swal.fire("Success", "Students assigned to bus successfully!", "success")
                        .then(function() {
                            // Reload the page after success
                            location.reload();
                        });
                },
                error: function() {
                    // Handle error

                }
            });
        });
    });
</script>