<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admin\models\Campus;
use app\modules\admin\models\User;
use kartik\depdrop\DepDrop;
use kartik\form\ActiveForm;
use kartik\export\ExportMenu;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\PayFees;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;

?>

<div class="card">

    <div class="card-body">
        <div class="search-form">
            <?= $this->render('_search_student', ['model' => $searchModel]); ?>
        </div>
    </div>
</div>


<div class="card">

    <div class="card-body">

        <div class="student-details-form">


            <?php $form = ActiveForm::begin([
                'id' => 'academic-year-class-section',
                'type' => ActiveForm::TYPE_VERTICAL,
                'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
                'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
                'formConfig' => ['showErrors' => true],

            ]); ?>
            <?= $form->errorSummary($model); ?>


            <div class="row">



                <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">



                    <?= $form->field($model, 'academic_year_id')->widget(\kartik\widgets\Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()
                            ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                            ->orderBy('id')->asArray()->all(), 'id', 'title'),
                        'options' => ['placeholder' => Yii::t('app', 'Choose Academic years'), 'id' => 'academic-year-id'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>


                </div>




                <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


                    <?= $form->field($model, 'student_class_id')->widget(\kartik\widgets\Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()
                            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                            ->orderBy('id')->asArray()->all(), 'id', 'title'),
                        'options' => ['placeholder' => Yii::t('app', 'Choose Student class'), 'id' => 'student-class-id-ac'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>


                </div>


                <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">


                    <?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [

                        'options' => ['id' => 'class-section-id-ac'],
                        'pluginOptions' => [
                            'depends' => ['student-class-id-ac'],
                            'placeholder' => 'Select...',
                            'url' => Url::to(['/admin/fee-structures/class-section-data'])
                        ]

                    ]);
                    ?>


                </div>

                <button class="btn btn-primary" type="button" id="set-class-academic-section">Set</button>
            </div>

            <?php ActiveForm::end(); ?>




        </div>
    </div>
</div>










<div class="card">

    <div class="card-body">



        <?php $form = ActiveForm::begin([
            'id' => 'promote-students-form',
            'type' => ActiveForm::TYPE_VERTICAL,
            'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
            'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
            'formConfig' => ['showErrors' => true],

        ]); ?>





        <?php

        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => '\kartik\grid\CheckboxColumn'],
            ['attribute' => 'id', 'visible' => false],
            'admission_number',
            'student_name',
            'gender',
            'date_of_birth',
            'phone_number',
            [
                'attribute' => 'student_class_id',
                'label' => Yii::t('app', 'Student Class'),
                'value' => function ($model) {
                    return $model->studentClass->title;
                },

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

            ],


            // [
            //     'attribute' => 'status',
            //     'format' => 'raw',
            //     'label' => Yii::t('app', 'Next Session Status'),
            //     'value' => function ($model) {
            //         return  Html::activeDropDownList(
            //             $model,
            //             'status',
            //             $model->getStateOptionsAcademic(),
            //             ['id' => 'status-id-' . $model->id, 'name' => 'status_id_' . $model->id, 'data-placeholder' => 'Select A Report', 'title' => 'My Test label']
            //         );
            //     },

            // ],
            [
                'attribute' => 'status',
                "format" => 'raw',
                'value' => function ($model) {
                    $html = '';


                    $html .= '<select id="status_list_' . $model->id . '" data-id="' . $model->id . '">';


                    $lists = $model->getStateOptionsAcademic();

                    foreach ($lists as $key => $list) {

                        if ($key == $model->status) {
                            $html .= '<option value="' . $key . '" selected>' . $list . '</option>';
                        } else {
                            $html .= '<option value="' . $key . '">' . $list . '</option>';
                        }
                    }
                    $html .= '</select>';

                    return $html;
                }
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




        <?php ActiveForm::end(); ?>




    </div>
</div>

<div class="card">

    <div class="card-body">

        <?= Html::button('Promote selected students', ['class' => 'btn btn-success', 'id' => 'promote_students']) ?>

    </div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    let academic_year_id;
    let student_class_id;
    let section_id;
    let base = "<?= Url::base(); ?>"


    $("#set-class-academic-section").on("click", function() {
        let serialize_data = $('#academic-year-class-section').serializeArray();
        if (serialize_data[1]) {
            academic_year_id = serialize_data[1].value;

        }
        if (serialize_data[2]) {
            student_class_id = serialize_data[2].value;

        }
        if (serialize_data[3]) {
            section_id = serialize_data[3].value;

        }
        if (academic_year_id && student_class_id && section_id) {
            swal("Done!", "Set Successfully!", "success");
        } else {
            swal("Oops!", "Please set academic year and class and section", "error");

        }


    });

    $("#promote_students").on('click', function() {
        let formData = jQuery("#promote-students-form").serialize();
        if (academic_year_id != undefined && student_class_id != undefined && section_id != undefined) {

            $.ajax({
                url: base + '/admin/student-details/promote-students-next-level',
                method: 'post',
                data: formData + '&academic_year_id=' + academic_year_id + '&student_class_id=' + student_class_id + '&section_id=' + section_id,
                success: function(res) {
                    let response = JSON.parse(res)
                    console.log(response);

                    if (response.status == 'ok') {

                        swal("Good job!", response.details, "success").then(okay => {
                            if (okay) {
                                window.location.reload();
                            }
                        });
                    } else {
                        swal("Sorry!", response.error, "error");
                    }





                }

            })
        } else {
            alert('required fields are missing')
        }

    })
</script>
<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();

        $.ajax({
            type: "POST",
            url: "<?= Url::toRoute(['student-details/update-status']) ?>",
            data: {
                id: id,
                val: val
            },
            success: function(data) {
                swal("Good job!", "Status Successfully Changed!", "success");


                if (val === '2') {

                    $('#status_list_' + id).prop('disabled', true);
                } else {

                    $('#status_list_' + id).prop('disabled', false);
                }
            }
        });
    });
</script>