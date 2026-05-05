<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\StudentClassAttendanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\SubjectTimetable;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Student Class Attendances');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="student-class-attendance-index">

    <div class="card">
       <div class="card-body">
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
        ['attribute' => 'id', 'visible' => false],
   
        [ 
                'attribute' => 'student_id',
                'label' => Yii::t('app', 'Student'),
                'value' => function($model){                   
                    return $model->student->student_name;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-student-class-attendance-search-student_id']
            ],
   
        [
                'attribute' => 'teacher_id',
                'label' => Yii::t('app', 'Teacher'),
                'value' => function($model){                   
                    return isset( $model->teacher->name) ? $model->teacher->name:'N/A' ;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()->asArray()->all(), 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Teacher details', 'id' => 'grid-student-class-attendance-search-teacher_id']
            ],
   
        [
                'attribute' => 'subject_timetable_id',
                'format' => 'raw',

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
                'filterInputOptions' => ['placeholder' => 'Subject timetable', 'id' => 'grid-student-class-attendance-search-subject_timetable_id']
            ],
   
        [
                'attribute' => 'academic_year_id',
                'label' => Yii::t('app', 'Academic Year'),
                'value' => function($model){                   
                    return $model->academicYear->title;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->asArray()->all(), 'id', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Academic years', 'id' => 'grid-student-class-attendance-search-academic_year_id']
            ],
   
        [
                'attribute' => 'subject_group_id',
                'label' => Yii::t('app', 'Subject Group'),
                'value' => function($model){                   
                    return isset($model->subjectGroup->subject_group_name) ? $model->subjectGroup->subject_group_name:'N/A';                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectGroups::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Subject groups', 'id' => 'grid-student-class-attendance-search-subject_group_id']
            ],
   
        [
                'attribute' => 'subject_id',
                'label' => Yii::t('app', 'Subject'),
                'value' => function($model){                   
                    return $model->subject->subject_name;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Subjects::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Subjects', 'id' => 'grid-student-class-attendance-search-subject_id']
            ],
   
        'date',
   
        [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){                   
                    return $model->getStateOptionsBadges();                   
                },
               
               
            ],
        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view}',
             'buttons' => [
            'view'=> function($url,$model) {
                $url = Url::toRoute(['/admin/student-class-attendance/view', 'id'=>$model->id]);
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                } 
                },
            'update'=> function($url,$model) {
                $url = Url::toRoute(['/admin/student-class-attendance/view', 'id'=>$model->id]);
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a( '<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);

                } 
                },
            'delete'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a( '<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url,[
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-class-attendance']],
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
