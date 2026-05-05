<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\search\IssueReturnInventorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-issue-return-inventory-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'user_type')->textInput(['maxlength' => true, 'placeholder' => 'User Type']) ?>

    <?= $form->field($model, 'issue_to')->textInput(['maxlength' => true, 'placeholder' => 'Issue To']) ?>

    <?= $form->field($model, 'issue_by')->textInput(['maxlength' => true, 'placeholder' => 'Issue By']) ?>

    <?= $form->field($model, 'issue_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Issue Date'),
                'autoclose' => true
            ]
        ],
    ]); ?>

    <?php /* echo $form->field($model, 'return_date')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Return Date'),
                'autoclose' => true
            ]
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'note')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'item_category_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\inventory\models\ItemCategory::find()->orderBy('id')->asArray()->all(), 'id', 'item_category'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Item category')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'inventory_items_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\inventory\models\InventoryItems::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Inventory items')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'quantity')->textInput(['placeholder' => 'Quantity']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
