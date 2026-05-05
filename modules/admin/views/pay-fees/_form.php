<?php



use app\modules\admin\models\Campus;
use app\modules\admin\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\PayFees */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget([
    'viewFile' => '_script', 'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'PaymentDetails',
        'relID' => 'payment-details',
        'value' => \yii\helpers\Json::encode($model->paymentDetails),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">

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




<div class="pay-fees-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>





    <?= $form->field($model, 'fee_structures_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\FeeStructures::find()
            ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

            ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => [
            'placeholder' => Yii::t('app', 'Choose Fee structures'),
            'id' => 'fee-structures-id',
            'disabled' => $model->isNewRecord ? false : true
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>


    <?php
    if ($model->isNewRecord) {
    ?>

        <?= $form->field($model, 'student_id')->widget(DepDrop::classname(), [
            // 'data' => $state_data,
            'options' => [
                'id' => 'class-section-id',
                'multiple' => true
            ],
            'pluginOptions' => [
                'depends' => ['fee-structures-id'],
                // 'placeholder'=>'Select...',
                'url' => Url::to(['/admin/pay-fees/student-details-get-by-ft-id'])
            ]
        ]);
        ?>

    <?php } else { ?>

        <?php echo  $form->field($model, 'student_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

                ->orderBy('id')->asArray()->all(), 'id', 'student_name'),
            'options' => [
                'placeholder' => Yii::t('app', 'Choose Student details'),
                'multiple' => false,
                'disabled' => $model->isNewRecord ? false : true


            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>



    <?php } ?>




    <?= $form->field($model, 'remarks_of_pay_fee')->textInput(['placeholder' => 'Remarks', 'value' => !empty(Yii::$app->session->get('remarks_of_pay_fee')) ? Yii::$app->session->get('remarks_of_pay_fee') : $model->remarks_of_pay_fee])->label('Remarks') ?>




    <?= $form->field($model, 'fees_cut')->textInput(['placeholder' => 'Fees Deduction', 'readonly' => !empty(Yii::$app->session->get('fee_deduction')) ? true : false, 'value' => !empty(Yii::$app->session->get('fee_deduction')) ? Yii::$app->session->get('fee_deduction') : $model->fees_cut])->label('Fees Deduction') ?>



    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?> <?php
                                        $forms = [];
                                        echo kartik\tabs\TabsX::widget([
                                            'items' => $forms,
                                            'position' => kartik\tabs\TabsX::POS_ABOVE,
                                            'encodeLabels' => false,
                                            'pluginOptions' => [
                                                'bordered' => true,
                                                'sideways' => true,
                                                'enableCache' => false,
                                            ],
                                        ]);
                                        ?>
    <?php } ?> <div class="form-group">
        <?php

        if (!empty($loginUserFrom = Yii::$app->session->get('verify_otp')) && $loginUserFrom = Yii::$app->session->get('verify_otp') == 1) {

            if (Yii::$app->session->get('pay_fees_id') == $model->id) {
                echo   Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
            } else {
                echo  '<button  class="btn btn-success" type="button" id="deleteBtn" onClick="deleteData(' . $model->id . ')" >Send Otp</button>';
            }
        } else {

            echo  '<button  class="btn btn-success" type="button" id="deleteBtn" onClick="deleteData(' . $model->id . ')" >Send Otp</button>';
        }


        ?>


    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script>
    let base = "<?= Url::base(); ?>"

    function deleteData(id) {
        let campusId = "<?= (new User())->getCampusesByUser(Yii::$app->user->identity->id) ?>";
        let fee_deduction = $("#payfees-fees_cut").val();
        if (fee_deduction > 0) {

            let url = base + '/admin/pay-fees/assign-fee-details-delete-conform';
            let con = confirm("Do You Want to Send Otp");
            if (con == true) {
                $.ajax({
                    type: 'post',
                    url: url,
                    data: {
                        id: id,
                        campusId: campusId,

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
                            console.log(response.message)
                            $("#error").html(response.message)
                            swal("Sorry!", response.message, "error");


                        }
                    }
                })
            }


        } else {
            alert("Enter Valid Amount")
        }



    }





    $("#verifyOtpBrn").on("click", function() {
        let id = "<?= $model->id ?>";
        let pay_fees_otp = $("#payfees-otp").val()
        let sessionKey = $("#payfees-sessionkey").val();
        let payfeesDeleteDataId = $("#payfees-deletedataid").val();
        let urlDel = base + '/admin/pay-fees/verify-otp?id=' + id;
        let fee_deduction = $("#payfees-fees_cut").val();
        let remarks_of_pay_fee = $("#payfees-remarks_of_pay_fee").val();



        $.ajax({
            url: urlDel,
            type: 'post',
            data: {
                sessionKey: sessionKey,
                pay_fees_otp: pay_fees_otp,
                payfeesDeleteDataId: payfeesDeleteDataId,
                fee_deduction: fee_deduction,
                remarks_of_pay_fee: remarks_of_pay_fee

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
</script>