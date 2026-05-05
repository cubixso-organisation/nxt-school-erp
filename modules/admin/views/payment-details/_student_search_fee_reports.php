<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\models\Campus;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;




/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\PayFeesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-pay-fees-search">

    <?php $form = ActiveForm::begin([
        'action' => ['fees-reports'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
    <div class="col-md-6 col-lg-6 col-sm-12">


        <?= $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

                ->orderBy('id')->asArray()->all(), 'id', 'title'),
            'options' => [
                'placeholder' => Yii::t('app', 'Choose Student class'),
                'id' => 'student-class-id',
                'disabled' => !$model->isNewRecord ? true : false
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Class'); ?>
    </div>


    <?php
    if (!$model->isNewRecord) {
        $class_section = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->orderBy('id')->asArray()->all(), 'id', 'section_name');
    } else {
        $class_section = [];
    }


    ?>

    <div class="col-md-6 col-lg-6 col-sm-12">



        <?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [
            'data' => $class_section,
            'options' => ['id' => 'class-section-id', 'disabled' => !$model->isNewRecord ? true : false],
            'pluginOptions' => [
                'depends' => ['student-class-id'],
                'placeholder' => 'Select...',
                'url' => Url::to(['/admin/fee-structures/class-section-data'])
            ]
        ])->label('Section');

        ?>
    </div>

    <?php
    if (!$model->isNewRecord) {
        $student_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->orderBy('id')->asArray()->all(), 'id', 'student_name');
    } else {
        $student_data = [];
    }


    ?>
    <div class="col-md-6 col-lg-6 col-sm-12">

        <?php $form->field($model, 'student_id')->widget(DepDrop::classname(), [
            'data' => $student_data,
            'options' => ['id' => 'student_id', 'multiple' => false, 'disabled' => !$model->isNewRecord ? true : false],
            'pluginOptions' => [
                'depends' => ['class-section-id'],
                'placeholder' => 'Select...',
                'url' => Url::to(['/admin/student-details/student-data-by-class-section'])
            ]
        ]);

        ?>
         <?= $form->field($model, 'academic_year')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
            ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

            ->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => [
            'placeholder' => Yii::t('app', 'Choose Student class'),
            'id' => 'student-academic-year',
            'disabled' => !$model->isNewRecord ? true : false
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Academic Year'); ?>
    </div>


   
</div>
<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>