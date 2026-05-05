<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\staffmanagement\models\search\StaffAttendenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

$this->title = 'Staff Attendences';
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="staff-attendence-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            if (empty($index)) {

            ?>

                <p>

                    <?= Html::a('Generate Today Attendance', ['generate-today-attendance'], ['class' => 'btn btn-info']) ?>
                </p>

            <?php } ?>
            <div class="search-form" style="display:none">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],


                ['attribute' => 'id', 'visible' => false],

                // [
                //     'attribute' => 'campus_id',
                //     'label' => 'Campus',
                //     'value' => function ($model) {
                //         return $model->campus->id;
                //     },

                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->asArray()->all(), 'id', 'id'),
                //     'filterWidgetOptions' => [
                //         'pluginOptions' => ['allowClear' => true],
                //     ],
                //     'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-staff-attendence-search-campus_id']
                // ],

                [
                    'attribute' => 'staff_id',
                    'label' => 'Staff',
                    'value' => function ($model) {
                        return $model->staff->name;
                    },

                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffDetails::find()->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Staff details', 'id' => 'grid-staff-attendence-search-staff_id']
                ],



                'date',

                [
                    'attribute' => 'attendance_count_perday',
                    'label' => 'Attendence Number',
                    'value' => function ($model) {
                        return $model->attendance_count_perday;
                    },

                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffAttendence::find()->where(['campus_id' => (new User())->getCampusId()])->asArray()->all(), 'attendance_count_perday', 'attendance_count_perday'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Staff details', 'id' => 'grid-staff-attendence-search-attendance_count_perday']
                ],




                [
                    'class' => 'kartik\grid\EditableColumn',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'attribute' => 'attendence',
                    'value' => function ($model) {
                        if (empty($model->attendence)) {
                            return "Not Set";
                        } else {
                            return strip_tags($model->getStateOptionsBadges());
                        }
                    },
                    'readonly' => false,
                    'editableOptions' => function ($model, $key, $index) {
                        return [
                            'name' => 'note', // this will be sent to controller to process
                            'header' => 'marks_type',
                            'asPopover' => false,
                            'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                            'data' => $model->getStateOptions(),
                            'beforeInput' => '<h6 style="font-weight: bold">Marks Type</h6>',
                            'value' => function ($model) {
                                if (empty($model->marks_type)) {
                                    return "Not Set";
                                } else {
                                    return strip_tags($model->getStateOptionsBadges());
                                }
                            }, // in this case, $model is an array. For others, $model->employer_score
                        ];
                    },
                ],
                [
                    'attribute' => 'designation',
                    'label' => 'Designation',
                    'value' => function ($model) {

                        return $model->staff->designation->title;
                    },

                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\StaffAttendence::find()->where(['campus_id' => (new User())->getCampusId()])->asArray()->all(), 'attendance_count_perday', 'attendance_count_perday'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Staff details', 'id' => 'grid-staff-attendence-search-attendance_count_perday']
                ],

            ];
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'bordered' => false,
                'class' => 'table table-striped mb-0',
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-staff-attendence']],
                'panel' => [
                    'type' => 'light',
                    'heading' => '<span class=""></span>  ' . Html::encode($this->title),
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
                    ]),
                ],
            ]); ?>
        </div>
    </div>
</div>