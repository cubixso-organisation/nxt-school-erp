<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\search\HostellersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-hostellers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'student_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Student details')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Campus')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'hostel_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Hostels::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Hostels')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'joining_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Joining Date'),
                'autoclose' => true
            ]
        ],
    ]); ?>

    <?php /* echo $form->field($model, 'bill_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Bill Date'),
                'autoclose' => true
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'next_bill_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Next Bill Date'),
                'autoclose' => true
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'sty_type')->textInput(['placeholder' => 'Sty Type']) */ ?>

    <?php /* echo $form->field($model, 'advance_payment')->textInput(['placeholder' => 'Advance Payment']) */ ?>

    <?php /* echo $form->field($model, 'fees')->textInput(['placeholder' => 'Fees']) */ ?>

    <?php /* echo $form->field($model, 'room_id')->textInput(['placeholder' => 'Room']) */ ?>

    <?php /* echo $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address']) */ ?>

    <?php /* echo $form->field($model, 'aadhar_number')->textInput(['maxlength' => true, 'placeholder' => 'Aadhar Number']) */ ?>

    <?php /* echo $form->field($model, 'photo')->textInput(['maxlength' => true, 'placeholder' => 'Photo']) */ ?>

    <?php /* echo $form->field($model, 'aadhar_front')->textInput(['maxlength' => true, 'placeholder' => 'Aadhar Front']) */ ?>

    <?php /* echo $form->field($model, 'aadhar_back')->textInput(['maxlength' => true, 'placeholder' => 'Aadhar Back']) */ ?>

    <?php /* echo $form->field($model, 'application_form_file')->textInput(['maxlength' => true, 'placeholder' => 'Application Form File']) */ ?>

    <?php /* echo $form->field($model, 'leave_of_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Leave Of Date'),
                'autoclose' => true
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'leave_month')->textInput(['maxlength' => true, 'placeholder' => 'Leave Month']) */ ?>

    <?php /* echo $form->field($model, 'is_all_items_checked')->textInput(['placeholder' => 'Is All Items Checked']) */ ?>

    <?php /* echo $form->field($model, 'is_balance_amount_paid')->textInput(['placeholder' => 'Is Balance Amount Paid']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
