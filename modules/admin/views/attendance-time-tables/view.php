<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\SubjectTimetable;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AttendanceTimeTables */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attendance Time Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-time-tables-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Attendance Time Tables').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'attendanceSettings.title',
            'label' => Yii::t('app', 'Attendance Settings'),
        ],
   


        [
            'attribute' => 'subject_timetable_id',
            'format'=>'raw',
            'label' => Yii::t('app', 'Subject Timetable'),
            'value' => function($model){                   
                
                $day_id = '<b style="color:rgb(78, 54, 163)">'.$model->subjectTimetable->day_id.'</b>';

                $time_from = $model->subjectTimetable->time_from;
                $time_to = $model->subjectTimetable->time_to;


                $class_id = $model->subjectTimetable->class_id;
                $section_id = $model->subjectTimetable->section_id;
                $subject_id = $model->subjectTimetable->subject_id;
                $subjects = Subjects::find()->where(['id'=>$subject_id])->one();
                $student_class = StudentClass::find()->where(['id'=>$class_id])->one();
                $class_sections = ClassSections::find()->where(['id'=>$section_id])->one();

                $getTimeOfDay= SubjectTimetable::getTimeOfDay($time_from); 
                
                $time_table_details  = $subjects->subject_name.' '.$student_class->title.' '.$class_sections->section_name.' '.$day_id.' '.$getTimeOfDay.' '.$time_from.'-'.$time_to;
     
                return $time_table_details ;


            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectTimetable::find()->asArray()->all(), 'id', 'id'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Subject timetable', 'id' => 'grid-attendance-time-tables-search-subject_timetable_id']
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

