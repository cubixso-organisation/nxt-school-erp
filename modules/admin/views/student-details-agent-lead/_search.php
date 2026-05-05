<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\StudentDetailsAgentLeadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-student-details-agent-lead-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Campus')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'agent_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
        'options' => ['placeholder' => Yii::t('app', 'Choose User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'profile_photo')->textInput(['maxlength' => true, 'placeholder' => 'Profile Photo']) ?>

    <?= $form->field($model, 'student_name')->textInput(['maxlength' => true, 'placeholder' => 'Student Name']) ?>

    <?php /* echo $form->field($model, 'gender')->textInput(['maxlength' => true, 'placeholder' => 'Gender']) */ ?>

    <?php /* echo $form->field($model, 'date_of_birth')->textInput(['maxlength' => true, 'placeholder' => 'Date Of Birth']) */ ?>

    <?php /* echo $form->field($model, 'name_of_the_parent')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Parent']) */ ?>

    <?php /* echo $form->field($model, 'phone_number')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number']) */ ?>

    <?php /* echo $form->field($model, 'verified_phone')->checkbox() */ ?>

    <?php /* echo $form->field($model, 'previous_school_name')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'previous_school_address')->textInput(['maxlength' => true, 'placeholder' => 'Previous School Address']) */ ?>

    <?php /* echo $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Student class')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'special_courses_id')->textInput(['placeholder' => 'Special Courses']) */ ?>

    <?php /* echo $form->field($model, 'section_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Class sections')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); */ ?>

    <?php /* echo $form->field($model, 'academic_year')->textInput(['maxlength' => true, 'placeholder' => 'Academic Year']) */ ?>

    <?php /* echo $form->field($model, 'hostal_is_required')->textInput(['placeholder' => 'Hostal Is Required']) */ ?>

    <?php /* echo $form->field($model, 'bus_transport_required')->textInput(['placeholder' => 'Bus Transport Required']) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
