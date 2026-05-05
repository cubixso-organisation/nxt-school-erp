<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentClassAttendance */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Class Attendances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-class-attendance-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Student Class Attendance').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'student.id',
            'label' => Yii::t('app', 'Student'),
        ],
        [
            'attribute' => 'teacher.name',
            'label' => Yii::t('app', 'Teacher'),
        ],
        [
            'attribute' => 'subjectTimetable.id',
            'label' => Yii::t('app', 'Subject Timetable'),
        ],
        [
            'attribute' => 'academicYear.title',
            'label' => Yii::t('app', 'Academic Year'),
        ],
        [
            'attribute' => 'subjectGroup.id',
            'label' => Yii::t('app', 'Subject Group'),
        ],
        [
            'attribute' => 'subject.id',
            'label' => Yii::t('app', 'Subject'),
        ],
        'date',
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

