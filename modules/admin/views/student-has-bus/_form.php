<?php

use app\modules\admin\models\Campus;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentHasBus;
use app\modules\admin\models\User;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentHasBus */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget([
    'viewFile' => '_script', 'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'StudentAttendanceBus',
        'relID' => 'student-attendance-bus',
        'value' => \yii\helpers\Json::encode($model->studentAttendanceBuses),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="student-has-bus-form">

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
    <div class="row">



        <div class="col-md-4">


            <?php

            if ($model->isNewRecord) {
            ?>

                <?= $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                        ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])

                        ->orderBy('id')->asArray()->all(), 'id', 'title'),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Choose Student class'),
                        'id' => 'student-class-id',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Student Class'); ?>
        </div>

        <div class="col-md-4">

            <?= $form->field($model, 'class_section_id')->widget(DepDrop::classname(), [
                    // 'data' => $state_data,
                    'options' => ['id' => 'class-section-id'],
                    'pluginOptions' => [
                        'depends' => ['student-class-id'],
                        'placeholder' => 'Select...',
                        'url' => Url::to(['/admin/fee-structures/class-section-data'])
                    ]
                ])->label('Class Section');

            ?>
        <?php } ?>
        </div>


        <div class="col-md-4">


            <?php
            if (!$model->isNewRecord) {
                $student_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                    ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])
                    ->andWhere(['id' => $model->student_id])
                    ->orderBy('id')->asArray()->all(), 'id', 'student_name');
            } else {
                $student_data = [];
            }



            ?>



            <?= $form->field($model, 'student_id')->widget(DepDrop::classname(), [
                'data' => $student_data,
                'options' => ['id' => 'student_id', 'multiple' => $model->isNewRecord ? true : false],
                'pluginOptions' => [
                    'depends' => ['class-section-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['/admin/student-details/student-data-by-class-section-by-bus'])
                ]
            ]);

            ?>
        </div>


        <div class="col-md-4">


            <?= $form->field($model, 'bus_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusDetails::find()
                    ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])
                    ->orderBy('id')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Bus details'), 'id' => 'bus-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>





        <?php
        if (!$model->isNewRecord) {
            $bus_root_data =  \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusRoute::find()
                ->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['bus_id' => $model->bus_id])

                ->orderBy('id')->asArray()->all(), 'id', 'point_name');
        } else {
            $bus_root_data = [];
        }


        ?>


        <div class="col-md-4">


            <?= $form->field($model, 'bus_route_id')->widget(DepDrop::classname(), [
                'data' => $bus_root_data,
                'options' => ['id' => 'bus-route-id'],
                'pluginOptions' => [
                    'depends' => ['bus-id'],
                    'placeholder' => 'Select...',
                    'url' => Url::to(['/admin/student-has-bus/bus-route-data'])
                ]
            ]); ?>


        </div>

        <div class="col-md-4">

            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
        </div>

        <?php if ($model->isNewRecord) { ?> <?php
                                            $forms = [];
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
        <?php } ?>
        <div class="col-md-12">

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

    </div>




    <?php ActiveForm::end(); ?>

</div>