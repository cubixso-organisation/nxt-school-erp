<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\FeesTyps */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget([
    'viewFile' => '_script', 'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'FeeStructures',
        'relID' => 'fee-structures',
        'value' => \yii\helpers\Json::encode($model->feeStructures),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="fees-typs-form">

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
        <div class="col-md-4">
            <?= $form->field($model, 'fees_type_name')->textInput(['maxlength' => true, 'placeholder' => 'Fees Type Name']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'academic_years_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(
                    \app\modules\admin\models\AcademicYears::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->all(),
                    'id',
                    'title'
                ),
                ['prompt' => 'Select Academic Year']
            ) ?>

        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'year_from')->textInput(['maxlength' => true, 'placeholder' => 'Year From', 'readonly' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'year_to')->textInput(['maxlength' => true, 'placeholder' => 'Year To', 'readonly' => true]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'months')->textInput(['maxlength' => true, 'placeholder' => 'Months']) ?>
        </div>


        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
        </div>

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
        <?php } ?>
        <div class="col-md-12">

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>

<div class="fees-typs-index">
    <div class="card">
        <div class="card-body">

            <?= $this->render('index_form', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>



        </div>
    </div>


</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        // Assuming "feestyps-academic_years_id" is the correct ID for your book_id field
        $("#feestyps-academic_years_id").on('change', function() {
            var academicYearId = $(this).val();

            // Use AJAX to fetch data based on the selected book_id
            $.ajax({
                url: 'get-data', // Replace with the actual endpoint URL
                type: 'GET',
                data: {
                    academic_id : academicYearId
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    var yearFrom = response.year_from;
                    var yearTo = response.year_to;
            

                    // Set the value of the "author" field
                    $("#feestyps-year_from").val(yearFrom);
                    $("#feestyps-year_to").val(yearTo);
                    
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    });
</script>