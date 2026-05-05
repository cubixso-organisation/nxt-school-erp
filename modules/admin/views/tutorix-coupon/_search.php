<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\TutorixCouponSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-tutorix-coupon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'placeholder' => 'Code']) ?>

    <?= $form->field($model, 'coupon_type')->textInput(['placeholder' => 'Coupon Tye']) ?>

    <?= $form->field($model, 'coupon_discount')->textInput(['placeholder' => 'Coupon Discount']) ?>

    <?= $form->field($model, 'max_discount')->textInput(['placeholder' => 'Max Discount']) ?>

    <?php /* echo $form->field($model, 'min_cart_item')->textInput(['placeholder' => 'Min Cart Item']) */ ?>

    <?php /* echo $form->field($model, 'max_cart_item')->textInput(['placeholder' => 'Max Cart Item']) */ ?>

    <?php /* echo $form->field($model, 'min_cart_value')->textInput(['placeholder' => 'Min Cart Value']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
