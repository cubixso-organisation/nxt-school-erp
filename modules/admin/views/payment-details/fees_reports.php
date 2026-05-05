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


?>






<div class="special-courses-index">
    <div class="card">
        <div class="card-body">


            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) { ?>
                    <?php Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
            </p>
            <div class="search-form">
                <?= $this->render('_student_search_fee_reports', ['model' => $searchModel]); ?>

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id', 'visible' => false],


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
                    'attribute' => 'admission_number',
                    'label' => Yii::t('app', 'admission number'),
                    'value' => function ($model) {
                        return isset($model->student->admission_number) ? $model->student->admission_number : '';
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                        ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->asArray()->all(), 'id', 'admission_number'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Admission number', 'id' => 'grid-student-details-search-admission_number']
                ],



//                'student_id',
[
    'attribute' => 'student_id',
    'label' => Yii::t('app', 'Student ID'),
    'value' => function ($model) {
        return $model->student_id;
    },
],


                [
                    'attribute' => 'student_id',
                    'label' => Yii::t('app', 'Student'),
                    'value' => function ($model) {
                        return isset($model->student->student_name) ? $model->student->student_name : '';
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



                [
                    'attribute' => 'name_of_the_father',
                    'label' => Yii::t('app', 'Parent Name'),
                    'value' => function ($model) {
                        return isset($model->student->parent->name_of_the_father) ? $model->student->parent->name_of_the_father : '';
                    },

                ],
                [
                    'attribute' => 'phone_number',
                    'label' => Yii::t('app', 'Phone Number'),
                    'value' => function ($model) {
                        return isset($model->student->parent->contact_number) ? $model->student->parent->contact_number : '';
                    },

                ],

                [
                    'attribute' => 'permanent_address',
                    'label' => Yii::t('app', 'Address'),
                    'value' => function ($model) {
                        return isset($model->student->parent->permanent_address) ? $model->student->parent->permanent_address : '';
                    },

                ],



                [
                    'attribute' => 'fee_structures_id',
                    'label' => Yii::t('app', 'fee structure'),
                    'value' => function ($model) {
                        return isset($model->feeStructures->title) ? $model->feeStructures->title : '';
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\FeeStructures::find()
                        ->andWhere(['campus_id' => User::getCampusId()])->andWhere(['status' => FeeStructures::STATUS_ACTIVE])
                        ->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Fee Structure', 'id' => 'grid-student-details-search-fee_structures_id']
                ],






                [
                    'attribute' => 'total_fee_amount',
                    'pageSummary' => true,
                    'label' => Yii::t('app', 'Structure Fee'),
                    'value' => function ($model) {
                        // Check if $model->student and $model->feeStructures are set before accessing their properties
                        $student_id = isset($model->student) ? $model->student->id : null;
                        $fee_structure_id = isset($model->feeStructures) ? $model->feeStructures->id : null;

                        if ($student_id && $fee_structure_id) {
                            // Proceed only if both student_id and fee_structure_id are valid
                            $total_fee = (new PaymentDetails())->getTotalFeeByStudentIdWithoutFeeCut($student_id, $fee_structure_id);
                            return $total_fee;
                        }

                        // Return null or a default value if either student_id or fee_structure_id is not set
                        return null;
                    }
                ],



                [
                    'attribute' => 'total_fee_amount',
                    'pageSummary' => true,
                    'label' => Yii::t('app', 'Total Amount'),
                    'value' => function ($model) {
                        $student_id = isset($model->student) ? $model->student->id : null;



                        $fee_structure_id = isset($model->feeStructures) ? $model->feeStructures->id : null;

                        if ($student_id && $fee_structure_id) {
                            // Proceed only if both student_id and fee_structure_id are valid
                            $total_fee = (new PaymentDetails())->getTotalFeeByStudentIdWithoutFeeCut($student_id, $fee_structure_id);
                            return $total_fee;
                        }

                        // Return null or a default value if either student_id or fee_structure_id is not set
                        return null;
                    }
                ],





                [
                    'attribute' => 'total_fee_amount',
                    'pageSummary' => true,
                    'label' => Yii::t('app', 'Fee Discount'),
                    'value' => function ($model) {
                        $student_id = isset($model->student) ? $model->student->id : null;



                        $fee_structure_id = isset($model->feeStructures) ? $model->feeStructures->id : null;

                        if ($student_id && $fee_structure_id) {
                            // Proceed only if both student_id and fee_structure_id are valid
                            $Discount = (new PaymentDetails())->getTotalFeeByStudentIdFeeDiscount($student_id, $fee_structure_id);
                            return $Discount;
                        }

                        // Return null or a default value if either student_id or fee_structure_id is not set
                        return null;
                    }
                ],

                [
                    'attribute' => 'paid_amount',
                    'pageSummary' => true,
                    'label' => Yii::t('app', 'Paid Amount'),
                    'value' => function ($model) {
                        // Ensure that student, class, and section are not null before accessing their properties
                        $student_id = isset($model->student) ? $model->student->id : null;
                        $class_id = isset($model->student->studentClass) ? $model->student->studentClass->id : null;
                        $section_id = isset($model->student->section) ? $model->student->section->id : null;
                        $pay_fees_id = $model->id;

                        // Check if student_id, class_id, and section_id are valid before calling the method
                        if ($student_id && $class_id && $section_id) {
                            // Return the paid amount if all IDs are valid
                            return (new PaymentDetails())->getPaidAmount($student_id, $class_id, $section_id, $pay_fees_id);
                        }

                        // Return null or a default value if any ID is missing
                        return null;
                    }
                ],





                [
                    'attribute' => 'balance',
                    'pageSummary' => true,
                    'label' => Yii::t('app', 'Total balance'),
                    'value' => function ($model) {
                        // Check if student, class, and section exist before accessing their properties
                        $student_id = isset($model->student) ? $model->student->id : null;
                        $class_id = isset($model->student->studentClass) ? $model->student->studentClass->id : null;
                        $section_id = isset($model->student->section) ? $model->student->section->id : null;
                        $pay_fees_id = $model->id;

                        if ($student_id && $class_id && $section_id) {
                            // Get the paid amount only if student, class, and section are valid
                            $paid = (new PaymentDetails())->getPaidAmount($student_id, $class_id, $section_id, $pay_fees_id);
                        } else {
                            $paid = 0; // Return 0 if any of the IDs are missing
                        }

                        // Fetch fee structure only if fee_structures_id is valid
                        $fee_structures = FeeStructures::find()->where(['id' => $model->fee_structures_id])->one();
                        if ($fee_structures) {
                            $fees_cut = $model->fees_cut;
                            $fee = $fee_structures->fee;
                            $studentPayAmount = $fee - $fees_cut;
                            return $studentPayAmount - $paid;
                        }

                        return null; // Return null or a default value if the fee structure is not found
                    }
                ],



                // [
                //     'attribute' => 'balance',
                //     'pageSummary' => true,
                //     'label' => Yii::t('app', 'Due Till Date'),
                //     'value' => function ($model) {
                //         $getTillDatePendingByPayFee =    PayFees::getTillDatePendingByPayFee($model->id);



                //         return $getTillDatePendingByPayFee;
                //     }
                // ],










            ];
            ?>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-payment-details']],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                ],
                'showPageSummary' => true,
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
<?php
