<?php

use app\models\User;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JqueryAsset;

$this->registerAssetBundle(JqueryAsset::class);


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\ExamsResultSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$script = <<< JS
$(document).on('input', '#rangeInput', function() {
    $('#displayedValue').val($(this).val());
});
JS;
$this->registerJs($script);
?>

<div class="form-exams-result-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'your-form-id', // Replace with your actual form ID
    ]); ?>

    <?= $form->field($model, 'academic_year_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Academic years')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->andWhere(['is_agent' => null])
            ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Student class'), 'id' => 'student-class-id'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?php
    if (!$model->isNewRecord) {
        $section_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->andWhere(['student_class_id' => $model->student_class_id])
            ->orderBy('id')->asArray()->all(), 'id', 'section_name');
    } else {
        $section_data = [];
    }
    ?>
    <?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [
        'data' => $section_data,
        'options' => ['id' => 'class-section-id'],
        'pluginOptions' => [
            'depends' => ['student-class-id'],
            'placeholder' => 'Select...',
            'url' => Url::to(['/admin/fee-structures/class-section-data'])
        ]

    ]); ?>
    <?php
    isset($out) ? $out : $out = [];

    $form->field($model, 'subject_id')->widget(DepDrop::classname(), [
        'data' => $out,
        'options' => ['id' => 'subcat-id'],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => ['pluginOptions' => ['allowClear' => true,  'multiple' => false, 'closeOnSelect' => true]],
        'pluginOptions' => [
            'placeholder' => 'Select...',
            'depends' => ['class-section-id'],
            'url' => Url::toRoute(['exams-result/get-subjects']),
        ],
    ]); ?>


    <?= $form->field($model, 'exam_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Exams::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(), 'id', 'name_of_exam'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Exams')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>