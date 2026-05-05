<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\AttendanceTimeTablesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\AttendanceSettings;
use app\modules\admin\models\AttendanceTimeTables;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\SubjectTimetable;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Attendance Time Tables');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 

?>
<div class="attendance-time-tables-index">
<div class="card">
       <div class="card-body">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role==User::role_campus_sub_admin){ ?>
        <?php
            
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $attendance_settings = AttendanceSettings::find()->where(['campus_id'=>$campusId])->one();


        if(!empty($attendance_settings)){
            echo Html::a(Yii::t('app', 'Create Attendance Time Tables'), ['create'], ['class' => 'btn btn-success']);

        }else{
            echo "<h3>Attendance Settings not found</h3>";
        }
             
             
            
            ?>
        <?php  } ?>
    </p>
    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
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
                'attribute' => 'attendance_settings_id',
                'label' => Yii::t('app', 'Attendance Settings'),
                'value' => function($model){                   
                    return $model->attendanceSettings->title;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AttendanceSettings::find()->asArray()->all(), 'id', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Attendance settings', 'id' => 'grid-attendance-time-tables-search-attendance_settings_id']
            ],
   
            [
                'attribute' => 'subject_timetable_id',
                'format'=>'raw',
                'label' => Yii::t('app', 'Subject Timetable'),
                'value' => function($model){
                    // Check if subjectTimetable relation is available
                    if (!$model->subjectTimetable) {
                        return 'N/A';
                    }
            
                    // Get day_id and time details with fallback values
                    $day_id = $model->subjectTimetable->day_id ? '<b style="color:rgb(78, 54, 163)">'.$model->subjectTimetable->day_id.'</b>' : 'N/A';
                    $time_from = $model->subjectTimetable->time_from ?? '';
                    $time_to = $model->subjectTimetable->time_to ?? '';
            
                    // Get class, section, and subject IDs
                    $class_id = $model->subjectTimetable->class_id ?? null;
                    $section_id = $model->subjectTimetable->section_id ?? null;
                    $subject_id = $model->subjectTimetable->subject_id ?? null;
            
                    // Load related models (Subjects, StudentClass, ClassSections) and check if they exist
                    $subjects = $subject_id ? Subjects::findOne($subject_id) : null;
                    $student_class = $class_id ? StudentClass::findOne($class_id) : null;
                    $class_sections = $section_id ? ClassSections::findOne($section_id) : null;
            
                    // Safeguard: Ensure each related model is loaded, otherwise default to 'N/A'
                    $subject_name = $subjects ? $subjects->subject_name : 'N/A';
                    $class_title = $student_class ? $student_class->title : 'N/A';
                    $section_name = $class_sections ? $class_sections->section_name : 'N/A';
            
                    // Get time of day
                    $getTimeOfDay = SubjectTimetable::getTimeOfDay($time_from);
            
                    // Construct the final timetable details string
                    $time_table_details = "$subject_name $class_title $section_name $day_id $getTimeOfDay $time_from-$time_to";
            
                    return $time_table_details;
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
                "format" => 'raw',
                'label' => Yii::t('app', 'Status'),
                'filter'  =>  (new AttendanceTimeTables())->getStateOptions(),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'status', 'id' => 'grid-state-search-status'],
    
    
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function($model){                   
                        return $model->getStateOptionsBadges();                   
                    },
                   
                   
                ],
    















            
        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view} {update} {delete}',
             'buttons' => [
            'view'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                } 
                },
            'update'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a( '<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);

                } 
                },
            'delete'=> function($url,$model) {
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role==User::role_campus_sub_admin) {
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-attendance-time-tables']],
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

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).on('change','select[id^=status_list_]',function(){
var id=$(this).attr('data-id');
var val=$(this).val();

$.ajax({
	  type: "POST",
	 
      url: "/school_management_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>