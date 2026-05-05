<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\librarymanagement\models\search\LibraryMembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Library Members');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="library-members-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>

                <?= Html::a(Yii::t('app', 'Import Students'), '#', [
                    'class' => 'btn btn-success',
                    'id' => 'import-students-btn',
                ]) ?>
                <?= Html::a(Yii::t('app', 'Import Teachers'), '#', [
                    'class' => 'btn btn-info',
                    'id' => 'import-teachers-btn',
                ]) ?>
            </p>
            <div class="search-form" style="display:none">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id', 'visible' => false],

                'member_id',

                'library_card_no',

                'admission_no',

                [
                    'attribute' => 'name',
                    'label' => 'Name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->name ?? "";
                    },


                ],
                'member_type',

                'phone',

                [
                    'attribute' => 'campus_id',
                    'label' => Yii::t('app', 'Campus'),
                    'value' => function ($model) {
                        return $model->campus->name_of_the_educational_Institution;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-staff-leave-types-search-campus_id']
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
                'id' => 'library-members-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-library-members']],
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


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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

<?php

$url = Url::toRoute(['import-student']);
$teacherUrl = Url::toRoute(['import-teacher']);
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

            // Your AJAX call goes here
            $.ajax({
                type: "POST",
                url: "<?= $url ?>",
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



    $(document).ready(function() {
        // Attach a click event to the "Import Students" button
        $('#import-teachers-btn').click(function() {
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
                url: "<?= $teacherUrl ?>",
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
                            text: parseData.Success,
                            icon: "success",
                            timer: 3000, // Show the message for 3 seconds
                            showConfirmButton: false
                        }).then(function() {
                            // Refresh the page after the success message disappears
                            location.reload();
                        });
                    } else {
                        // Display an error message if import fails or no students are imported
                        Swal.fire("Oops!", parseData.error, "error");
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