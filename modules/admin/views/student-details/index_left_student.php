<?php
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\StudentDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentDetails;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Student Details');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
   	$('.search-form').toggle(1000);
   	return false;
   });";
$this->registerJs($search);



?>
<div class="student-details-index">
    
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],


        'admission_number',
        'student_name',
        'gender',
        'date_of_birth',
        'category',

        'phone_number',
        [
            'attribute' => 'student_class_id',
            'label' => Yii::t('app', 'Student Class'),
            'value' => function ($model) {
                return $model->studentClass ? $model->studentClass->title : null;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['status' => ClassSections::STATUS_ACTIVE])
                ->asArray()->all(), 'id', 'title'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-search-student_class_id']
        ],
        [
            'attribute' => 'section_id',
            'label' => Yii::t('app', 'Section'),
            'value' => function ($model) {
                if ($model->section) {
                    return $model->section->section_name;
                } else {
                    return null;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()
                ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                ->andWhere(['status' => ClassSections::STATUS_ACTIVE])
                ->asArray()->all(), 'id', 'section_name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Class sections', 'id' => 'grid-student-details-search-section_id']
        ],



        [
            'attribute' => 'academic_year_id ',
            'label' => Yii::t('app', 'Academic Year'),
            'value' => function ($model) {
                return $model->academicYear ? $model->academicYear->title : null;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->asArray()->all(), 'id', 'title'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'academic year', 'id' => 'grid-student-details-search-academic_year_id']
        ],




        'student_house',
        'height',
        'weight',
        'national_Identification_number',

        [


            'attribute' => 'status',
            "format" => 'raw',
            'label' => Yii::t('app', 'Status'),
            'filter'  => (new StudentDetails())->getStateOptions(),
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


        ],        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view}',
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-student-details']],
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
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<!-- Include SweetAlert library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
    $('.subm').click(function(e) {
        e.preventDefault(); // Prevent the default form submission

        // var formData = new FormData($('form')[0]);
        var form = $('#uploadForm')[0];
        var formData = new FormData(form);
        let base = "<?= Url::base() ?>"
        let url = base + '/admin/student-details/upload-excel'

        // Show loading GIF SweetAlert
        swal({
            title: 'Loading',
            buttons: false,
            closeOnClickOutside: false,
            closeOnEsc: false
        });

        // Dynamically add image element with loading GIF
        $('.swal-modal').append('<img src="https://media.tenor.com/images/70471d9fee1447c544cb13c458aeb972/tenor.gif" alt="Loading..." />');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                swal.close(); // Close loading SweetAlert
                let res = JSON.parse(response);
                if (res.type == 'error') {
                    // Show error SweetAlert
                    swal({
                        title: 'Error',
                        text: res.message,
                        icon: 'error',
                    });
                    $("#success").html('');
                    $("#error").html(res.message);
                } else {
                    // Show success SweetAlert
                    swal({
                        title: 'Success',
                        text: res.message,
                        icon: 'success',
                    });

                    $("#error").html('');
                    $("#success").html(res.message);
                }
            },

            error: function(xhr, status, error) {
                swal.close(); // Close loading SweetAlert
                var errorMessage = "Integrity constraint violation: 1048 Column 'student_name' cannot be null";
                swal({
                    title: 'Uploading Error',
                    text: errorMessage,
                    icon: 'error',
                });
            },

            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>