<?php


use kartik\depdrop\DepDrop;
use yii\helpers\Url;

?>


    <?= $form->field($model, 'institute_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Institutes::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Institutes'),'id'=>'institute-id'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>


        <?php
        if ($model->isNewRecord) {
            $educational_institution_type_data=  [];
        } else {
            $educational_institution_type_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\EducationalInstitutionTypes::find()->orderBy('id')->asArray()->all(), 'id', 'title');
        }

?>





 
  
<?php
        if ($model->isNewRecord) {
            $campus_id_data= [];
        } else {
            $campus_id_data = \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'name_of_the_educational_Institution');
        }

?>
   

<?= $form->field($model, 'campus_id')->widget(DepDrop::classname(), [
'data' =>$campus_id_data ,
    'options'=>['id'=>'campus-id'],
    'pluginOptions'=>[
'depends'=>['institute-id'],
'placeholder'=>'Select...',
'url'=>Url::to(['/admin/campus/campus-data'])
    ]
]);

?>


<?= $form->field($model, 'educational_institution_type_id')->widget(DepDrop::classname(), [
'data' =>$educational_institution_type_data ,

'options'=>['id'=>'educational-institution-type-id'],
'pluginOptions'=>[
'depends'=>['campus-id'],
'placeholder'=>'Select...',
'url'=>Url::to(['/admin/campus/name-of-the-educational-institution'])
    ]
]);

?>


