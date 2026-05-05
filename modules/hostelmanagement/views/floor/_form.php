<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\Floor */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget([
    'viewFile' => '_script', 'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'Rooms',
        'relID' => 'rooms',
        'value' => \yii\helpers\Json::encode($model->rooms),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="floor-form">

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

    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->user->identity->user_role == User::ROLE_ADMIN || Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getCampusId(), 'style' => 'display:none']);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) {
            // Your code for the Chef Warden condition goes here
            echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getUserCampusId(), 'style' => 'display:none']);
        }
    }
    ?>


    <?= $form->field($model, 'hostel_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Hostels::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Hostels')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'name_of_floor')->textInput(['maxlength' => true, 'placeholder' => 'Name Of Floor']) ?>

   


    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>