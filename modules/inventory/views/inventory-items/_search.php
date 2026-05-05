<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\search\InventoryItemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-inventory-items-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true, 'placeholder' => 'Item Name']) ?>

    <?= $form->field($model, 'item_category_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\inventory\models\ItemCategory::find()->orderBy('id')->asArray()->all(), 'id', 'item_category'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Item category')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    

    <?= $form->field($model, 'available_quantity')->textInput(['placeholder' => 'Available Quantity']) ?>

    <?php /* echo $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false, 
                ],
            ]) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
