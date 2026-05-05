<?php

use app\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\LibraryBooks */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="library-books-form">

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
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_LIBRARIAN) {
            // Your code for the Chef Warden condition goes here
            echo $form->field($model, 'campus_id', ['template' => '{input}'])->textInput(['value' => User::getUserCampusId(), 'style' => 'display:none']);
        }
    }
    ?>

    <?= $form->field($model, 'book_title')->textInput(['maxlength' => true, 'placeholder' => 'Book Title']) ?>

    <?= $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(), [
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ],
    ]) ?>

    <?= $form->field($model, 'book_number')->textInput(['maxlength' => true, 'placeholder' => 'Book Number']) ?>

    <?= $form->field($model, 'isbn_number')->textInput(['maxlength' => true, 'placeholder' => 'Isbn Number']) ?>

    <?= $form->field($model, 'publisher')->textInput(['maxlength' => true, 'placeholder' => 'Publisher']) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true, 'placeholder' => 'Author']) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true, 'placeholder' => 'Subject']) ?>

    <?= $form->field($model, 'rack_number')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(
            \app\modules\librarymanagement\models\LibraryRacks::find()
                ->select(['id', 'CONCAT(rack_number, " - ", rack_location) AS full_rack_info'])
                ->where(['campus_id' => (new User())->getAllCampusId()])
                ->orderBy('id')
                ->asArray()
                ->all(),
            'id',
            'full_rack_info'
        ),
        'options' => ['placeholder' => Yii::t('app', 'Choose Library racks')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'qty')->textInput(['placeholder' => 'Qty']) ?>

    <?= $form->field($model, 'available')->textInput(['placeholder' => 'Available']) ?>

    <?= $form->field($model, 'book_price')->textInput(['maxlength' => true, 'placeholder' => 'Book Price']) ?>


    <?= $form->field($model, 'status')->widget(\kartik\widgets\Select2::classname(), [
        'data' => $model->getStateOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Choose Status'),
            'value' =>   $model->status,


        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumResultsForSearch' => -1,
        ],
    ]); ?>

    <?php if ($model->isNewRecord) { ?><?php } ?> <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>