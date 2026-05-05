<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\ExamsResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\base\ClassSections;
use app\modules\admin\models\Exams;
use app\modules\admin\models\ExamsResult;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Exams Results');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 

?>




<div class="exams-result-index">




    
    <div class="card">
       <div class="card-body">
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
      
    
    
           [
                'attribute' => 'exam_id',
                'label' => Yii::t('app', 'Exams'),
                'value' => function($model){                   
                    return isset($model->exam->name_of_exam) ? $model->exam->name_of_exam:'';                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Exams::find()
                ->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['status'=>ClassSections::STATUS_ACTIVE])
                ->asArray()->all(), 'id', 'name_of_exam'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Exams', 'id' => 'grid-exams-result-search-exam_id']
            ],
   
        [
                'attribute' => 'academic_year_id',
                'label' => Yii::t('app', 'Academic Year'),
                'value' => function($model){                   
                    return $model->academicYear->title;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
                ->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['status'=>ClassSections::STATUS_ACTIVE])
                ->asArray()->all(), 'id', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Academic years', 'id' => 'grid-exams-result-search-academic_year_id']
            ],


            [
                'attribute' => 'marks_type',
                'format' => 'raw',
                'label' => Yii::t('app', 'marks type'),
                'value' => function($model){                   
                    return $model->getMarksTypeBadges();
                                
                },


                'filterType' => GridView::FILTER_SELECT2,
                'filter' => (new ExamsResult())->getMarksTypeOptions(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'marks type', 'id' => 'grid-state-search-marks_type']



              
            ],






   
        [
                'attribute' => 'student_id',
                'label' => Yii::t('app', 'Student'),
                'value' => function($model){                   
                    return $model->student->student_name;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetails::find()
                ->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['status'=>ClassSections::STATUS_ACTIVE])
                ->asArray()->all(), 'id', 'student_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Student details', 'id' => 'grid-exams-result-search-student_id']
            ],
   
        [
                'attribute' => 'class_id',
                'label' => Yii::t('app', 'Class'),
                'value' => function($model){                   
                    return $model->class->title;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                ->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['status'=>ClassSections::STATUS_ACTIVE])
                ->asArray()->all(), 'id', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-exams-result-search-class_id']
            ],
   
        [
                'attribute' => 'section_id',
                'label' => Yii::t('app', 'Section'),
                'value' => function($model){                   
                    return $model->section->section_name;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                ->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['status'=>ClassSections::STATUS_ACTIVE])
                ->asArray()->all(), 'id', 'section_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-exams-result-search-section_id']
            ],
            [
                'label' => 'Marks-Sheet PDF',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a href="' . $model->marks_sheet . '" target="_blank">
                    <i class="far fa-file-pdf fa-2x"></i></a>';
                },
            ],
            
     
        [
            'attribute' => 'percentage_or_gpa',
            'label' => 'percentage or gpa',
            'format' => 'raw',
            'value' => function ($model) {
                if (isset($model->exam) && isset($model->exam->marks_type)) {
                    if ($model->exam->marks_type == Exams::marks_type_gpa) {
                        return $model->percentage_or_gpa . ' GPA';
                    } else {
                        return $model->percentage_or_gpa . ' %';
                    }
                } else {
                    return 'N/A'; // Fallback in case exam or marks_type is not set
                }
                


            },
        ],
   
        // [
        //         'attribute' => 'status',
        //         'format' => 'raw',
        //         'value' => function($model){                   
        //             return $model->getStateOptionsBadges();                   
        //         },

        //         'filterType' => GridView::FILTER_SELECT2,
        //         'filter' => (new ExamsResult())->getStateOptions(),
        //         'filterWidgetOptions' => [
        //             'pluginOptions' => ['allowClear' => true],
        //         ],
        //         'filterInputOptions' => ['placeholder' => 'Status', 'id' => 'grid-state-search-status']
               
               
        //     ],


        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view} {delete}',
             'buttons' => [
            'view'=> function($url,$model) {
                $url = Url::toRoute(['/admin/exams-result/view','id'=>$model->exams_result_id]);
            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                } 
                },
            'update'=> function($url,$model) {
                $url = Url::toRoute(['/admin/exams-result/update','id'=>$model->exams_result_id]);

            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                    return Html::a( '<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);

                } 
                },
            'delete'=> function($url,$model) {
                $url = Url::toRoute(['/admin/exams-result/delete','id'=>$model->exams_result_id]);

            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-exams-result']],
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

