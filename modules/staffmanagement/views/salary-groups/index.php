<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\staffmanagement\models\search\SalaryGroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\staffmanagement\models\base\SalaryGroupComponents;
use app\modules\staffmanagement\models\base\SalaryGroups;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = 'Salary Groups';
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);

$model = new SalaryGroups();
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<div class="salary-groups-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) { ?>
                    <?= Html::a('Create Salary Groups', ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <?= Html::a('Advance Search', '#', ['class' => 'btn btn-info search-button']) ?>
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

                'name',
                [
                    'attribute' => 'salary_components',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (empty($model->salaryGroupComponents)) {
                            return "No components available";
                        }

                        $list = "<ol>";
                        foreach ($model->salaryGroupComponents as $salaryComponent) {
                            // Check if component exists before accessing its properties
                            if (isset($salaryComponent->component)) {
                                $list .= "<li> > {$salaryComponent->component->name}</li>";
                            } else {
                                $list .= "<li> > Component not found</li>";
                            }
                        }
                        $list .= "</ol>";
                        return $list;
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
                    'attribute' => '',
                    'label' => "Manage Staffs",
                    'format' => 'raw',
                    'value' => function ($model) {
                        return "<button data-toggle='modal' onClick='preselected(" . $model->id . ")' class='btn btn-primary'  data-id='" . $model->id . "'>Manage Staff</button>";
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
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-salary-groups']],
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Staff Members:</h5>
                <button type="button" class="close" onclick=closeModel() data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="assignStaffForm">
                    <div class="form-group">
                        <select id="staffList" multiple></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveChanges()">
                    <?= $model->id ?>

                    Save
                    <div id="loadingSpinner" class="spinner-border d-none" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

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



<?php $url = Url::to(Yii::$app->request->baseUrl . '/admin/staff-management/salary-groups/get-staff-data'); ?>
<?php $preSelectedValuesUrl = Url::to(Yii::$app->request->baseUrl . '/admin/staff-management/salary-groups/selected-values'); ?>
<script>
    function preselected(id) {
        $.ajax({
            type: "GET",
            url: '<?= $preSelectedValuesUrl ?>?id=' + id,


            dataType: 'json',
            success: function(response) {
                console.log(response);

                var selectedValues = response.map(function(item) {
                    return item.id;
                });
                // Call the openModal function with the pre-selected values
                manageStaff(id, selectedValues);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching pre-selected values:', error);
            }
        });
    }




    function manageStaff(id, selectedValues) {

        console.log('Opening modal...');
        var modal = $('#exampleModal');
        modal.attr("data-id", id);
        $('#exampleModal').modal('show');
        // Initialize Selectize on the staffList select element
        var $mSelect = $('#staffList').selectize({
            placeholder: "Select staff members",
            plugins: ['remove_button'],
            closeAfterSelect: true
        });



        var groupId = id;

        $.ajax({
            url: '<?= $url ?>',
            dataType: 'json',
            success: function(data) {
                console.log('Staff data received:', data);

                // Get Selectize instance
                var selectize = $mSelect[0].selectize;

                // Clear previous options
                selectize.clearOptions();

                // Loop through the data and append options to Selectize
                $.each(data, function(index, value) {
                    selectize.addOption({
                        value: value.id,
                        text: value.text
                    });
                });
                if (selectedValues && selectedValues.length > 0) {
                    $.each(selectedValues, function(index, selectedValue) {
                        selectize.addItem(selectedValue, true); // true for silent mode
                    });
                }

                // Refresh Selectize to display the new options
                selectize.refreshOptions();
                // Refresh Selectize to display the new options

                // Open modal

            },
            error: function(xhr, status, error) {
                console.error('Error fetching staff data:', error);
            }
        });
    }
    <?php $assignGroupToStaffUrl = Url::to(Yii::$app->request->baseUrl . '/admin/staff-management/salary-groups/group-to-staff'); ?>

    function saveChanges() {

        var dataIdValue = $("#exampleModal").data("id");
        var selectedValues = $('#staffList').val();
        var salaryGroupId = dataIdValue;

        $('#loadingSpinner').removeClass('d-none');
        console.log("selected..... ", selectedValues);
        var data = {
            selectedValue: selectedValues,
            salaryGroupId: salaryGroupId,
        }

        $.ajax({
            type: "POST",
            url: "<?= $assignGroupToStaffUrl ?>",
            data: data,
            dataType: "JSON",

            success: function(response) {

                $('#loadingSpinner').addClass('d-none');

                // Display success message using SweetAlert
                swal.fire({
                    title: "Success!",
                    text: "Changes have been saved successfully.",
                    icon: "success",
                    button: false, // No close button
                    timer: 3000 // Auto close after 3 seconds
                }).then(function() {
                    // Reload the page after the SweetAlert is closed
                    location.reload();
                });

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // Hide loading spinner
                $('#loadingSpinner').addClass('d-none');

                // Display error message using SweetAlert
                swal({
                    title: "Error!",
                    text: "An error occurred while saving changes.",
                    icon: "error",
                    button: false, // No close button
                    timer: 3000 // Auto close after 3 seconds
                }).then(function() {
                    // Reload the page after the SweetAlert is closed
                    location.reload();
                });
            }
        });
        // Your code to handle selected values goes here
    }


    function closeModel() {
        $('#exampleModal').modal('hide')
    }
</script>