<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\IssueReturnInventory */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="issue-return-inventory-form">

    <?php $form = ActiveForm::begin([
        'id' => 'issueform',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
    <?= $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getCampusId(), 'style' => 'display:none']); ?>
    <div class="row">
        <div class="col-md-4">

            <?= $form->field($model, 'user_type')->widget(\kartik\widgets\Select2::classname(), [
                'data' => $model->getUserTypeInventory(),
                'options' => ['placeholder' => Yii::t('app', 'User Type'), 'id' => 'user_type'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);  ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'issue_to')->widget(DepDrop::classname(), [
                'options' => ['id' => 'issue-to'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true,  'multiple' => false, 'closeOnSelect' => true]],
                'pluginOptions' => [
                    'placeholder' => 'Select...',
                    'depends' => ['user_type'],
                    'url' => \yii\helpers\Url::to('get-user'),
                ],
            ]);

            ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'issue_by')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(
                    User::find()
                        ->where([
                            'or',
                            ['user_role' => User::ROLE_ADMIN],
                            ['user_role' => User::role_campus_sub_admin]
                        ])
                        ->andWhere(['campus_id' => $model->campus_id])
                        ->all(),
                    'id',
                    'user_role'
                ),
                'options' => ['placeholder' => Yii::t('app', 'Issued By')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>


        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'item_category_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(
                    \app\modules\inventory\models\ItemCategory::find()
                        ->joinWith('inventoryItems') // Use the correct relation name here
                        ->andWhere(['>', 'inventory_items.available_quantity', 0])
                        ->andWhere(['campus_id' => \app\models\User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->orderBy('item_category.id')
                        ->asArray()
                        ->all(),
                    'id',
                    'item_category'
                ),

                'options' => ['placeholder' => Yii::t('app', 'Choose Item category'), 'id' => 'item_category_id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>




        </div>
        <div class="col-md-4">

            <?= $form->field($model, 'inventory_items_id')->widget(DepDrop::classname(), [
                'options' => ['id' => 'inventory-id'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true,  'multiple' => false, 'closeOnSelect' => true]],
                'pluginOptions' => [
                    'placeholder' => 'Select...',
                    'depends' => ['item_category_id'],
                    'url' => \yii\helpers\Url::to('get-inventory-items'),
                ],
            ]);

            ?>

        </div>
        <div class="col-md-4">
            <div id="current-stock"></div>
            <?= $form->field($model, 'quantity')->textInput(['placeholder' => 'Quantity']) ?>
            <div id="quantity-error" class="text-danger"></div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
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

        </div>
        <div class="col-md-4">

            <!-- <?= $form->field($model, 'expected_return_date')->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                'saveFormat' => 'php:Y-m-d',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Expected Return Date'),
                        'autoclose' => true
                    ]
                ],
            ]); ?> -->
            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>


        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'note')->textarea(['rows' => 1]) ?>


        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-6">

    </div>
</div>


<?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $("#issuereturninventory-item_category_id").on('change', function() {
            var selectedMemberId = $(this).val();
            // console.log(selectedMemberId);
            // Use AJAX to fetch data based on the selected Category_id
            $.ajax({
                url: 'get-member-data',
                type: 'GET',
                data: {
                    item_category_id: selectedMemberId
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response);

                    var InventoryItems = response.item_name;


                    // Set the value of the "InventoryItem" field
                    $("#issuereturninventory-inventory_items_id").val(InventoryItems);


                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    });
</script>




<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-zmQyrN8WekAtJh9t3HA6y7zVKyjz1uR4vTziXu8CPx+8j70cF3G80et7wJUtntZHg+4JbcMZSUnMhd+F6f8c0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<?php $fullUrl = Url::to(['/'], true);
$url = $fullUrl . "admin/inventory/add-item-stock/get-available-stock";
?>

<script>
    $(document).ready(function() {
        var response;
        var hasError = false;

        function fetchStockData(selectedValue) {
            hasError = false;
            toggleSubmitButton(hasError);

            if (selectedValue !== '') {
                $('#current-stock').html('<i class="fas fa-spinner fa-spin"></i>');

                // Make use of the promise returned by $.ajax
                $.ajax({
                    type: "GET",
                    url: "<?= $url ?>",
                    data: {
                        item_id: selectedValue
                    },
                    dataType: 'json'
                }).done(function(data) {
                    console.log(data);
                    response = data;

                    if (response && response.total_quantity !== undefined && response.quantity_left !== undefined) {
                        $('#current-stock').html('<b>Stock Left: </b>' + response.quantity_left);
                    } else {
                        $('#current-stock').html('<b>Error:</b> Unexpected response format');
                    }

                    handleInputEvent(); // Call handleInputEvent after the AJAX request is completed
                }).fail(function(error) {
                    console.error('Error fetching available stock:', error);
                    hasError = true;
                    $('#current-stock').html('<b>Error:</b> Unable to fetch stock data');
                    toggleSubmitButton(hasError);
                });
            }
        }

        $('#inventory-id').on('change', function() {
            fetchStockData($(this).val());
        });

        $('#issuereturninventory-quantity').on('input', function() {
            // Directly call fetchStockData instead of using a timeout
            fetchStockData($('#inventory-id').val());
        });

        function handleInputEvent() {
            $('#quantity-error').html('');
            if (response && response.total_quantity !== undefined && !hasError) {
                var enteredQuantity = parseInt($('#issuereturninventory-quantity').val(), 10);

                if (!isNaN(enteredQuantity)) {
                    console.log(enteredQuantity);

                    if (enteredQuantity > response.total_quantity) {
                        hasError = true;
                        $('#quantity-error').html('Quantity cannot be more than left stock');
                    } else {
                        hasError = false;
                    }

                    toggleSubmitButton(hasError);
                } else {
                    hasError = true;
                    $('#quantity-error').html('Please enter a valid quantity');
                    toggleSubmitButton(hasError);
                }
            }
        }

        function toggleSubmitButton(hasError) {
            console.log(hasError);
            if (hasError) {
                $(':input[type="submit"]').prop('disabled', true);
            } else {
                $(':input[type="submit"]').prop('disabled', false);
            }
        }
    });
</script>