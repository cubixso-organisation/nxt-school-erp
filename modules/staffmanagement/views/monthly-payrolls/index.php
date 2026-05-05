<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\staffmanagement\models\search\MonthlyPayrollsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Monthly Payrolls');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="monthly-payrolls-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) { ?>
                    <?= Html::a(Yii::t('app', 'Create Monthly Payrolls'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
            <div class="row">
                <form id="payroll-generate" action="javascript:void(0)">
                    <div class="form-row align-items-center">
                        <div class="col-md-4 mb-3">
                            <label for="yearSelect">Year:</label>
                            <select class="form-control" id="yearSelect">
                                <!-- Year options will be populated dynamically using JavaScript -->
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="monthSelect">Month:</label>
                            <select class="form-control" id="monthSelect">
                                <!-- Populate with all months -->
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button type="submit" class="btn btn-primary" id="generateBtn">Generate</button>
                        </div>
                    </div>
                </form>

                </p>

            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <?php
                $gridColumn = [
                    ['class' => 'yii\grid\SerialColumn'],

                    ['attribute' => 'id', 'visible' => false],

                    // [
                    //     'attribute' => 'campus_id',
                    //     'label' => Yii::t('app', 'Campus'),
                    //     'value' => function ($model) {
                    //         return $model->campus->id;
                    //     },
                    //     'filterType' => GridView::FILTER_SELECT2,
                    //     'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->asArray()->all(), 'id', 'id'),
                    //     'filterWidgetOptions' => [
                    //         'pluginOptions' => ['allowClear' => true],
                    //     ],
                    //     'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-monthly-payrolls-search-campus_id']
                    // ],

                    [
                        'attribute' => 'staff_id',
                        'label' => Yii::t('app', 'Staff'),
                        'value' => function ($model) {
                            return $model->staff->name??"";
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffDetails::find()->asArray()->all(), 'id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Staff details', 'id' => 'grid-monthly-payrolls-search-staff_id']
                    ],



                    [
                        'attribute' => 'yearly_ctc',
                        'label' => Yii::t('app', 'Annual CTC'),
                        'value' => function ($model) {
                            return $model->yearly_ctc;
                        },

                    ],




                    // 'salary_components:ntext',

                    [
                        'attribute' => 'total_monthly_pay',
                        'label' => Yii::t('app', 'Total Monthly Payment'),
                        'value' => function ($model) {
                            return round($model->total_monthly_pay, 2);
                        },

                    ],

                    'date',

                    [
                        'attribute' => 'month',
                        'label' => Yii::t('app', 'Month'),
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->getMonthOptionsBadges($model->month);
                        },

                    ],

                    [
                        'attribute' => 'salary_group_id',

                        'label' => Yii::t('app', 'Salary Group Name'),
                        'value' => function ($model) {
                            return isset($model->group->name)? $model->group->name:'';
                        },

                    ],


                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{view} ',
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
                    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-monthly-payrolls']],
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

    <script>
        // JavaScript code to populate the year select field dynamically
        const yearSelect = document.getElementById('yearSelect');
        const currentYear = new Date().getFullYear();
        const lastFourYears = [currentYear, currentYear - 1, currentYear - 2, currentYear - 3];

        lastFourYears.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        });
    </script>


    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#payroll-generate').submit(function(event) {
                // Prevent the default form submission
                event.preventDefault();

                // Serialize form data
                var year = $('#yearSelect').val();
                var month = $('#monthSelect').val();

                var formData = {
                    'year': year,
                    'month': month
                };

                // Disable the button to prevent multiple submissions
                $('#generateBtn').prop('disabled', true);

                // Show loading message with SweetAlert
                swal({
                    title: 'Loading...',
                    text: 'Please wait while we process your request',
                    icon: 'info',
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false
                });

                // Send AJAX request
                $.ajax({
                    type: 'POST',
                    url: '<?= Url::toRoute('generate-payroll') ?>', // Specify your server endpoint here
                    data: formData,
                    success: function(response) {
                        // Handle successful response
                        swal({
                            title: 'Success!',
                            text: 'Payroll generated successfully',
                            icon: 'success',
                            buttons: false,
                            timer: 1500 // Automatically close after 1.5 seconds
                        }).then(function() {
                            // Reload the page after success
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors with SweetAlert
                        swal({
                            title: 'Error!',
                            text: 'Failed to generate payroll',
                            icon: 'error',
                            buttons: true
                        });
                        console.error('Error submitting form');
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        // Re-enable the button after the request is complete
                        $('#generateBtn').prop('disabled', false);
                    }
                });
            });

            // Handle button click
            $('#generateBtn').click(function(event) {
                // Submit the form
                $('#payroll-generate').submit();
            });
        });
    </script>