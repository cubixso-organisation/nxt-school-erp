<?php

use app\models\User;
use app\modules\admin\models\AcademicYears;
use app\modules\admin\models\base\StudentClass;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\SubjectGroups;
use app\modules\admin\models\SubjectGroupsClassSections;
use app\modules\admin\models\SubjectGroupSubjects;
use app\modules\admin\models\Subjects;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\export\ExportMenu;
use app\modules\admin\models\base\Banner;
use kartik\grid\GridView;
 
 
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SubjectGroups */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'SubjectGroupSubjects',
        'relID' => 'subject-group-subjects',
        'value' => \yii\helpers\Json::encode($model->subjectGroupSubjects),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos' => \yii\web\View::POS_END,
    'viewParams' => [
        'class' => 'SubjectGroupsClassSections',
        'relID' => 'subject-groups-class-sections',
        'value' => \yii\helpers\Json::encode($model->subjectGroupsClassSections),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);

$id = Yii::$app->request->get('id');
if(!empty($id)) {
    $subject_groups_class_sections = SubjectGroupsClassSections::find()->where(['subject_group_id' => $id])->one();
    $class_sections_id = $subject_groups_class_sections->class_sections_id;
} else {
    $class_sections_id  = '';

}



?>
 



<div class="row">
<div class="col-lg-12 col-12">
    <?php 
    $id = User::getCampusesByUser(Yii::$app->user->identity->id);
    $campus = Campus::find()->where(['id'=>$id])->one();
    if(!empty($campus->academic_year)){
        $academic_year = !empty($campus->academicYear->title)?$campus->academicYear->title:'';
    }else{
        $academic_year ='';
    }

?> 
<h4><?php

if(!empty($academic_year)){
    echo $academic_year;
}else{
echo "Please Set Academic year";
}


?></h4>
</div>
    </div>

    
    <hr>





<div class="row">
<div class="col-md-4 col-sm-12 col-lg-4" >




<div class="subject-groups-form">
    <?php

if($model->isNewRecord) {

    $form_id = 'login-form-insert';
} else {
    $form_id = 'login-form-update';

}
 


    $form = ActiveForm::begin([
        'id' => $form_id,
        'type' => ActiveForm::TYPE_VERTICAL,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
        'formConfig' => ['showErrors' => true],
        // set style for proper tooltips error display
    ]); ?>
    <div class="row">

  
    <?= $form->errorSummary($model); ?>
  
    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>


    <div class="col-12">

<?= $form->field($model, 'academic_year_id')->widget(\kartik\widgets\Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
    ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
    ->orderBy('id')->asArray()->all(), 'id', 'title'),
    'options' => ['placeholder' => Yii::t('app', 'Choose Academic years')],
    'pluginOptions' => [
        'allowClear' => true
    ],
])->label('Academic Year'); ?>
</div>
<div class="col-12">
    <?= $form->field($model, 'subject_group_name')->textInput(['maxlength' => true, 'placeholder' => 'Subject Group Name']) ?>
    </div>
 


    <div class="col-12">
    <?php
    if($model->isNewRecord) {
        echo $form->field($model, 'class_sections_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(ClassSections::find()
            ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->orderBy('id')->asArray()->all(), 'id', function ($model) {
                $student_class_id  = $model['student_class_id'];
                $student_class = StudentClass::find()->where(['id' => $student_class_id])->one();

                return $model['section_name'].'-'.$student_class->title.'';
            }),
            'options' => ['placeholder' => Yii::t('app', 'Choose Class sections')],
            'pluginOptions' => [
                'allowClear' => true,
            ],

        ]);

    } else {

        $section  = ClassSections::find()->where(['id' => $class_sections_id])->one();
        echo $form->field($model, 'class_sections_id')->hiddenInput(['maxlength' => true, 'placeholder' => 'Class section Id','value' => $class_sections_id])->label(false);
        echo $form->field($model, 'class_sections_val')->textInput(['maxlength' => true, 'placeholder' => 'Class section Id','value' => $section->studentClass->title.'-'.$section->section_name,'readonly' => true])->label('Class And Section');



    }



?>



    </div>

 
    <div class="col-12">





<?php


$subjectList = Subjects::find()
->where(['status' => Subjects::STATUS_ACTIVE])->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
->orderBy('id')->asArray()->all();

?>

<label>Subjects:</label>
<br>
<?php
if($model->isNewRecord) {
    foreach($subjectList as $subjectList) { ?>
<?= $subjectList['subject_name'] ?> <input type="checkbox" name="SubjectGroups[subject_id][]" value="<?= $subjectList['id'] ?>" ><br>

<?php
    }
} else { ?>

<?php

foreach($subjectList as $subjectList) {

    $subject_group_subjects = SubjectGroupSubjects::find()->where(['subject_id' => $subjectList['id']])->andWhere(['subject_group_id' => $id])->andWHere(['status' => SubjectGroupSubjects::STATUS_ACTIVE])->one();

    ?>

<?php
if (is_object($subject_group_subjects) && $subject_group_subjects->subject_id == $subjectList['id']) {
    $checked = 'checked';
}
 else {
    $checked = '';
}

 echo  $subjectList['subject_name'] ?> <input type="checkbox" name="SubjectGroups[subject_id][]" value="<?= $subjectList['id'] ?>" <?= $checked ?> ><br>

<?php } ?>

<?php
}

