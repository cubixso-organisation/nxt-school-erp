<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentAttendanceBus */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Attendance Buses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-attendance-bus-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Student Attendance Bus').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'bus_route_id',
            'label' => Yii::t('app', 'Bus Route'),
            'value' => function($model){                   
                return $model->busRoute->point_name;                   
            },

        ],

        [
            'attribute' => 'student.student_name',
            'label' => Yii::t('app', 'Student'),
        ],
        [
            'attribute' => 'student_has_bus_id',
            'label' => Yii::t('app', 'Student Has Bus'),
            'value' => function($model){                   
                return $model->studentHasBus->bus->title;                   
            },
   
        ],
        'unique_key',
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

