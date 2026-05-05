<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\search\LibraryBooksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-library-books-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'book_title')->textInput(['maxlength' => true, 'placeholder' => 'Book Title']) ?>

    <?php /*echo $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false, 
                ],
            ]) */?>

    <?= $form->field($model, 'book_number')->textInput(['maxlength' => true, 'placeholder' => 'Book Number']) ?>

    <?= $form->field($model, 'isbn_number')->textInput(['maxlength' => true, 'placeholder' => 'Isbn Number']) ?>

    <?php /* echo $form->field($model, 'publisher')->textInput(['maxlength' => true, 'placeholder' => 'Publisher']) */ ?>

    <?php /* echo $form->field($model, 'author')->textInput(['maxlength' => true, 'placeholder' => 'Author']) */ ?>

    <?php /* echo $form->field($model, 'subject')->textInput(['maxlength' => true, 'placeholder' => 'Subject']) */ ?>

    <?php /* echo $form->field($model, 'rack_number')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\librarymanagement\models\LibraryRacks::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Library racks')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'qty')->textInput(['placeholder' => 'Qty']) */ ?>

    <?php /* echo $form->field($model, 'available')->textInput(['placeholder' => 'Available']) */ ?>

    <?php /* echo $form->field($model, 'book_price')->textInput(['maxlength' => true, 'placeholder' => 'Book Price']) */ ?>

    <?php /* echo $form->field($model, 'campus_id')->textInput(['placeholder' => 'Campus']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
