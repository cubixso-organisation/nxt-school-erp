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
   
    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => true],


        'student_name',

        'phone_number',

        [
            'attribute' => 'parent_id',
            'label' => Yii::t('app', 'Father Name'),
            'value' => function ($model) {
                return $model->parent->name_of_the_father ??"NA";
            },
        ],

        [
            'attribute' => 'parent_id',
            'label' => Yii::t('app', 'Mother Name'),
            'value' => function ($model) {
                return $model->parent->name_of_the_mother ??"NA";
            },
        ],

        [
            'attribute' => 'parent_id',
            'label' => Yii::t('app', 'Parent Contact Number'),
            'value' => function ($model) {
                return $model->parent->contact_number ??"NA";
            },
        ],
        [
            'attribute' => 'campus_id',
            'label' => Yii::t('app', 'Campus'),
            'value' => function ($model) {
                return $model->campus->name_of_the_educational_Institution ? $model->campus->name_of_the_educational_Institution : null;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()
              
                ->andWhere(['status' => Campus::STATUS_ACTIVE])
                ->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-student-details-search-name_of_the_educational_Institution']
        ],
        [
            'attribute' => 'student_class_id',
            'label' => Yii::t('app', 'Student Class'),
            'value' => function ($model) {
                return $model->studentClass ? $model->studentClass->title : null;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
               
                ->Where(['status' => ClassSections::STATUS_ACTIVE])
                ->asArray()->all(), 'id', 'title'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Student class', 'id' => 'grid-student-details-search-student_class_id']
        ],
       'student_house',

        
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