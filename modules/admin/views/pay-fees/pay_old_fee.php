<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\SpecialCoursesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\Campus;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\PaymentDetails;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Fee Details');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);

Yii::$app->session->remove('verify_otp');
Yii::$app->session->remove('pay_fees_id');
Yii::$app->session->remove('fee_deduction');
?>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">



                <div class="pay-fees-form">

                    <?php $form = ActiveForm::begin([
                        'id' => 'VerifyOtp',


                    ]); ?>

                    <?= $form->errorSummary($model); ?>



                    <?= $form->field($model, 'otp')->textInput(['placeholder' => 'Enter Otp'])->label('Enter Otp') ?>
                    <?= $form->field($model, 'sessionKey')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'deleteDataId')->hiddenInput()->label(false) ?>




                    <button type="button" id="verifyOtpBrn" class="btn btn-primary">Verify Otp</button>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <div class="test-danger" id="error">

                </div>

            </div>
        </div>
    </div>
</div>






<div class="special-courses-index">
    <div class="card">
        <div class="card-body">


            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) { ?>
                    <?php Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
            </p>
            <div class="search-form">
                <?= $this->render('_search_academic', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <?php
            if (isset($_GET['PayFeesSearch'])) {

                Yii::$app->session->set('redirect_url', $_SERVER['REDIRECT_QUERY_STRING']);


            ?>



                <?php
                if (!empty($model)) {

                    $student_id = $model->student->id ?? "";
                    $class_id = $model->student->studentClass->id ?? "";
                    $section_id = $model->student->section->id ?? "";
                    $pay_fees_id = $model->id ?? "";



                    $gridColumn = [
                        ['class' => 'yii\grid\SerialColumn'],

                        ['attribute' => 'id', 'visible' => false],



                        [
                            'attribute' => 'academic_year_id',
                            'pageSummary' => true,
                            'label' => Yii::t('app', 'Academic Year'),
                            'value' => function ($model) {
                                $student_id = $model->academicYear->title;
                                return $student_id;
                            }
                        ],


                        [
                            'attribute' => 'total_fee',
                            'label' => Yii::t('app', 'Total Fee'),
                            'value' => function ($model) {
                                $student_id = isset($model->student->id) ? $model->student->id : '';
                                return (new PaymentDetails())->getTotalFeeByStudentId($student_id, $model->fee_structures_id);
                            }
                        ],




                        [
                            'attribute' => 'balance',
                            'label' => Yii::t('app', 'balance'),
                            'value' => function ($model) {
                                $student_id = isset($model->student->id) ? $model->student->id : '';
                                $class_id = isset($model->student->studentClass->id) ? $model->student->studentClass->id : '';
                                $section_id = isset($model->student->section->id) ? $model->student->section->id : '';
                                $pay_fees_id = $model->id;
                                $academicYearId = Yii::$app->request->get('PayFeesSearch')['academic_year_id'] ?? null;

                                $paid = (new PaymentDetails())->getPaidAmount($student_id, $class_id, $section_id, $pay_fees_id, $academicYearId);
                                // var_dump($student_id);
                                // var_dump($paid);
                                // exit;
                                $fee_structures = FeeStructures::find()->joinWith(['payFees as pf'])->where(['fee_structures.id' => $model->fee_structures_id])->andWhere(['pf.academic_year_id' => $academicYearId])->one();

                                $fees_cut = isset($model->fees_cut) ? floatval($model->fees_cut) : 0;
                                $fee = isset($fee_structures->fee) ? floatval($fee_structures->fee) : 0;
                                $paid = isset($paid) ? floatval($paid) : 0; // Ensure $paid is numeric

                                $studentPayAmount = $fee - $fees_cut;
                                // var_dump($studentPayAmount - $paid);
                                // exit;
                                return $studentPayAmount - $paid;
                            }
                        ],






                        [
                            'attribute' => 'created_on',
                            'label' => Yii::t('app', 'Payment Date'),
                            'format' => 'raw',
                            'value' => function ($model) {
                                $htmlInput = '';
                                return  $htmlInput .= '<input type="date" name="payment_date" id="payment_date_data' . $model->id . '">';
                            },


                        ],



                        [
                            'attribute' => 'student_class_id',
                            'label' => Yii::t('app', 'Student Class'),
                            'value' => function ($model) {
                                return isset($model->student->studentClass->title) ? $model->student->studentClass->title : '';
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
                                return isset($model->student->section->section_name) ? $model->student->section->section_name : '';
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
                            'attribute' => 'student_id',
                            'label' => Yii::t('app', 'Student'),
                            'value' => function ($model) {
                                return $model->student->student_name . ' ' . $model->student->id;
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                                ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                                ->asArray()->all(), 'id', 'student_name'),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-student-details-search-user_id']
                        ],
                        'reference_number',



                        [
                            'attribute' => 'fee_structures_id',
                            'label' => Yii::t('app', 'fee structure'),
                            'value' => function ($model) {
                                if (isset($model->feeStructures) && is_object($model->feeStructures)) {
                                    $title = isset($model->feeStructures->title) ? $model->feeStructures->title : 'N/A';
                                    $fee = isset($model->feeStructures->fee) ? floatval($model->feeStructures->fee) : 0;
                                    $fees_cut = isset($model->fees_cut) ? floatval($model->fees_cut) : 0;

                                    return $title . '-' . ($fee - $fees_cut) . '/-';
                                } else {
                                    return 'Fee structure not available';
                                }
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\FeeStructures::find()
                                ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                                ->asArray()->all(), 'id', 'title'),
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => 'Fee Structure', 'id' => 'grid-student-details-search-fee_structures_id']
                        ],



                        [
                            'attribute' => 'fees_cut',
                            'label' => Yii::t('app', 'Max Deduction'),
                            'value' => function ($model) {
                                return $model->fees_cut;
                            },

                        ],






                        [
                            'attribute' => 'payment_mode',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $lists = $model->gePaymentModeOptions();
                                $html = '';

                                $html .= '<select id="payment_mode_list' . $model->id . '" data-id="' . $model->id . '" >';
                                $html .= '<option value="" selected>--Select--</option>';

                                foreach ($lists as $key => $list) {
                                    if ($key == $model->payment_mode) {
                                        $html .= '<option value="' . $key . '" selected>' . $list . '</option>';
                                    } else {
                                        $html .= '<option value="' . $key . '">' . $list . '</option>';
                                    }
                                }
                                $html .= '</select>';

                                return $html;
                            },

                        ],


                        [
                            'attribute' => 'remarks',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $htmlInput = '';
                                return  $htmlInput .= '<input type="text" name="remarks" id="remarks_data' . $model->id . '" value="' . $model->remarks_of_pay_fee . '">';
                            },


                        ],


                        [
                            'attribute' => 'amount',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $htmlInput = '';
                                return  $htmlInput .= '<input type="number" name="amount" id="amount_data' . $model->id . '" value="">';
                            },


                        ],


                        [
                            'attribute' => 'payNow',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $htmlInput = '';
                                return '<button type="button" class="btn btn-success" onClick="payNow(' . $model->id . ')" >Pay</button>';
                            },


                        ],




                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'template' => ' {update} ',
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
                                        return '<span class="fas fa-trash-alt" type="button" id="deleteBtn" onClick="deleteData(' . $model->id . ')"></span>';
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
                        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-special-courses']],
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
                    ]);
                }
            } else {
                echo "data not found";
            }

            ?>
        </div>
    </div>
