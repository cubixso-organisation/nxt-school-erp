<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ParentDetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parent Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parent-details-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Parent Details').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                          <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUBADMIN){ ?>
             <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) 
            ?>  
             <?php  } ?>
        </div>
    </div>
    </div>
    </div>
    <div class="card">
       <div class="card-body">

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'user.username',
            'label' => Yii::t('app', 'User'),
        ],
        'name_of_the_father',
        'name_of_the_mother',
        'current_address:ntext',
        'permanent_address:ntext',
        'contact_number',
        'father_education_qualification:ntext',
        'mother_education_qualification:ntext',
        'father_aadhaar_number',
        'mother_aadhaar_number',
        'father_occupation',
        'mother_occupation',
    


        
        [


     


                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){                   
                    return $model->getStateOptionsBadges();                   
                },
               
               
            ],


    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
</div>
</div>
    </div>


</div>









<div class="card">
       <div class="card-body">
    <div class="row">
<?php
if($providerStudentDetails->totalCount){
    $gridColumnStudentDetails = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'campus.id',
                'label' => Yii::t('app', 'Campus')
            ],
            [
                'attribute' => 'user.username',
                'label' => Yii::t('app', 'User')
            ],
                        'admission_number',
            'rool_number',
       

            [
                'attribute' => 'profile_photo', // Replace 'image' with your actual attribute name
                'format' => 'html', // Set the format to 'html' to render HTML content
                'value' => function ($model) {
                    return Html::img($model->profile_photo, ['width' => '100']);
                },
            ],


            'student_name',
            'gender',
            'date_of_birth',
            'category',
            'religion',
            'caste',
            'phone_number',
            [
                'attribute' => 'studentClass.title',
                'label' => Yii::t('app', 'Student Class')
            ],
            [
                'attribute' => 'section.id',
                'label' => Yii::t('app', 'Section')
            ],
            'academic_year',
            [
                'attribute' => 'academicYear.title',
                'label' => Yii::t('app', 'Academic Year')
            ],
            'hostal_is_required',
            'bus_transport_required',
            'email:email',
            'admission_date',
            [
                'attribute' => 'bloodGroup.title',
                'label' => Yii::t('app', 'Blood Group')
            ],
            'student_house',
            'height',
            'weight',
            'current_address:ntext',
            'permanent_address:ntext',
            'national_Identification_number',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){                   
                    return $model->getStateOptionsBadges();                   
                },
               
               
            ],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerStudentDetails,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-details']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Student Details')),
        ],
        'export' => false,
        'columns' => $gridColumnStudentDetails
    ]);
}

?>
</div>
</div>
</div>


