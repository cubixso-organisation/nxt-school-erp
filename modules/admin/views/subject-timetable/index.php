<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\SubjectTimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\AcademicYears;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\StudentClass;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

$session = Yii::$app->session;

$section_data = [];

// $this->title = Yii::t('app', 'Subject Timetables');
$this->params['breadcrumbs'][] = Yii::t('app', 'Subject Timetables');
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";

$this->registerJs($search);
$section_id = '';
$class_id = '';
$subject_group_subject_id = '';
$academic_year_id = '';
$academic_year = '';
$section = '';
$class = '';

if ($session->has('section_id')) {
    $section_id =  $session->get('section_id');
    $class_sections = ClassSections::find()->where(['id' => $section_id])->one();
    $section = $class_sections->section_name;
}

if ($session->has('class_id')) {
    $class_id =  $session->get('class_id');
    $student_class = StudentClass::find()->where(['id' => $class_id])->one();
    $class = $student_class->title;
}

if ($session->has('subject_group_subject_id')) {
    $subject_group_subject_id =  trim($session->get('subject_group_subject_id'));
}


if ($session->has('academic_year_id')) {
    $academic_year_id =  trim($session->get('academic_year_id'));
    $academic_years = AcademicYears::find()->where(['id' => $academic_year_id])->one();
    $academic_year = $academic_years->title;
}




?>

<div class="row">
    <div class="col-lg-12 col-12">
        <?php
        $id = User::getCampusesByUser(Yii::$app->user->identity->id);
        $campus = Campus::find()->where(['id' => $id])->one();
        if (!empty($campus->academic_year)) {
            $academic_year = !empty($campus->academicYear->title) ? $campus->academicYear->title : '';
        } else {
            $academic_year = '';
        }

        ?>
        <h4><?php

            if (!empty($academic_year)) {
                echo 'Current ' . $academic_year;
            } else {
                echo "Please Set Academic year";
            }


            ?></h4>
    </div>
</div>





