<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\SpecialCoursesSearch */
// @var $dataProvider yii\data\ActiveDataProvider 

use app\modules\admin\models\base\StudentDetails;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\Campus;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\PayFees;
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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
                <?php } ?>
            </p>
            <div class="search-form">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <?php
            // Check if a search is applied
            if (isset($_GET['PayFeesSearch'])) {
                Yii::$app->session->set('redirect_url', $_SERVER['REDIRECT_QUERY_STRING']);

                // Calculate total balance and prepare WhatsApp message
                $studentPhone = $studentDetails->phone_number;
                $studentName = $studentDetails->student_name;
                $studentId = $studentDetails->id;
                $parent = $studentDetails->parent->name_of_the_father;
                $totalBalance = PayFees::find()->where(['student_id' => $studentId])->sum('balance_fee');
                $message = "Dear Mr. $parent We would like to inform you that the total outstanding balance for your child, " . $studentName . " is: ₹" . number_format($balanceAmount, 2) .  "Kindly arrange to settle the amount at the earliest, Thank you for your prompt attention to this matter";
                $whatsappLink = "https://wa.me/" . urlencode($studentPhone) . "?text=" . urlencode($message);
            ?>

                <div class="mb-3">
                    <a href="<?= $whatsappLink ?>" target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> Send WhatsApp Message
                    </a>
                </div>





                <?php
                $gridColumn = [
                    ['class' => 'yii\grid\SerialColumn'],

                    // ['attribute' => 'id', 'visible' => false],



                    // [
                    //     'attribute' => 'academic_year_id',
                    //     'pageSummary' => true,
                    //     'label' => Yii::t('app', 'Academic Year'),
                    //     'value' => function ($model) {
                    //         $student_id = $model->academicYear->title;
                    //         return $student_id;



                    //     }
                    // ],

                    // [
                    //     'attribute' => 'student_class_id',
                    //     'label' => Yii::t('app', 'Student Class'),
                    //     'value' => function ($model) {
                    //         return $model->student->studentClass->title;
                    //     },
                    //     'filterType' => GridView::FILTER_SELECT2,
                    //     'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                    //         ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    //         ->asArray()->all(), 'id', 'title'),
                    //     'filterWidgetOptions' => [
                    //         'pluginOptions' => ['allowClear' => true],
                    //     ],
                    //     'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-search-student_class_id']
                    // ],


                    // [
                    //     'attribute' => 'section_id',
                    //     'label' => Yii::t('app', 'Student Section'),
                    //     'value' => function ($model) {
                    //         return $model->student->section->section_name;
                    //     },
                    //     'filterType' => GridView::FILTER_SELECT2,
                    //     'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                    //         ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    //         ->asArray()->all(), 'id', 'section_name'),
                    //     'filterWidgetOptions' => [
                    //         'pluginOptions' => ['allowClear' => true],
                    //     ],
                    //     'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-search-section_id']
                    // ],




                    [
                        'attribute' => 'student_id',
                        'label' => Yii::t('app', 'Student'),
                        'value' => function ($model) {
                            return $model->student->student_name;
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                            ->asArray()->all(), 'id', 'student_name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-student-details-search-user_id'],
                        'contentOptions' => ['class' => 'frozen-column'], // Add this line to freeze the column
                    ],



                    [
                        'attribute' => 'fee_structures_id',
                        'label' => Yii::t('app', 'fee structure'),
                        'value' => function ($model) {
                            return $model->feeStructures->title . '-' . ($model->feeStructures->fee) . '/-';
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
                        'attribute' => 'total_fee',
                        'label' => Yii::t('app', 'Total Fee'),
                        'value' => function ($model) {
                            $student_id = $model->student->id;

                            return (new PaymentDetails())->getTotalFeeByStudentId($student_id, $model->fee_structures_id);
                        }
                    ],




                    [

                        'attribute' => 'balance',
                        'label' => Yii::t('app', 'balance'),
                        'value' => function ($model) {
                            $student_id = $model->student->id;
                            $class_id = $model->student->studentClass->id;
                            $section_id = $model->student->section->id;
                            $pay_fees_id = $model->id;


                            $paid = (new PaymentDetails())->getPaidAmount($student_id, $class_id, $section_id, $pay_fees_id, $model->academic_year_id);
                            $fee_structures = FeeStructures::find()->where(['id' => $model->fee_structures_id])->one();
                            $fees_cut = $model->fees_cut;
                            $fee = $fee_structures->fee;
                            $studentPayAmount = $fee - $fees_cut;
                            // var_dump($paid);exit;
                            return $balanceAmount = $studentPayAmount - $paid;
                        }
                    ],




                    [
                        'attribute' => 'fees_cut',
                        'label' => Yii::t('app', 'Fee Discount'),
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
                        'attribute' => 'created_on',
                        'label' => Yii::t('app', 'Payment Date'),
                        'format' => 'raw',
                        'value' => function ($model) {
                            // Get the current date in 'YYYY-MM-DD' format
                            $currentDate = date('Y-m-d');

                            // Check if the campus ID matches the specific campus ID that requires the date restriction
                            $restrictedCampusId = 67; // Replace with the campus ID that needs restriction
                            $userCampusIds = User::getCampusesByUser(Yii::$app->user->identity->id);

                            // Ensure $userCampusIds is an array
                            if (!is_array($userCampusIds)) {
                                $userCampusIds = [$userCampusIds];
                            }

                            if (in_array($restrictedCampusId, $userCampusIds)) {
                                // Restrict to only the current date
                                $htmlInput = '<input type="date" name="payment_date" id="payment_date_data' . $model->id . '"style="width:100px;" value="' . $currentDate . '" min="' . $currentDate . '" max="' . $currentDate . '">';
                            } else {
                                // Allow selecting any date
                                $htmlInput = '<input type="date" name="payment_date" id="payment_date_data' . $model->id . '"style="width:100px;" value="' . $currentDate . '">';
                            }

                            return $htmlInput;
                        },
                    ],





                    [
                        'attribute' => 'remarks',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<input type="text" name="remarks" id="remarks_data' . $model->id . '" style="width:100px;" value="' . htmlspecialchars($model->remarks, ENT_QUOTES, 'UTF-8') . '">';
                        },
                    ],



                    [
                        'attribute' => 'amount',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $htmlInput = '';
                            return $htmlInput .= '<input type="number" name="amount" id="amount_data' . $model->id . '"style="width:100px;" value="">';
                        },


                    ],


                    [
                        'attribute' => 'payNow',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $htmlInput = '';
                            return '<button id="rzp-button1" type="button" class="btn btn-success" onClick="payNow(' . $model->id . ',' . $model->student_id . ')" >Pay</button>';
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
                                    return Html::a('<button type="button" class="btn btn-primary btn-sm">Add Discount</button>
                    ', $url);
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

<div class="modal fade" id="paymentModel" tabindex="-1" role="dialog" aria-labelledby="paymentModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModelLabel">Phone Number to Send Payment Links</h5>
                <button type="button" id="closePaymentModel" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Phone Number</label>
                        <input type="text" id="contactNo" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Phone Number">
                    </div>

                    <button type="submit" id="paymentLinkButton" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


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
    $("#closePaymentModel").on("click", function() {
        $('#paymentModel').modal('hide');

    })



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

    //    Payments



    function generateToken(data) {
        return new Promise(function(resolve, reject) {
            var settings = {
                url: "https://api.estudent.tech/api/v1/token/generate",
                // url: "http://localhost:8080/api/v1/token/generate",
                method: "POST",
                timeout: 0,
                headers: {
                    "Content-Type": "application/json"
                },
                data: JSON.stringify(data), // Convert the data object to a JSON string
            };

            swal({
                title: 'Processing...',
                text: 'Generating token, please wait...',
                icon: 'info',
                buttons: false,
                closeOnClickOutside: false,
                closeOnEsc: false,
            });

            $.ajax(settings).done(function(response) {
                swal.close(); // Close the swal loader
                if (response.status === "NOK" || response.success == false) {
                    swal("Error!", response.message || 'Failed to generate token.', "error");
                    reject(response.message || 'Failed to generate token.');
                } else {
                    resolve(response);
                }
            }).fail(function(xhr, status, error) {
                swal.close();
                swal("Error!", error || 'An error occurred while generating the token.', "error");
                reject(error);
            });
        });
    }

    let paymentMode = '';

    $(document).on('change', 'select[id^=payment_mode_list]', function() {
        var id = $(this).attr('data-id');
        paymentMode = $(this).val();
    });

    function payNow(id, studentId = '') {
        baseUrl = "<?= Url::base($schema = true) ?>";
        let remarks = $("#remarks_data" + id).val();
        let amount = $("#amount_data" + id).val();
        let payment_date = $("#payment_date_data" + id).val();
        if (!amount) {
            swal("Error!", "Please add the amount", "error");
        } else {
            paymentMode = paymentMode;

            if (paymentMode == <?= PayFees::razor_pay ?>) {
                $('#paymentModel').modal('show');
                $('#paymentLinkButton').attr('onclick', "sendPaymentLink(" + id + "," + amount + "," + studentId + ")");
            } else {
                swal({
                    title: 'Processing...',
                    text: 'Processing your payment, please wait...',
                    icon: 'info',
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                });

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
                        swal.close();
                        let response = JSON.parse(res);

                        console.log(response.status)
                        if (response.status == 'ok') {
                            swal("Good job!", response.message, "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            swal("Sorry!", response.message || 'Payment failed.', "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.close();
                        swal("Error!", error || 'An error occurred during payment.', "error");
                    }
                });
            }
        }
    }

    function sendPaymentLink(id, amount, studentId) {
        let contact_no = $("#contactNo").val();
        if (!contact_no) {
            swal("Error!", "Please enter the contact number", "error");
        } else {
            generateToken({
                "user_id": 1,
                "user_role": "admin",
                "username": "admin@estudent.tech"
            }).then(function(tokenResponse) {
                if (tokenResponse.success == true) {
                    generatePaymentLink(tokenResponse.token, id, amount, studentId, contact_no);
                    console.log(tokenResponse);
                } else {
                    swal("Sorry!", tokenResponse.message, "error");
                }
            }).catch(function(error) {
                swal("Error!", error || 'Failed to send payment link.', "error");
            });
        }
    }

    function generatePaymentLink(token, payFeeId, amount, studentId, contact_no) {
        var settings = {
            // "url": "http://localhost:8080/api/v1/admin/generate-payment-link",
            "url": "https://api.estudent.tech/api/v1/admin/generate-payment-link",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "authorization": "bearer " + token,
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "student_id": studentId,
                "payment_id": payFeeId,
                "amount": amount,
                "contact_no": contact_no
            }),
        };

        swal({
            title: 'Processing...',
            text: 'Generating payment link, please wait...',
            icon: 'info',
            buttons: false,
            closeOnClickOutside: false,
            closeOnEsc: false,
        });

        $.ajax(settings).done(function(response) {
            swal.close();
            if (response.status === "NOK") {
                swal("Error!", response.message || 'Failed to generate payment link.', "error");
            } else {
                swal("Success!", "Payment link generated successfully.", "success");
                console.log(response);
            }
        }).fail(function(xhr, status, error) {
            swal.close();
            swal("Error!", error || 'An error occurred while generating the payment link.', "error");
        });
    }
</script>