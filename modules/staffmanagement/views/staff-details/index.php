<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\staffmanagement\models\search\StaffDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = 'Staff Details';
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="staff-details-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) { ?>
                    <?= Html::a('Create Staff Details', ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <a href="javascript:void(0)" class="btn btn-info" id='import-all-staff'>Import All Staffs</a>

            </p>

        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],


                ['attribute' => 'id', 'visible' => false],

                'name',

                [
                    'attribute' => 'campus_id',
                    'label' => 'Campus',
                    'value' => function ($model) {
                        return $model->campus->name_of_the_educational_Institution;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->where(['id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-staff-attendence-settings-search-campus_id']
                ],

                [
                    'attribute' => 'designation_id',
                    'label' => 'Designation',
                    'value' => function ($model) {
                        return $model->designation->title;
                    },

                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffDesignations::find()->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Staff designations', 'id' => 'grid-staff-details-search-designation_id']
                ],

                // [
                //     'attribute' => 'payroll_id',
                //     'label' => 'Payroll',
                //     'value' => function ($model) {
                //         return $model->payroll->title;
                //     },

                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filter' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\Payroll::find()->asArray()->all(), 'id', 'title'),
                //     'filterWidgetOptions' => [
                //         'pluginOptions' => ['allowClear' => true],
                //     ],
                //     'filterInputOptions' => ['placeholder' => 'Payroll', 'id' => 'grid-staff-details-search-payroll_id']
                // ],

                'contact_no',
                'email:email',


                [
                    'attribute' => '',
                    'label' => "Add Salary",
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a href="' . \yii\helpers\Url::toRoute(['staff-salary/staff-view', 'id' => $model->id]) . '" class="btn btn-primary btn-sm btn-rounded">Add Salary</a>';
                    },
                ],




                // [
                //     'attribute' => 'status',
                //     'format' => 'raw',
                //     'value' => function ($model) {
                //         return $model->getStateOptionsBadges();
                //     },
                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filter' => \app\modules\staffmanagement\models\StaffDetails::getStateOptions(),
                //     'filterWidgetOptions' => [
                //         'pluginOptions' => ['allowClear' => true],
                //     ],
                //     'filterInputOptions' => ['placeholder' => 'status']

                // ],


                // [
                //     'class' => 'kartik\grid\ActionColumn',
                //     'template' => '{view} {update} {delete}',
                //     'buttons' => [
                //         'view' => function ($url, $model) {
                //             if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                //                 return Html::a('<button type="button" class="btn btn-inverse-primary btn-sm btn-rounded btn-icon"><i class="ti-eye"></i></button>', $url);
                //             }
                //         },
                //         'update' => function ($url, $model) {
                //             if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                //                 return Html::a('<button type="button" class="btn btn-inverse-primary btn-sm btn-rounded btn-icon"><i class="ti-pencil"></i></button>', $url);
                //             }
                //         },
                //         'delete' => function ($url, $model) {
                //             if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                //                 return Html::a('<button type="button" class="btn btn-inverse-primary btn-sm btn-rounded btn-icon"><i class="ti-trash"></i></button>', $url, [
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
                'bordered' => false,
                'class' => 'table table-striped mb-0',
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-staff-details']],
                'panel' => [
                    'type' => 'light',
                    'heading' => '<span class=""></span>  ' . Html::encode($this->title),
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
        // Attach a click event to the "Import Students" button
        $('#import-all-staff').click(function() {
            // Show full-screen loading overlay
            Swal.fire({
                title: "Loading...",
                text: "Importing teachers, please wait.",
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            });

            // Your AJAX call goes here
            $.ajax({
                type: "POST",
                url: "<?= Url::toRoute('import-all-staffs') ?>",
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
                            text: parseData.detail,
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