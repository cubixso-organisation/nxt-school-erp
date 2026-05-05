<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\modules\childassessment\models\MeritsAssignedToClass */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="merits-assigned-to-class-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>

    <?= $form->errorSummary($model); ?>
    <div class="row grid-margin stretch-card">
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']);  ?> </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'academic_year_id')->widget(\kartik\widgets\Select2::classname(), [
                                                                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->where(['campus_id' => User::getCampusId(\Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'title'),
                                                                'options' => ['placeholder' => 'Choose Academic years'],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ]);  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
                                                                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->where(['campus_id' => User::getCampusId(\Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'title'),
                                                                'options' => ['placeholder' => Yii::t('app', 'Choose Class'), 'id' => 'class_id'],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ]);  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>
            <?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                // 'data' => \yii\helpers\ArrayHelper::map(SubCategories::find()->where(['id' => $model->sub_category_id])->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['id' => 'section_id'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true,  'multiple' => false, 'closeOnSelect' => true]],
                'pluginOptions' => [
                    'placeholder' => 'Select...',
                    'depends' => ['class_id'],
                    'url' => \yii\helpers\Url::to('get-sections'),
                ],
            ]);

            ?>
        </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'merit_id')->widget(\kartik\widgets\Select2::classname(), [
                                                                'data' => \yii\helpers\ArrayHelper::map(\app\modules\childassessment\models\ChildMerit::find()->where(['campus_id' => User::getCampusId(\Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'name'),
                                                                'options' => ['placeholder' => 'Choose Child merit'],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true
                                                                ],
                                                            ]);  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'status')->dropDownList($model->getStateOptions())  ?> </div>

    </div> <?php if ($model->isNewRecord) { ?><?php } ?>
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


    <?php ActiveForm::end(); ?>

</div>