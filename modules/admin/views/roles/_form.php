<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Roles */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget([
    'viewFile' => '_script',
    'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'RoleHasPermissions',
        'relID' => 'role-has-permissions',
        'value' => \yii\helpers\Json::encode($model->roleHasPermissions),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="roles-form">

    <!-- Form starts -->
    <?php $form = ActiveForm::begin([
        'id' => 'login-form-inline',
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true,
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']],
        'formConfig' => ['showErrors' => true],
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><?= Html::encode(Yii::t('app', 'Role Details')) ?></h5>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>
            <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>
        </div>
    </div>

    <!-- Permissions Section in Table Format -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><?= Html::encode(Yii::t('app', 'Permissions')) ?></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Module</th>
                            <th>Create</th>
                            <th>Read</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Group permissions by module name and add space between capital letters
                        $groupedPermissions = [];
                        foreach ($permissions as $permission) {
                            if (preg_match('/(create|update|read|delete)([A-Z][a-zA-Z]+)/', $permission->name, $matches)) {
                                $action = $matches[1]; // 'create', 'update', etc.
                                $module = $matches[2]; // 'Notice', 'User', etc.
                                // Add space between consecutive capital letters in module name
                                $module = preg_replace('/([a-z])([A-Z])/', '$1 $2', $module);
                                $groupedPermissions[$module][$action] = $permission->name;
                            }
                        }
                        ?>

                        <?php foreach ($groupedPermissions as $moduleName => $modulePermissions): ?>
                            <tr>
                                <td><?= Html::encode($moduleName) ?></td>
                                <td>
                                    <?= isset($modulePermissions['create']) ? Html::checkbox('Permissions[]', in_array($modulePermissions['create'], $model->roleHasPermissions), ['value' => $modulePermissions['create']]) : '' ?>
                                </td>
                                <td>
                                    <?= isset($modulePermissions['read']) ? Html::checkbox('Permissions[]', in_array($modulePermissions['read'], $model->roleHasPermissions), ['value' => $modulePermissions['read']]) : '' ?>
                                </td>
                                <td>
                                    <?= isset($modulePermissions['update']) ? Html::checkbox('Permissions[]', in_array($modulePermissions['update'], $model->roleHasPermissions), ['value' => $modulePermissions['update']]) : '' ?>
                                </td>
                                <td>
                                    <?= isset($modulePermissions['delete']) ? Html::checkbox('Permissions[]', in_array($modulePermissions['delete'], $model->roleHasPermissions), ['value' => $modulePermissions['delete']]) : '' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>