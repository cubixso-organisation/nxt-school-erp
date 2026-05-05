<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentDetailsAgentLead */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'AgentStudentJoin', 
        'relID' => 'agent-student-join', 
        'value' => \yii\helpers\Json::encode($model->agentStudentJoins),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="student-details-agent-lead-form">

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

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true, 'placeholder' => 'Gender']) ?>

    <?= $form->field($model, 'date_of_birth')->textInput(['maxlength' => true, 'placeholder' => 'Date Of Birth']) ?>

    <?= $form->field($model, 'name_of_the_parent')->textInput(['maxlength' => true, 'placeholder' => 'Name Of The Parent']) ?>

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number']) ?>

    <?= $form->field($model, 'verified_phone')->checkbox() ?>

    <?= $form->field($model, 'previous_school_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'previous_school_address')->textInput(['maxlength' => true, 'placeholder' => 'Previous School Address']) ?>

    <?= $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Student class')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'special_courses_id')->textInput(['placeholder' => 'Special Courses']) ?>

    <?= $form->field($model, 'section_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Class sections')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'academic_year')->textInput(['maxlength' => true, 'placeholder' => 'Academic Year']) ?>

    <?= $form->field($model, 'hostal_is_required')->textInput(['placeholder' => 'Hostal Is Required']) ?>

    <?= $form->field($model, 'bus_transport_required')->textInput(['placeholder' => 'Bus Transport Required']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?>    <?php
    $forms = [
        [
            'label' => '<i class="fa fa-book"></i> ' . Html::encode(Yii::t('app', 'AgentStudentJoin')),
            'content' => $this->render('_formAgentStudentJoin', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->agentStudentJoins),
            ]),
        ],
    ];
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
<?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
