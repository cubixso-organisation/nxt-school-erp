<!-- Your form content -->
<?php

use app\models\User;
use app\modules\admin\models\base\BusDetails;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => 'assign-bus-form',
    'action' => Url::to(['assign-bus']),
]); ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'bus_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(BusDetails::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->orderBy('id')->asArray()->all(), 'id', 'title'),
            'options' => ['placeholder' => 'Choose Bus details', 'id' => 'bus-id'],
            'pluginOptions' => ['allowClear' => true],
        ])->label(false); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'bus_route_id')->widget(DepDrop::classname(), [
            'options' => ['id' => 'bus-route-id'],
            'pluginOptions' => [
                'depends' => ['bus-id'],
                'placeholder' => 'Select...',
                'url' => Url::to(['/admin/student-has-bus/bus-route-data']),
            ],
        ])->label(false); ?>
    </div>
</div>

<?= Html::hiddenInput('selected_students', '', ['id' => 'selected-students']); ?>

<div class="form-group">
    <?= Html::submitButton('Assign', ['class' => 'btn btn-primary']); ?>
</div>

<?php ActiveForm::end(); ?>