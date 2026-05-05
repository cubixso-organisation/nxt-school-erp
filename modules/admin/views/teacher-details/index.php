<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\TeacherDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use app\modules\admin\models\TeacherDetails;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


$this->title = Yii::t('app', 'Teacher Details');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
   	$('.search-form').toggle(1000);
   	return false;
   });";
$this->registerJs($search);


?>
<div class="teacher-details-index">
    <div class="card">
        <div class="card-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>
            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) { ?>
                    <?= Html::a(Yii::t('app', 'Create Teacher Details'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <!-- <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button d-none']) ?> -->

                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'uploadForm']]) ?>
                <?= $form->field($model, 'fileImport')->fileInput() ?>
                <button type="button" class="btn btn-success subm">Upload</button>
                <?php ActiveForm::end(); ?>
            <div id="error" class="text-danger"></div>
            <div id="success" class="text-success"></div>
            <a href="<?= Url::base() ?>/web/files/teacher-details-eample.xls">Example Format Download</a>

            </p>
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

                ['attribute' => 'id', 'visible' => true],



                'name',



                [
                    'attribute' => 'profile_image',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::img(
                            $model['profile_image'],
                            [
                                'width' => '100px',
                                'height' => '100px',
                            ]
                        );
                    },

                ],

                [
                    'attribute' => 'class_id',
                    'label' => Yii::t('app', 'Class'),
                    'value' => function ($model) {
                        return !empty($model->class->title) ? $model->class->title : "";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['status' => StudentClass::STATUS_ACTIVE])
                        ->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-teacher-details-search-class_id']
                ],



                [
                    'attribute' => 'section_id',
                    'label' => Yii::t('app', 'Section'),
                    'value' => function ($model) {
                        return !empty($model->section->section_name) ? $model->section->section_name : "";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                        ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->andWhere(['status' => ClassSections::STATUS_ACTIVE])
                        ->asArray()->all(), 'id', 'section_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Student Section', 'id' => 'grid-teacher-details-search-section_id']
                ],




                'id_number',

                'date_of_birth',



                [


                    'attribute' => 'gender',
                    "format" => 'raw',
                    'label' => Yii::t('app', 'gender'),
                    'filter'  => (new TeacherDetails())->getGenderOptions(),
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'gender', 'id' => 'grid-state-search-gender'],


                    'attribute' => 'gender',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->getGenderOptionsBadges();
                    },


                ],





                [
                    'attribute' => 'blood_group_id',
                    'label' => Yii::t('app', 'Blood Group'),
                    'value' => function ($model) {
                        return !empty($model->bloodGroup->title) ? $model->bloodGroup->title : "";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BloodGroups::find()->asArray()->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Blood groups', 'id' => 'grid-teacher-details-search-blood_group_id']
                ],

                'father_name',

                'contact_number',

                'email:email',

                'address:ntext',

                [


                    'attribute' => 'status',
                    "format" => 'raw',
                    'label' => Yii::t('app', 'Status'),
                    'filter'  => (new TeacherDetails())->getStateOptions(),
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'status', 'id' => 'grid-state-search-status'],


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
                        'view' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                                return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                            }
                        },
                        'update' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                                return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
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
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-teacher-details']],
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
                    ]),
                ],
            ]); ?>
        </div>
    </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();

        $.ajax({
            type: "POST",

            url: "/school_management_backend/gii/default/status-change",


            data: {
                id: id,
                val: val
            },
            success: function(data) {
                swal("Good job!", "Status Successfully Changed!", "success");
            }
        });
    });
</script>
<script>
    $('.subm').click(function(e) {
        var form = $('#uploadForm')[0];
        var formData = new FormData(form);

        let base = "<?= Url::base() ?>"
        let url = base + '/admin/teacher-details/upload-excel'


        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                let res = JSON.parse(response)
                if (res.type == 'error') {

                    $("#success").html('')
                    $("#error").html(res.message)
                } else {
                    $("#error").html('')

                    $("#success").html(res.message)

                }
            },

            error: function() {
                alert('ERROR at PHP side!!');
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>