<div class="subject-timetable-index">
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'id' => 'academic-year-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
                'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
                'formConfig' => ['showErrors' => true],

            ]); ?>




            <div class="col-12">

                <?php
                $academic_year = \yii\helpers\ArrayHelper::map(
                    AcademicYears::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->orderBy('id')->asArray()->all(),
                    'id',
                    'title'
                );
                if (!empty($academic_year_id)) {
                    $model->academic_year_id = $academic_year_id;
                }


                echo  $form->field($model, 'academic_year_id')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => $academic_year,
                    'options' => ['placeholder' => Yii::t('app', 'Select Academic Year'), 'id' => 'academic-year-id'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-12">

                <button class="btn btn-success" type="button" id="get_academic_year">Get</button>
            </div>

            <?php ActiveForm::end(); ?>




        </div>
    </div>
</div>



<div class="subject-timetable-index">
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'id' => 'class-section-subject-data-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
                'fieldConfig' => ['options' => ['class' => 'form-group col-xs-6 col-sm-6 col-md-6 col-lg-12']], // spacing field groups
                'formConfig' => ['showErrors' => true],

            ]); ?>


            <div class="row">



                <div class="col-4">

                    <?php
                    if (!empty($class_id)) {
                        $data_StudentClass = \yii\helpers\ArrayHelper::map(StudentClass::find()->where(['id' => $class_id])->orderBy('id')->asArray()->all(), 'id', 'title');
                    } else {
                        $data_StudentClass = \yii\helpers\ArrayHelper::map(StudentClass::find()

                            ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                            ->andWhere(['status' => StudentClass::STATUS_ACTIVE])

                            ->orderBy('id')->asArray()->all(), 'id', 'title');
                    }


                    echo  $form->field($model, 'class_id')->widget(\kartik\widgets\Select2::classname(), [
                        'data' => $data_StudentClass,
                        'options' => ['placeholder' => Yii::t('app', 'Choose Student class'), 'id' => 'student-class-id'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);


                    ?>
                </div>
                <div class="col-4">



                    <?php
                    if (!empty($section_id)) {
                        $section_data =  \yii\helpers\ArrayHelper::map(ClassSections::find()
                            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                            ->andWhere(['id' => $section_id])
                            ->andWhere(['student_class_id' => $model->class_id])
                            ->orderBy('id')->asArray()->all(), 'id', 'section_name');
                    } else {
                        $section_data =  \yii\helpers\ArrayHelper::map(ClassSections::find()
                            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                            ->andWhere(['student_class_id' => $model->class_id])
                            ->orderBy('id')->asArray()->all(), 'id', 'section_name');
                    }

                    ?>

                    <?= $form->field($model, 'section_id')->widget(DepDrop::classname(), [
                        'data' => $section_data,
                        'options' => ['id' => 'section-id'],
                        'pluginOptions' => [
                            'depends' => ['student-class-id'],
                            'placeholder' => 'Select...',
                            'url' => Url::to(['/admin/fee-structures/class-section-data'])
                        ]
                    ]);

                    ?>





                </div>


                <div class="col-4">



                    <?= $form->field($model, 'subject_group_subject_id')->widget(DepDrop::classname(), [
                        'data' => $section_data,
                        'options' => ['id' => 'subject-group-subject-id'],
                        'pluginOptions' => [
                            'depends' => ['section-id'],
                            'placeholder' => 'Select...',
                            'url' => Url::to(['/admin/subject-timetable/subject-groups'])
                        ]
                    ]);



                    ?>
                </div>


            </div>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">

                        <button class="btn btn-success" type="button" id="get_class_section_subject">Search</button>
                    </div>

                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>




<?php if (!empty($section_id) && !empty($class_id) && !empty($subject_group_subject_id)) { ?>



    <div class="card">
        <div class="card-body">

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">


                <?php foreach ($model->getDaysOptions() as $key => $getDaysOptionsData) {

                    if ($key == 1) {
                        $nav_link_active = "";
                    } else {
                        $nav_link_active = "";
                    }


                ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $nav_link_active ?>" id="pills-<?= $getDaysOptionsData ?>-tab" data-toggle="pill" href="#pills-<?= $getDaysOptionsData ?>" role="tab" aria-controls="pills-<?= $getDaysOptionsData ?>" aria-selected="true"><?= $getDaysOptionsData ?></a>
                    </li>
                <?php } ?>


            </ul>


            <div class="tab-content" id="pills-tabContent">

                <?php foreach ($model->getDaysOptions() as $key => $getDaysOptionsData) {

                    if ($key == 1) {
                        $show_active = "";
                    } else {
                        $show_active = "";
                    }



                ?>


                    <div class="tab-pane fade <?= $show_active ?>" id="pills-<?= $getDaysOptionsData ?>" role="tabpanel" aria-labelledby="pills-<?= $getDaysOptionsData ?>-tab">


                        <?php


                        echo $this->render('_time_table_form', [
                            'model' => $model,
                            'widgetContainer' => 'dynamicform_wrapper_' . $getDaysOptionsData,
                            'widgetBody' => 'container-items-' . $getDaysOptionsData,
                            'widgetItem' => 'item-' . $getDaysOptionsData,
                            'insertButton' => 'add-item-' . $getDaysOptionsData,
                            'formId' => $getDaysOptionsData . '-dynamic-form',
                            'deleteButton' => $getDaysOptionsData . '-remove-item',
                            'formName' => $getDaysOptionsData,
                            'submitButtonId' => $getDaysOptionsData . 'SubmitButton',
                            'submitButtonValue' => $getDaysOptionsData,
                            'section_id' => $section_id,
                            'class_id' => $class_id,
                            'subject_group_subject_id' => $subject_group_subject_id,
                            'day_id' => $getDaysOptionsData,
                            'time_from_id' => $getDaysOptionsData . '-time_from',
                            'time_to_id' => $getDaysOptionsData . '-time_to'


                        ]);


                        ?>


                    </div>
                <?php
                }
                ?>



            </div>



        </div>
    </div>

<?php } ?>

<div class="subject-timetable-index">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-12">


                    <?php
                    if (!empty($academic_year_id)) {
                        echo "<h6>Selected Academic Year :" . '<span style="color:blue">' . isset($academic_year_id) ? $academic_year[$academic_year_id] : "" . '</span>' . '-';
                    }
                    echo "Selected Class : " . '<span style="color:blue">' . $class . '</span>' . '-';
                    echo "Selected section :" . '<span style="color:blue">' . $section . '</span>';



                    ?>

                </div>
                <?= Html::beginForm(['subject-timetable/import'], 'post', ['enctype' => 'multipart/form-data']) ?>
                <div class="row">
                    <div class="col-md-6">
                        <label for="importFile">Import Timetable (Excel)</label>
                        <?= Html::fileInput('importFile', null, ['accept' => '.xlsx,.xls,.csv', 'class' => 'form-control']) ?>
                    </div>
                    <div class="col-md-6 mt-4">
                        <?= Html::submitButton('Import Timetable', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Download Sample Format', ['subject-timetable/sample-download'], ['class' => 'btn btn-info ml-2']) ?>
                    </div>
                </div>
                <?= Html::endForm() ?>

                <button class="btn btn-success" type="button" id="reset_data">Reset</button>
            </div>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    var baseUrl = "<?= Url::base() ?>"

    $("#get_class_section_subject").on("click", function() {
        let class_section_subject = $('#class-section-subject-data-form').serialize();

        $.ajax({
            type: 'post',
            url: baseUrl + '/admin/subject-timetable/get-subjects-by-group',
            data: class_section_subject,
            success: function(data) {
                let res = JSON.parse(data);
                if (res.status == 'ok') {
                    location.reload();

                }
            }
        })


    })

    $("#get_academic_year").on("click", function() {
        let academic_year_form = $('#academic-year-form').serialize();

        $.ajax({
            type: 'post',
            url: baseUrl + '/admin/subject-timetable/get-academic-year-id',
            data: academic_year_form,
            success: function(data) {
                let res = JSON.parse(data);
                if (res.status == 'ok') {
                    location.reload();

                }
            }
        })





    })



    $("#reset_data").on("click", function() {


        $.ajax({
            type: 'post',
            url: baseUrl + '/admin/subject-timetable/reset-session-data',
            data: {
                reset: 'reset'
            },
            success: function(data) {
                let res = JSON.parse(data);
                if (res.status == 'ok') {
                    location.reload();

                }
            }
        })





    })
</script>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success"><?= Yii::$app->session->getFlash('success') ?></div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('warning')): ?>
    <div class="alert alert-warning"><?= Yii::$app->session->getFlash('warning') ?></div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger"><?= Yii::$app->session->getFlash('error') ?></div>
<?php endif; ?>