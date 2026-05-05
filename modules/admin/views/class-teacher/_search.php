<?php

use app\models\User;
use app\modules\admin\models\StudentClass;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\ClassTeacherSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-class-teacher-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                    ->where(['status' => StudentClass::STATUS_ACTIVE])
                    ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                    ->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Student class'), 'id' => 'student-class-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        </div>
        <?php
        if (!$model->isNewRecord) {
            $section_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])

                ->orderBy('id')->asArray()->all(), 'id', 'section_name');
        } else {
            $section_data = [];
        }


        ?>

        <div class="col-lg-6">
            <?=

            $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                'data' => $section_data,
                'options' => ['id' => 'class-section-id'],
                'pluginOptions' => [
                    'depends' => ['student-class-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['/admin/fee-structures/class-section-data'])
                ]
            ]);

            ?>
        </div>

    </div>


    <?php /* echo $form->field($model, 'status')->dropDownList($model->getStateOptions()) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>