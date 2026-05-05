<?php

use app\models\User;
use app\modules\admin\models\Exams;
use kartik\widgets\DepDrop;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\search\ExamStudentMarksheetSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-exam-student-marksheet-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>




    <div class="row">
        <div class="col-3">
            <?= $form->field($model, 'session_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => 'Choose Academic years'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-3">

            <?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Student class'), 'id' => 'class-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->Label('Class'); ?>
        </div>
        <div class="col-3">
<?php  isset($out) ? $out : $out = []; ?>
            <?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                'data' => $out,
                'options' => ['id' => 'section-id'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true,  'multiple' => false, 'closeOnSelect' => true]],
                'pluginOptions' => [
                    'placeholder' => 'Select...',
                    'depends' => ['class-id'],
                    'url' => \yii\helpers\Url::toroute(['exam-schedules/get-section']),
                ],
            ])->Label('Section'); ?>

        </div>
        <div class="col-3">
            <?php echo $form->field($model, 'exam_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Exams::find()->where(['campus_id' => (new User())->getCampusId()])->orderBy('id')->asArray()->all(), 'id', 'name_of_exam'),
                'options' => ['placeholder' => 'Choose Exams'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>








    <?php /* echo $form->field($model, 'total_marks')->textInput(['placeholder' => 'Total Marks']) */ ?>

    <?php /* echo $form->field($model, 'total_percentage')->textInput(['placeholder' => 'Total Percentage']) */ ?>

    <?php /* echo $form->field($model, 'marks_type')->textInput(['placeholder' => 'Marks Type']) */ ?>

    <?php /* echo $form->field($model, 'total_grade')->textInput(['maxlength' => true, 'placeholder' => 'Total Grade']) */ ?>

    <?php /* echo $form->field($model, 'total_cgpa')->textInput(['placeholder' => 'Total Cgpa']) */ ?>

    <?php /* echo $form->field($model, 'marksheet_url')->textarea(['rows' => 6]) */ ?>

    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton('Generate Marksheet', ['class' => 'btn btn-primary']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>