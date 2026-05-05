<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\modules\hostelmanagement\models\WardenToHostel */
/* @var $form yii\widgets\ActiveForm */

?>
<?php if (Yii::$app->session->hasFlash('error')) : ?>
    <div class="alert alert-danger">
        <?php echo Yii::$app->session->getFlash('error'); ?>
    </div>
<?php endif; ?>
<div class="warden-to-hostel-form">

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

    <?= $form->field($model, 'warden_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(User::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['user_role' => User::ROLE_WARDEN])->orderBy('id')->asArray()->all(), 'id', 'username'),
        'options' => ['placeholder' => Yii::t('app', 'Choose User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    <?= $form->field($model, 'hostel_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Hostels::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'User Type'), 'id' => 'hostel_id'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);  ?>

    <?php if ($model->isNewRecord) : ?>
        <?= $form->field($model, 'floor_id')->widget(DepDrop::classname(), [
            'options' => ['id' => 'floor_id'],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => [
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true,
                    'closeOnSelect' => true,
                ],
            ],
            'pluginOptions' => [
                'placeholder' => 'Select...',
                'depends' => ['hostel_id'],
                'url' => \yii\helpers\Url::to('get-floor'),
            ],
            'pluginEvents' => [
                'depdrop:afterChange' => new \yii\web\JsExpression('function(event, id, value) {
                if (value.length > 0) {
                    $(this).find("option[value=\'\']").remove();
                }
            }'),
            ],
        ]); ?>
    <?php else : ?>
        <?= $form->field($model, 'floor_id')->widget(DepDrop::classname(), [
            'options' => ['id' => 'floor_id'],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => [
                'pluginOptions' => [
                    'allowClear' => true,
                    'closeOnSelect' => true,
                ],
            ],
            'pluginOptions' => [
                'placeholder' => 'Select...',
                'depends' => ['hostel_id'],
                'url' => \yii\helpers\Url::to('get-floor'),
                'multiple' => false,
            ],
            'pluginEvents' => [
                'depdrop:afterChange' => new \yii\web\JsExpression('function(event, id, value) {
                if (value.length > 0) {
                    $(this).find("option[value=\'\']").remove();
                }
            }'),
            ],
        ]); ?>
    <?php endif; ?>



    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>