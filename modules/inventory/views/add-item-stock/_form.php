<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\file\FileInput as FileFileInput;
use kartik\widgets\FileInput;



/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\AddItemStock */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="add-item-stock-form">

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
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'item_supplier_list_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\inventory\models\ItemSupplierList::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Item supplier list')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'item_category_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\inventory\models\ItemCategory::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'item_category'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Item category')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'item_store_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\inventory\models\ItemStore::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'item_store_name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Item store')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'inventory_items_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\inventory\models\InventoryItems::find()->joinWith('itemCategory')->where(['item_category.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'item_name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Inventory items')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>
    <div class="row">

        <div class="col-md-6">
            <div id="current-stock"></div>

            <div class="form-group">
                <div class="input-group">

                    <div class="row">
                        <div class="col-3"> <?= $form->field($model, 'type')->dropDownList(['1' => '+', '2' => '-'], ['class' => 'form-control miplus']) ?></div>
                        <div class="col-9"> <?= $form->field($model, 'quantity')->textInput(['id' => 'quantity', 'class' => 'form-control miplusinput', 'placeholder' => 'Quantity']) ?>
                    
                        <div id="quantity-error" class="text-danger"></div>
                    
                    </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'purchase_price')->textInput(['maxlength' => true, 'placeholder' => 'Purchase Price']) ?>

        </div>
    </div>


</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'date')->widget(\kartik\datecontrol\DateControl::classname(), [
            'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
            'saveFormat' => 'php:Y-m-d',
            'ajaxConversion' => true,
            'options' => [
                'pluginOptions' => [
                    'placeholder' => Yii::t('app', 'Choose Date'),
                    'autoclose' => true
                ]
            ],
        ]); ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>


    </div>
</div>



<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'attach_document')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'previewFileType' => 'image',
                'initialPreview' => [$model->attach_document],
                'initialPreviewAsData' => true,
                'overwriteInitial' => true,
                'showUpload' => false,
            ],
        ]); ?>
    </div>
    <div class="col-md-6">

        <?= $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(), [
            'editorOptions' => [
                'preset' => 'small',
                'inline' => false,
            ],
        ]) ?>
    </div>
</div>









<?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
<!-- Include Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-zmQyrN8WekAtJh9t3HA6y7zVKyjz1uR4vTziXu8CPx+8j70cF3G80et7wJUtntZHg+4JbcMZSUnMhd+F6f8c0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        var response; // Declare the response variable outside the AJAX success callback

        $('#additemstock-inventory_items_id').on('change', function() {
            var selectedValue = $(this).val();

            if (selectedValue !== '') {
                // Show a loading spinner while waiting for the AJAX response
                $('#current-stock').html('<i class="fas fa-spinner fa-spin"></i>');

                // Make an AJAX call to fetch available stock
                $.ajax({
                    type: "GET",
                    url: "get-available-stock", // Adjust the URL based on your project structure
                    data: {
                        item_id: selectedValue
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        response = data; // Assign the data to the response variable

                        // Check if the response has the expected properties
                        if (response && response.total_quantity !== undefined && response.quantity_left !== undefined) {
                            // Update the current-stock div with the received data
                            $('#current-stock').html('<b>Total Stock: </b>' + response.total_quantity + ', <b>Stock Left: </b>' + response.quantity_left);
                        } else {
                            // Handle the case where the response format is unexpected
                            $('#current-stock').html('<b>Error:</b> Unexpected response format');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching available stock:', error);
                        // Display an error message if the request fails
                        $('#current-stock').html('<b>Error:</b> Unable to fetch stock data');
                    }
                });
            }
        });

        // Add an event listener to the quantity input for real-time validation
        $('#quantity').on('input', function() {
            // Clear the error message whenever the user modifies the quantity
            $('#quantity-error').html('');
            
            // Check if the response object and its properties exist before using them
            if (response && response.total_quantity !== undefined) {
                var selectedType = $('#additemstock-type').val(); // Replace 'additemstock-type' with the actual ID of the type field
                var enteredQuantity = parseInt($(this).val(), 10);

                if (selectedType === '2' && enteredQuantity > response.total_quantity) {
                    $('#quantity-error').html('You cannot remove more than total stock');
                }
            }
        });

        // Add an event listener to the selectedType input for real-time validation
        $('#additemstock-type').on('change', function() {
            // Clear the error message whenever the user changes the selectedType
            $('#quantity-error').html('');
            
            // Check if the response object and its properties exist before using them
            if (response && response.total_quantity !== undefined) {
                var selectedType = $(this).val(); // Replace 'additemstock-type' with the actual ID of the type field
                var enteredQuantity = parseInt($('#quantity').val(), 10);

                if (selectedType === '2' && enteredQuantity > response.total_quantity) {
                    $('#quantity-error').html('You cannot remove more than total stock');
                }
            }
        });
    });
</script>
