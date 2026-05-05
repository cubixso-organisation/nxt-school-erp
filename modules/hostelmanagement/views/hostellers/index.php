<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hostelmanagement\models\search\HostellersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Hostellers');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="hostellers-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) { ?>
                    <?= Html::a(Yii::t('app', 'Create Hostelers'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <?= Html::a(Yii::t('app', 'Import Students'), '#', [
                    'class' => 'btn btn-info',
                    'id' => 'import-students-btn',
                ]) 
                ?>
            </p>

        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php 
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],


                ['attribute' => 'id', 'visible' => false],

                [
                    'attribute' => 'student_id',
                    'label' => Yii::t('app', 'Student'),
                    'format' => 'raw', // Render HTML
                    'value' => function ($model) {
                        if (isset($model->student)) {
                            $url = Url::toRoute(['admin/student-details/view', 'id' => $model->student_id]);
                            return Html::a($model->student->student_name, $url, ['target' => '_blank']);
                        }
                        // Return a fallback message if no student is linked
                        return Yii::t('app', 'No student found');
                    },

                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'student_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-hostellers-search-student_id']
                ],

                [
                    'attribute' => 'campus_id',
                    'label' => Yii::t('app', 'Campus'),
                    'value' => function ($model) {
                        return $model->campus->name_of_the_educational_Institution;
                    },


                ],

                [
                    'attribute' => 'hostel_id',
                    'label' => Yii::t('app', 'Hostel'),
                    'value' => function ($model) {
                        return isset($model->hostel->name) ? $model->hostel->name:'';
                    },

                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Hostels::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Hostels', 'id' => 'grid-hostellers-search-hostel_id']
                ],

                'joining_date',

                // 'bill_date',

                // 'next_bill_date',

                // 'sty_type',

                // 'advance_payment',

                // 'fees',


                [
                    'attribute' => 'room_id',
                    'label' => Yii::t('app', 'Room'),
                    'value' => function ($model) {
                        return isset($model->room->name_of_the_room) ? $model->room->name_of_the_room:'';
                    },

                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Rooms::find()->asArray()->all(), 'id', 'name_of_the_room'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Hostels', 'id' => 'grid-hostellers-search-hostel_id']
                ],

                // 'address',

                // 'aadhar_number',

                // 'photo',

                // 'aadhar_front',

                // 'aadhar_back',

                // 'application_form_file',

                // 'leave_of_date',

                // 'leave_month',

                // 'is_all_items_checked',

                // 'is_balance_amount_paid',
                [
                    'attribute' => 'status',
                    'filter'  => \app\modules\hostelmanagement\models\Hostellers::getStateOptions(),
                    "format" => 'raw',
                    'value' => function ($data) {


                        return $data->getStateOptionsBadges();
                    }
                ],

                // [ 
                //     'attribute' => 'status',
                //     'format' => 'raw',
                //     'value' => function ($model) {
                //         return $model->getStateOptionsBadges();
                //     },
                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filter' => \app\modules\hostelmanagement\models\Hostellers::getStateOptions(),
                //     'filterWidgetOptions' => [
                //         'pluginOptions' => ['allowClear' => true],
                //     ],
                //     'filterInputOptions' => ['placeholder' => 'status']

                // ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view} {update} ',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
                                return Html::a('<button type="button" class="btn btn-inverse-primary btn-sm btn-rounded btn-icon"><i class="fas fa-eye"></i></button>', $url);
                            }
                        },
                        'update' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
                                return Html::a('<button type="button" class="btn btn-inverse-primary btn-sm btn-rounded btn-icon"><i class="fas fa-pencil-alt"></i></button>', $url);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
                                return Html::a('<button type="button" class="btn btn-inverse-primary btn-sm btn-rounded btn-icon"><i class="fas fa-trash"></i></button>', $url, [
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
                'bordered' => false,
                'class' => 'table table-striped mb-0',
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-hostellers']],
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();

        $.ajax({
            type: "POST",
            url: "<?= Url::toRoute(['hostellers/status-change']) ?>",
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


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<?php
$url = Url::toRoute(['import-students']);
if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {

    $campus_id = (new User())->getCampusId();
} else {
    $campus_id = (new User())->getChiefWardenCampus(\Yii::$app->user->identity->id);
}

?>
<script>
    $(document).ready(function() {
        // Attach a click event to the "Import Students" button
        $('#import-students-btn').click(function() {
            // Show full-screen loading overlay
            Swal.fire({
                title: "Loading...",
                text: "Importing students, please wait.",
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            });
            console.log(<?= $campus_id ?>);
            // Your AJAX call goes here
            $.ajax({
                type: "POST",
                url: "<?= $url ?>",
                data: {
                    'campus_d': <?= $campus_id ?>,
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
                            timer: 1000, // Show the message for 3 seconds
                            showConfirmButton: false
                        }).then(function() {
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