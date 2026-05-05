<?php

use app\models\User;
use app\modules\admin\models\base\ClassTeacher;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\TeacherDetails;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Assigned Teachers');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    function updateSectionsDropdown(classId) {
        if (classId) {
            $.ajax({
                url: '" . \yii\helpers\Url::to(['class-teacher/sections-by-class']) . "?classId=' + classId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('AJAX success:', data);
                    var sections = data.sections;
                    var options = '<option></option>';
                    sections.forEach(function(section) {
                        options += '<option value=\"' + section.id + '\">' + section.section_name + '</option>';
                    });
                    $('#grid-class-teacher-search-section_id').html(options);
                    $('#grid-class-teacher-search-section_id').prop('disabled', false);
                    $('#grid-class-teacher-search-section_id').select2({
                        allowClear: true,
                        placeholder: 'Class sections'
                    }).trigger('change');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('AJAX error. Status:', textStatus, '. Error:', errorThrown);
                    console.log('Response:', jqXHR.responseText);
                }
            });
        } else {
            $('#grid-class-teacher-search-section_id').html('');
            $('#grid-class-teacher-search-section_id').prop('disabled', true);
            $('#grid-class-teacher-search-section_id').select2({
                allowClear: true,
                placeholder: 'Class sections'
            }).trigger('change');
        }
    }

    var urlParams = new URLSearchParams(window.location.search);
    var classId = urlParams.get('ClassTeacherSearch[class_id]');
    if (classId) {
        updateSectionsDropdown(classId);
    }

    $('#grid-class-teacher-search-class_id').on('change', function() {
        var classId = $(this).val();
        updateSectionsDropdown(classId);
    });
");
?>

<div class="class-teacher-index">
    <div class="card">
        <div class="card-body">
            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role==User::role_campus_sub_admin) { ?>
                    <?= Html::a(Yii::t('app', 'Assign Teacher'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button d-none']) ?>
            </p>
       
                <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'id', 'visible' => false],
                [
                    'attribute' => 'class_id',
                    'label' => Yii::t('app', 'Class'),
                    'value' => function ($model) {
                        return $model->class->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(StudentClass::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['status' => StudentClass::STATUS_ACTIVE])
                        ->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => [
                        'placeholder' => 'Student class',
                        'id' => 'grid-class-teacher-search-class_id'
                    ],
                ],
                [
                    'attribute' => 'section_id',
                    'label' => Yii::t('app', 'Section'),
                    'value' => function ($model) {
                        return $model->section->section_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => [],
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => [
                        'placeholder' => 'Class sections',
                        'id' => 'grid-class-teacher-search-section_id',
                        'disabled' => false
                    ],
                ],
                [
                    'attribute' => 'teacher_details_id',
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Teacher Details'),
                    'value' => function ($model) {
                        $class_id  = $model->class_id;
                        $section_id = $model->section_id;
                        $teacher_details_id = $model->teacher_details_id;
                
                        $teacher_details = TeacherDetails::find()
                            ->where(['id' => $teacher_details_id])
                            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                            ->andWhere(['class_id' => $class_id])
                            ->andWhere(['section_id' => $section_id])
                            ->one();
                
                        if ($teacher_details) {
                            return $teacher_details->name . ' <b style="color:red">Class Teacher</b>';
                        } else {
                            return $model->teacherDetails->name ?? 'Not Assigned';
                        }
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(TeacherDetails::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Teacher details', 'id' => 'grid-class-teacher-search-teacher_details_id']
                ],
                
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Status'),
                    'filter'  => (new ClassTeacher())->getStateOptions(),
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Status', 'id' => 'grid-state-search-status'],
                    'value' => function ($model) {
                        return $model->getStateOptionsBadges();
                    },
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (in_array(Yii::$app->user->identity->user_role, [User::ROLE_ADMIN, User::ROLE_CAMPUS_ADMIN, User::role_campus_sub_admin])) {
                                return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                            }
                        },
                        'update' => function ($url, $model) {
                            if (in_array(Yii::$app->user->identity->user_role, [User::ROLE_ADMIN, User::ROLE_CAMPUS_ADMIN, User::role_campus_sub_admin])) {
                                return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (in_array(Yii::$app->user->identity->user_role, [User::ROLE_ADMIN, User::ROLE_CAMPUS_ADMIN, User::role_campus_sub_admin])) {
                                return Html::a('<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url, [
                                    'data' => [
                                        'method' => 'post',
                                        'confirm' => 'Are you sure?',
                                    ],
                                ]);
                            }
                        },
                    ]
                ],
            ];

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'pjax' => true, // Enable Pjax
                'pjaxSettings' => ['options' => ['id' => 'class-teacher-pjax-container']],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                ],
                'export' => false,
                'responsive' => true,
                'hover' => true,
                'persistResize' => false,
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
            ]);
            ?>
        </div>
    </div>
</div>
