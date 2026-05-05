<?php

use app\modules\admin\models\base\SubjectTimetable;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\SubjectTimetableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-subject-timetable-search">

    <?php $form = ActiveForm::begin([
        'action' => ['teacher-time-table'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'teacher_details_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Teacher')],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]); ?>
        </div>

        <div class="col-md-4">
            <?php echo $form->field($model, 'day_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => (new SubjectTimetable)->getDaysWiseOptions(),
                'options' => ['placeholder' => Yii::t('app', 'Choose Day')],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>