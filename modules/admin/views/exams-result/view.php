<?php
   use yii\helpers\Html;
   use yii\widgets\DetailView;
   use kartik\grid\GridView;
   use app\models\User;
use app\modules\admin\models\Exams;

   /* @var $this yii\web\View */
   /* @var $model app\modules\admin\models\ExamsResult */
   
   $this->title = $model->exams_result_id;
   $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Exams Results'), 'url' => ['index']];
   $this->params['breadcrumbs'][] = $this->title;
   ?>
<div class="exams-result-view">
   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-sm-9">
               <h2><?= Yii::t('app', 'Exams Result').' '. Html::encode($this->title) ?></h2>
            </div>
            <div class="col-sm-3" style="margin-top: 15px">
               <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->exams_result_id], ['class' => 'btn btn-primary']) ?>
               <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUBADMIN){ ?>
               <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->exams_result_id], [
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
                   'exams_result_id',
                   [
                       'attribute' => 'campus.id',
                       'label' => Yii::t('app', 'Campus'),
                   ],
                   [
                       'attribute' => 'exam.id',
                       'label' => Yii::t('app', 'Exam'),
                   ],
                   [
                       'attribute' => 'academicYear.title',
                       'label' => Yii::t('app', 'Academic Year'),
                   ],
                   [
                       'attribute' => 'student.id',
                       'label' => Yii::t('app', 'Student'),
                   ],
                   [
                       'attribute' => 'class.title',
                       'label' => Yii::t('app', 'Class'),
                   ],
                   [
                       'attribute' => 'section.id',
                       'label' => Yii::t('app', 'Section'),
                   ],
                   [
                    'attribute' => 'percentage_or_gpa',
                    'label' => 'percentage or gpa',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->exam->marks_type==Exams::marks_type_gpa){
                            return $model->percentage_or_gpa.' GPA' ;
        
                        }else{
                            return $model->percentage_or_gpa.' %' ;
        
                        }
        
        
                    },
                ],
                
               
              
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