?>





</div>





 
<div class="col-12">
<?= $form->field($model, 'description')->textarea(['maxlength' => false, 'placeholder' => 'Description']) ?>
</div>


<div class="col-12">
    <button type="button" onclick="<?= $model->isNewRecord ? "submitFormData()" : "submitFormDataUpdate()" ?>" class="btn btn-primary">Save</button>

    </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>



<div class="col-md-8 col-sm-12 col-lg-8">
<div class="box box-primary">
    <div class="box-header ptbnull">
        <h3 class="box-title titlefix"><?= Yii::t('app', 'Subject Group List') ?></h3>
        <div class="box-tools pull-right">
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive mailbox-messages" id="subject_list">



        <?php 

$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],

    ['attribute' => 'id', 'visible' => false],



    [
        'attribute' => 'academic_year_id',
        'label' => Yii::t('app', 'Academic Year'),
        'value' => function($model){                   
            return $model->academicYear->title;                   
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
        ->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->andWhere(['status'=>AcademicYears::STATUS_ACTIVE])
        ->asArray()->all(), 'id', 'title'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Academic years', 'id' => 'grid-subject-groups-search-academic_year_id']
    ],




   

    [
        'attribute' => 'subject_group_name',
        'label' => Yii::t('app', 'Subject Group'),
        'value' => function($model){                   
            return $model->subject_group_name;                   
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectGroups::find()
        ->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->andWhere(['status'=>AcademicYears::STATUS_ACTIVE])
        ->asArray()->all(), 'subject_group_name', 'subject_group_name'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Subject Group', 'id' => 'grid-subject-groups-search-subject_group_name']
    ],




       

    [
        'attribute' => 'sections',
        'label' => Yii::t('app', 'Sections'),
        'value' => function($model){                   
            foreach ($model->subjectGroupsClassSections as $group_section_key => $group_section_value) {
                $class_section  = $group_section_value->classSections->studentClass->title.'-'.$group_section_value->classSections->section_name;
                return $class_section;
            }              
        },
  
    ],








 

    [
        'attribute' => 'group_subject',
        'format' => 'html',
        'value' => function ($model) {
            $groupSubjectHtml = '<table width="100%">';
            foreach ($model->subjectGroupSubjects as $group_subject_key => $group_subject_value) {
                $groupSubjectHtml .= '<tr><td>' . Html::encode($group_subject_value->subject->subject_name) . '</td></tr>';
            }
            $groupSubjectHtml .= '</table>';
            return $groupSubjectHtml;
        },
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update} {delete}',
        'headerOptions' => ['class' => 'text-right no_print'],
        'contentOptions' => ['class' => 'text-right no_print'],
        'buttons' => [
            'update' => function ($url, $model, $key) {

                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url,[
                        'data-pjax' => 0
                    ]);

                }


            },
            'delete' => function ($url, $model, $key) {


                return Html::a('<span class="fas fa-trash-alt" onclick="deleteSubjectGroups('.$model->id.')" aria-hidden="true"></span>');

            },
        ],
    ],




];   


?>

          
     
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-subject-groups']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        'export' => false,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_PDF => false
                ]
            ]) ,
        ],
    ]); ?>

            
            
            
     

        </div><!-- /.mail-box-messages -->
    </div><!-- /.box-body -->
</div>




</div>


</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    let baseUrl = "<?= Url::base() ?>"
    function submitFormData(){
        var formData =    $('#login-form-insert').serialize()
      $.ajax({
        type:'post',
        url:baseUrl+'/admin/subject-groups/save-subjects-and-groups',
        data:formData,
        success:function(res){
            if(res.success){
                
                Swal.fire(res.message)

            }else{
                Swal.fire({
  icon: 'error',
  title: 'Oops...',
  text: res.message,

})
            }


        }

      })

    }
  


    function submitFormDataUpdate(){
        var formData =    $('#login-form-update').serialize()
      $.ajax({
        type:'post',
        url:baseUrl+'/admin/subject-groups/update-subjects-and-groups',
        data:formData,
        success:function(res){
            if(res.success){
                
                Swal.fire(res.message)

            }else{
                Swal.fire({
  icon: 'error',
  title: 'Oops...',
  text: res.message,

})
            }


        }

      })

    }

function deleteSubjectGroups(id){

      $.ajax({
        type:'post',
        url:baseUrl+'/admin/subject-groups/subject-group-delete',
        data:{
            "id":id
        },
        success:function(data){

            let res = JSON.parse(data)
            console.log(res.status)



            if(res.status=='ok'){
                
                Swal.fire(res.message)

            }else{
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: res.message,

})
            }


        }

      })
   
}
  

</script>

