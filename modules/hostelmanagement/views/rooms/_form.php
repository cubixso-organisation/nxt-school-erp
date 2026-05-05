<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\models\User;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\Rooms */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="rooms-form">

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
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'><?php
                                                            if (!Yii::$app->user->isGuest) {
                                                                if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN || Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                                                                    echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getCampusId(), 'style' => 'display:none']);
                                                                } elseif (Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
                                                                    // Your code for the Chef Warden condition goes here
                                                                    echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getUserCampusId(), 'style' => 'display:none']);
                                                                }
                                                            }
                                                            ?>

        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']);  ?> </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>
            <?= $form->field($model, 'hostel_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Hostels::find()->orderBy('id')->where([
                    'or',
                    ['campus_id' => User::getCampusId()],
                    ['campus_id' => User::getUserCampusId()],
                ])->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Hostels'), 'id' => 'hostel_id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);  ?>

        </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'>
            <?= $form->field($model, 'floor_id')->widget(DepDrop::classname(), [
                // 'data' => \yii\helpers\ArrayHelper::map(SubCategories::find()->where(['id' => $model->sub_category_id])->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['id' => 'floor_id'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true,  'multiple' => false, 'closeOnSelect' => true]],
                'pluginOptions' => [
                    'placeholder' => 'Select...',
                    'depends' => ['hostel_id'],
                    'url' => \yii\helpers\Url::to('get-floor'),
                ],
            ]);

            ?>
        </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'name_of_the_room')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Room'])  ?> </div>
        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'block')->textInput(['maxlength' => true, 'placeholder' => 'Block'])  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'no_of_beds')->textInput(['placeholder' => 'No Of Beds'])  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'type')->dropDownList($model->getRoomTypeOptions())  ?> </div>

        <div class='col-md-6 col-xl-6 col-sm-12 col-xs-12'> <?= $form->field($model, 'status')->dropDownList($model->getStateOptions())  ?> </div>

    </div> <?php if ($model->isNewRecord) { ?><?php } ?>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

</div>