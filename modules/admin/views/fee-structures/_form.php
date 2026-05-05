<?php

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\FeeStructures;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;


?>

<div class="fee-structures-form">

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
            <?= $form->field($model, 'title')->textInput(['placeholder' => 'Title'])->label('Title') ?>
        </div>

        <div class="col-md-4">


            <?= $form->field($model, 'fee_type_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\FeesTyps::find()
                    ->orderBy('id')
                    ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    ->andWhere(['status' => FeeStructures::STATUS_ACTIVE])
                    ->asArray()->all(), 'id', 'fees_type_name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Fees Type')],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => !$model->isNewRecord ? true : false

                ],
            ]); ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                    ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])
                    ->andWhere(['is_agent' => null])
                    ->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose Student class'),
                    'id' => 'student-class-id',
                    'multiple' => true, // Enable multiple selection
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'selectOnClose' => true,
                ],
            ]); ?>
        </div>


        <div class="col-md-4">
            <?= $form->field($model, 'class_section_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => [],
                'options' => [
                    'placeholder' => 'Select section...',
                    'id' => 'class-section-id',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
        </div>






        <div class="col-md-4">



            <?= $form->field($model, 'fee')->textInput(['placeholder' => 'Fee']) ?>
        </div>
        <div class="col-md-4">


            <?= $form->field($model, 'maximum_detuction')->textInput(['placeholder' => 'Maximum Detuction']) ?>
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


    <div class="row">

        <?= $this->render('index_form', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]); ?>


    </div>


</div>

<?php
$this->registerJs("
    $('#student-class-id').on('change', function() {
        var classIds = $(this).val();
        if (classIds.length > 0) {
            $.ajax({
                url: '" . Url::to(['/admin/fee-structures/class-section-data-fee']) . "',
                type: 'POST',
                data: {class_ids: classIds},
                success: function(data) {
                    var sectionSelect = $('#class-section-id');
                    sectionSelect.empty();
                    if (data.length > 0) {
                        $.each(data, function(index, item) {
                            sectionSelect.append(new Option(item.name, item.id));
                        });
                    }
                    sectionSelect.trigger('change'); // Update Select2
                }
            });
        } else {
            $('#class-section-id').empty().trigger('change');
        }
    });
");
?>
