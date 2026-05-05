<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\StudentClassAttendanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\StudentClassAttendance;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\SubjectTimetable;
use kartik\grid\GridView;

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
                return $model->student ? $model->student->student_name : 'N/A';                   
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->asArray()->all(), 'id', 'student_name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-student-class-attendance-search-student_id']
        ],
        
        [
            'attribute' => 'teacher_id',
            'label' => Yii::t('app', 'Teacher'),
            'value' => function($model){                   
                return $model->teacher ? $model->teacher->name : 'N/A';                   
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TeacherDetails::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->asArray()->all(), 'id', 'name'),
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
                if ($model->subjectTimetable) {
                    $day_id = '<b style="color:rgb(78, 54, 163)">' . $model->subjectTimetable->day_id . '</b>';
                    $time_from = $model->subjectTimetable->time_from;
                    $time_to = $model->subjectTimetable->time_to;
                    $class_id = $model->subjectTimetable->class_id;
                    $section_id = $model->subjectTimetable->section_id;
                    $subject_id = $model->subjectTimetable->subject_id;
                    
                    $subjects = Subjects::find()->where(['id' => $subject_id])->one();
                    $student_class = StudentClass::find()->where(['id' => $class_id])->one();
                    $class_sections = ClassSections::find()->where(['id' => $section_id])->one();

                    $getTimeOfDay = SubjectTimetable::getTimeOfDay($time_from);
                    
                    return $subjects->subject_name . ' ' . $student_class->title . ' ' . $class_sections->section_name . ' ' . $day_id . ' ' . $getTimeOfDay . ' ' . $time_from . '-' . $time_to;
                }
                return 'N/A';
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectTimetable::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->asArray()->all(), 'id', 'day_id'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Subject timetable', 'id' => 'grid-student-class-attendance-search-subject_timetable_id']
        ],

        [
            'attribute' => 'academic_year_id',
            'label' => Yii::t('app', 'Academic Year'),
            'value' => function($model){                   
                return $model->academicYear ? $model->academicYear->title : 'N/A';                   
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->asArray()->all(), 'id', 'title'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Academic years', 'id' => 'grid-student-class-attendance-search-academic_year_id']
        ],

        [
            'attribute' => 'subject_group_id',
            'label' => Yii::t('app', 'Subject Group'),
            'value' => function($model){                   
                return isset($model->subjectGroup) ? $model->subjectGroup->subject_group_name : 'N/A';                  
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\SubjectGroups::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->asArray()->all(), 'id', 'id'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Subject groups', 'id' => 'grid-student-class-attendance-search-subject_group_id']
        ],

        [
            'attribute' => 'subject_id',
            'label' => Yii::t('app', 'Subject'),
            'value' => function($model){                   
                return $model->subject ? $model->subject->subject_name : 'N/A';                   
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Subjects::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->asArray()->all(), 'id', 'subject_name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Subjects', 'id' => 'grid-student-class-attendance-search-subject_id']
        ],

        'date',
        // 'mode',
        // [
        //     'attribute' => 'mode',
        //     'format' => 'raw',
        //     'label' => Yii::t('app', 'Mode'),
        //     'filter' => (new StudentClassAttendance())->getModeOptions(),
        //     'filterType' => GridView::FILTER_SELECT2,
        //     'filterWidgetOptions' => [
        //         'pluginOptions' => ['allowClear' => true],
        //     ],
        //     'filterInputOptions' => ['placeholder' => 'Select mode', 'id' => 'grid-mode-search-mode'],
        //     'value' => function($model) {                   
        //         return $model->getModeOptionsBadges();                   
        //     },
        // ],
        
        [
            'attribute' => 'status',
            'format' => 'raw',
            'label' => Yii::t('app', 'Status'),
            'filter' => (new StudentClassAttendance())->getStateOptions(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'status', 'id' => 'grid-state-search-status'],
            'value' => function($model){                   
                return $model->getStateOptionsBadges();                   
            },
        ],

        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view'=> function($url,$model) {
                    if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || 
                        \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || 
                        \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                        return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url, [
                            'title' => Yii::t('app', 'View'), 
                            'data-pjax' => '0',
                        ]);
                    }
                },
                'update'=> function($url,$model) {
                    if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || 
                        \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || 
                        \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                        return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url, [
                            'title' => Yii::t('app', 'Update'), 
                            'data-pjax' => '0',
                        ]);
                    }
                },
                'delete'=> function($url,$model) {
                    if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || 
                        \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || 
                        \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                        return Html::a('<span class="fas fa-trash" aria-hidden="true"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'), 
                            'data-pjax' => '0',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?')
                        ]);
                    }
                },
            ],
        ],
    ]; ?>

    <?= GridView::widget([
        'id' => 'student-class-attendance-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'toolbar' =>  [
            // ['content' =>
            //     Html::a('<i class="fas fa-plus"></i>', ['create'], [
            //         'title' => Yii::t('app', 'Add Attendance'), 
            //         'class' => 'btn btn-success'
            //     ])
            // ],
            '{toggleData}',
            '{export}',
        ],
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="fas fa-book"></i> '.Yii::t('app', 'Student Class Attendances').'</h3>',
            'type' => 'primary',
            'before' => '<em>* ' . Yii::t('app', 'List of all student class attendances') . '</em>',
            'after' => '<div class="clearfix"></div>',
        ],
    ]); ?>
       </div>
    </div>
</div>
