<?php

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\CampusHasUsers */
/* @var $form yii\widgets\ActiveForm */

?> 
 
<div class="campus-has-users-form">

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

    <?= $form->field($model, 'user_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()
        // ->where(['NOT IN','user_role',[User::ROLE_ADMIN,User::ROLE_CAMPUS_ADMIN]])
        ->where(['user_role'=>User::ROLE_BUS_COORDINATOR])
        ->orderBy('id')
        ->andWhere(['create_user_id'=>\Yii::$app->user->identity->id])
        ->asArray()->all(), 'id', 'username'),
        'options' => ['placeholder' => Yii::t('app', 'Choose User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>





    <?php

$institutes_id = (new Institutes())->getInstituteIdOfUser();
    
if(!empty($institutes_id)){
    $campus = Campus::find()->where(['institute_id'=>$institutes_id])->all();
    foreach($campus as $campusIdData){
        $campusIds[] = $campusIdData->id;
    }
  

}

    
    
  echo   $form->field($model, 'campus_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()
        ->where(['in','id',$campusIds])
        ->orderBy('id')->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Campus'),'prompt'=>'Select College or School'],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple'=>true
        ],
    ]); ?>







    <?= $form->field($model, 'status')->dropDownList($model->getStateOptions()) ?>

<?php if($model->isNewRecord){ ?><?php } ?>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
