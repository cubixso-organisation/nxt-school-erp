<?php

use yii\widgets\DetailView;

use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\Campus;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use app\modules\admin\models\StudentAttendanceBus;




use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\BusDetails */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bus Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


if(!empty($_GET['StudentAttendanceBusSearch']['created_on'])){
    $created_on = $_GET['StudentAttendanceBusSearch']['created_on'];


    $date_explode=explode(" - ",$created_on);
    $date1=trim($date_explode[0]);
    $convertedDate =   date("Y-m-d", strtotime($date1));
    $d1 = date($convertedDate. ' 00:00:00');
    $d2 = date($convertedDate. ' 23:59:59');
    $date2=trim($date_explode[1]);
 

     $StudentAttendanceBus = 
      StudentAttendanceBus::find()
    ->innerJoinWith(['busRoute'])
    ->innerJoinWith(['student.studentClass'])
    ->innerJoinWith(['student.section'])
    ->where(['bus_route.campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
    ->andWhere(['bus_route.bus_id'=>$busId])
    ->andFilterWhere(['between','student_attendance_bus.created_on',$d1,$d2])
    ->count();

    $StudentAttendanceBus_Present = 
    StudentAttendanceBus::find()
  ->innerJoinWith(['busRoute'])
  ->innerJoinWith(['student.studentClass'])
  ->innerJoinWith(['student.section'])
  ->where(['bus_route.campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
  ->andWhere(['bus_route.bus_id'=>$busId])
  ->andWhere(['student_attendance_bus.status'=>StudentAttendanceBus::STATUS_PRESENT])
  ->andFilterWhere(['between','student_attendance_bus.created_on',$d1,$d2])
  ->count();

  
  $StudentAttendanceBus_Absent = 
  StudentAttendanceBus::find()
->innerJoinWith(['busRoute'])
->innerJoinWith(['student.studentClass'])
->innerJoinWith(['student.section'])
->where(['bus_route.campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
->andWhere(['bus_route.bus_id'=>$busId])
->andWhere(['student_attendance_bus.status'=>StudentAttendanceBus::STATUS_ABSENT])
->andFilterWhere(['between','student_attendance_bus.created_on',$d1,$d2])
->count();



}


?>
<div class="bus-details-view">
<div class="card">
<div class="card-body">


<div class="row">
<div class="col-lg-3 col-6">
<div class="small-box bg-info">
<div class="inner">
<h3><?=  !empty($StudentAttendanceBus)?$StudentAttendanceBus:0 ?></h3>
<p>Total Students</p>
</div>
<div class="icon">
<i class="ion ion-bag"></i>
</div>
</div>
</div>

<div class="col-lg-3 col-6">

<div class="small-box bg-info">
<div class="inner">
<h3><?= !empty($StudentAttendanceBus_Present)?$StudentAttendanceBus_Present:0 ?></h3>
<p>Students present</p>
</div>
<div class="icon">
<i class="ion ion-bag"></i>
</div>
</div>
</div>


<div class="col-lg-3 col-6">

<div class="small-box bg-info">
<div class="inner">
<h3><?= !empty($StudentAttendanceBus_Absent)?$StudentAttendanceBus_Absent:0 ?></h3>
<p>Students Absent</p>
</div>
<div class="icon">
<i class="ion ion-bag"></i>
</div>
</div>
</div>




</div>


    
</div>
</div>

<div class="card">
       <div class="card-body">
<?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        ['attribute' => 'id', 'visible' => false],

        [
                'attribute' => 'bus_route_id',
                'label' => Yii::t('app', 'Bus Route'),
                'value' => function ($model) {
                    return $model->busRoute->point_name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusRoute::find()
                ->where(['bus_id'=>$busId])
                ->asArray()->all(), 'id', 'point_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Bus route', 'id' => 'grid-student-attendance-bus-search-bus_route_id']
            ],


            [
                'attribute' => 'status_direction',
                'format' => 'raw',
                'label' => Yii::t('app', 'Bus Route'),
                'value' => function ($model) {
                    return $model->busRoute->bus->getStateDirectionOptionsBadges();
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusRoute::find()
                ->where(['bus_id'=>$busId])
                ->asArray()->all(), 'id', 'point_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Bus route', 'id' => 'grid-student-attendance-bus-search-status_direction']
            ],


        [
            'attribute' => 'student_class_id',
            'label' => Yii::t('app', 'Student Class'),
            'value' => function ($model) {
                return $model->student->studentClass->title;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
            ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->asArray()->all(), 'id', 'title'),
            'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-search-student_class_id']
        ],


        [
            'attribute' => 'section_id',
            'label' => Yii::t('app', 'Student Section'),
            'value' => function ($model) {
                return $model->student->section->section_name;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
            ->andWhere(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->asArray()->all(), 'id', 'section_name'),
            'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-search-section_id']
        ],




        [
                'attribute' => 'student_id',
                'label' => Yii::t('app', 'Student'),
                'value' => function ($model) {
                    return $model->student->student_name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                    ->innerJoinWith('studentAttendanceBuses')
                    ->innerJoinWith('studentHasBuses')
                    ->where(['student_has_bus.bus_id'=>$busId])
                  ->asArray()->all(), 'id', 'student_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-student-attendance-bus-search-student_id']
            ],

        [
                'attribute' => 'created_on',
                'label' => Yii::t('app', 'Date'),
                'value' => function ($model) {
                    return $model->studentHasBus->created_on;
                },
                'filterType' => GridView::FILTER_DATE,
                'filterInputOptions' => ['placeholder' => 'Student has bus', 'id' => 'grid-student-attendance-bus-search-created_on']
            ],



        [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getStateOptionsBadges();
                },


            ],
        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view} {update} {delete}',
             'buttons' => [
            'view'=> function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                }
            },
            'update'=> function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                }
            },
            'delete'=> function ($url, $model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                    return Html::a('<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url, [
                        'data' => [
                                    'method' => 'post',
                                     // use it if you want to confirm the action
                                     'confirm' => 'Are you sure?',
                                 ],
                                ]);
                }
            },


        ]



        ],
    ];
?>
    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumn,
    'pjax' => true,
    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-attendance-bus']],
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
</div>
</div>



</div>