</div>



<?php
if (isset($_GET['PayFeesSearch'])) {
?>

    <div class="special-courses-index">
        <div class="card">
            <div class="card-body">
                <?= $this->render('../payment-details/payment_history_of_student', ['model' => $PaymentDetailsModel, 'dataProvider' => $dataProviderPaymentDetailsSearch, 'searchModel' => $PaymentDetailsSearch]); ?>
            </div>
        </div>
    </div>
<?php } ?>







<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



<script>
    let base = "<?= Url::base(); ?>"

    function deleteData(id) {
        let campusId = "<?= (new User())->getCampusesByUser(Yii::$app->user->identity->id) ?>";


        let url = base + '/admin/pay-fees/assign-fee-details-delete-conform';
        let con = confirm("Do You Want to Delete");
        if (con == true) {
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    id: id,
                    campusId: campusId
                },
                success: function(res) {
                    let response = JSON.parse(res)
                    if (response.status == 'ok') {
                        let msgRes = JSON.parse(response.message)
                        // console.log(msgRes.Details)
                        $('document').ready(function() {

                            $('#exampleModal').modal('show')
                        });

                        $("#payfees-sessionkey").val(msgRes.Details);
                        $("#payfees-deletedataid").val(id);



                    } else {



                    }
                }
            })
        }






    }





    $("#verifyOtpBrn").on("click", function() {
        let pay_fees_otp = $("#payfees-otp").val()
        let sessionKey = $("#payfees-sessionkey").val();
        let payfeesDeleteDataId = $("#payfees-deletedataid").val();
        let urlDel = base + '/admin/pay-fees/assign-fee-details-delete-data';

        $.ajax({
            url: urlDel,
            type: 'post',
            data: {
                sessionKey: sessionKey,
                pay_fees_otp: pay_fees_otp,
                payfeesDeleteDataId: payfeesDeleteDataId
            },
            success: function(data) {
                let response = JSON.parse(data)
                console.log(response)
                if (response.status == 'ok') {
                    location.reload();

                } else {
                    $("#error").html(response.message)
                }


            }


        })


    })
    let paymentMode = '';
    $(document).on('change', 'select[id^=payment_mode_list]', function() {
        var id = $(this).attr('data-id');
        paymentMode = $(this).val();
    })

    function payNow(id) {
        baseUrl = "<?= Url::base($schema = true) ?>";
        let remarks = $("#remarks_data" + id).val();
        let amount = $("#amount_data" + id).val();
        let payment_date = $("#payment_date_data" + id).val();


        paymentMode = paymentMode;
        $.ajax({
            type: 'post',
            data: {
                pay_fees_id: id,
                remarks: remarks,
                paymentMode: paymentMode,
                amount: amount,
                payment_date: payment_date
            },
            url: baseUrl + '/admin/pay-fees/pay-now',
            success: function(res) {
                let response = JSON.parse(res)
                if (response.status == 'ok') {
                    swal("Good job!", response.message, "success");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);



                } else {

                    swal("Sorry!", response.error, "error");

                }
            }
        })


    }
</script>