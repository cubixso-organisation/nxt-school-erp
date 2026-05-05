<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\base\FeeStructures as BaseFeeStructures;
use app\modules\admin\models\Campus;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\PayFees;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use kartik\grid\GridView;

$this->title = Yii::t('app', 'Pay Fees');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="pay-fees-index">



    <div class="card">
        <div class="card-body">
            <?= $this->render('_searchClass', ['model' => $FeeStructuresSearchSearchModel]); ?>
        </div>
    </div>





    <div class="card">


        <div class="card-body">



            <?php
            $get = Yii::$app->request->get();


            if (isset($get['FeeStructuresSearch'])) {
                if (!empty($get['FeeStructuresSearch']['student_class_id']) && !empty($get['FeeStructuresSearch']['class_section_id'])) {
                    $student_class_id = $get['FeeStructuresSearch']['student_class_id'];
                    $class_section_id = $get['FeeStructuresSearch']['class_section_id'];

                    echo Html::activeDropDownList(
                        $feeStructuresModal,
                        'id',
                        ArrayHelper::map(
                            FeeStructures::find()
                                ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                                ->andWhere(['student_class_id' => $student_class_id])
                                ->andWhere(['in', 'class_section_id', $class_section_id])
                                ->andWhere(['status' => FeeStructures::STATUS_ACTIVE])
                                ->all(),
                            'id',
                            function ($model) {
                                return $model->title . ' - ' . $model->studentClass->title . ' (' . $model->classSection->section_name . ')';
                            }
                        ),
                        ['prompt' => 'Select...', 'class' => 'form-control', 'onchange' => 'getData()']
                    );
                }
            }

            ?>


        </div>
    </div>




    <div class="card">

        <div class="card-body">


            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" onclick="getFeeStructureData()">
                Assign Fee
            </button>



        </div>
    </div>


    <div class="card">

        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],
                ['class' => '\kartik\grid\CheckboxColumn'],
                [
                    'attribute' => 'id',
                    'label' => 'Fee ID'
                ],

                'student_name',


                'admission_number',




                'phone_number',

                [
                    'attribute' => 'assigned_fee_details',
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Fee details'),
                    'value' => function ($model) {
                        $campus_id = $model->campus_id; // adjust if field is named differently

                        $current_academic_year = (new Campus)->getCurrentSession($campus_id);
                        // var_dump($current_academic_year);exit;

                        $pfd = [];
                        foreach ($model->payFees as $payFeesData) {
                            if ($payFeesData->academic_year_id == $current_academic_year) {
                                $pfd[] = $payFeesData->feeStructures;
                            }
                        }

                        $assigned_fee_details = '';
                        if (!empty($pfd)) {
                            $assigned_fee_details = (new \app\modules\admin\models\PayFees())->showListArr($pfd, 'title', $model->id);
                        }

                        return $assigned_fee_details;
                    },
                ],

                [
                    'attribute' => 'student_class_id',
                    'label' => Yii::t('app', 'Student Class'),
                    'value' => function ($model) {
                        return $model->studentClass->title;
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
                    'attribute' => 'section_id',
                    'label' => Yii::t('app', 'Student Section'),
                    'value' => function ($model) {
                        return $model->section->section_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                        ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->asArray()->all(), 'id', 'section_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-search-section_id']
                ],




                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->getStateOptionsBadges();
                    },


                ],

            ];
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProviderStudentDetails,
                'filterModel' => $studentDetailsSearch,
                'columns' => $gridColumn,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-details']],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                ],
                'export' => false,
                // your toolbar can include the additional full export menu
                'toolbar' => [
                    '{export}',
                    ExportMenu::widget([
                        'dataProvider' => $dataProviderStudentDetails,


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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div id="model-title-data"></div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">fee structure</th>
                            <th scope="col">Fee</th>
                            <th scope="col">Max deduction</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="table-data">


                        </tr>


                    </tbody>
                </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <?= Html::button('Assign Fee Selected Students', ['class' => 'btn btn-success', 'id' => 'assign_fee_all_student']) ?>


            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    let BaseUrl = "<?= Url::base() ?>";

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

    function getData() {
        let g_data = $("#feestructures-id").find(":selected").val();
        return g_data;
    }

    function getFeeStructureData() {
        let fee_structure_id = getData();
        if (fee_structure_id) {
            $.ajax({
                type: "POST",

                url: BaseUrl + '/admin/pay-fees/get-fee-structure-data',
                data: {
                    id: getData()
                },
                success: function(data) {
                    res = JSON.parse(data)

                    if (res.status == 'ok') {

                        console.log(res.details)


                        $("#model-title-data").html(' <h5 class="modal-title">' + res.details.title + '</h5>')
                        $("#table-data").html('<td>' + res.details.title + '</td><td>' + res.details.fee + '</td><td>' + res.details.maximum_detuction + '</td>')




                    }

                }
            });

        } else {
            alert('select fee structure')
        }
    }


    $(document).on("click", "#assign_fee_all_student", function(e) {
        let selected = $("input[type=checkbox]");
        let data = selected.serialize();
        let fee_structure_id = getData();
        if (fee_structure_id != '') {

            if (selected.length) {


                let url = '/admin/pay-fees/assign-fee-all-student'
                url = "<?= Url::base() ?>" + url
                $.ajax({
                    url: url,
                    data: data + '&fee_structure_id=' + fee_structure_id,
                    dataType: "json",
                    method: "post",
                    success: function(data) {


                        console.log(data)

                        if (data.status == 'ok') {

                            swal("Good job!", "Status Successfully Changed!", "success").then(okay => {
                                if (okay) {
                                    // window.location.reload();
                                }
                            });
                        } else {
                            swal("Sorry!", "Failed to update!", "error");
                        }


                    }




                });



            } else {
                alert("select some items to delete");
            }


        } else {
            alert('Select Fee Structure Id')
        }




    });


    function unassignFee(id, sid) {
        let baseUrl = "<?= Url::base(); ?>";

        let conform = confirm("Aru Shore Want to remove fee structure");
        if (conform == true) {


            $.ajax({
                url: baseUrl + '/admin/pay-fees/remove-fee',
                data: {
                    fee_structure_id: id,
                    student_id: sid
                },
                type: 'post',
                success: function(data) {
                    let res = JSON.parse(data)
                    console.log(res.status)
                    if (res.status == 'ok') {
                        swal(res.status, res.message, "success").then(okay => {
                            if (okay) {
                                window.location.reload();
                            }
                        });


                    } else {
                        swal("Sorry!", res.message, "error");

                    }
                }

            })
        }



    }
</script>