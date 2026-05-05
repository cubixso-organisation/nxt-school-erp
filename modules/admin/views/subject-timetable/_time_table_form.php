<?php

use app\models\User;
use app\modules\admin\models\AcademicYears;
use app\modules\admin\models\Campus;
use app\modules\admin\models\ClassRooms;
use app\modules\admin\models\SubjectGroupSubjects;
use app\modules\admin\models\Subjects;
use app\modules\admin\models\SubjectTimetable;
use app\modules\admin\models\TeacherDetails;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$session = Yii::$app->session;

$subject_timetable = SubjectTimetable::find()->innerJoinWith('subjectGroupSubject')->where(['subject_timetable.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
    ->andWhere(['class_id' => $class_id])
    ->andWhere(['section_id' => $section_id])
    ->andWhere(['subject_timetable.status' => SubjectTimetable::STATUS_ACTIVE])
    ->andWhere(['subject_timetable.day_id' => $day_id])->all();

?>

 
<div class="customer-form">
    <?php $form = ActiveForm::begin(['id' => $formId]); ?>
    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>

    <?php foreach ($subject_timetable as $subject_timetable_data) {
        
        ?>
        <div class="<?= $widgetItem ?> panel panel-default" id="<?= $subject_timetable_data->id ?>" ><!-- widgetBody -->
            <div class="panel-heading">
                <span class="panel-title-address"><?= $formName ?> Time Table : </span>
                <button type="button" class="pull-right <?= $deleteButton ?> btn btn-danger btn-xs" onclick="deleteButton(<?= $subject_timetable_data->id ?>)"   ><i class="fa fa-minus"></i></button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="row">


                <input type="hidden" name="SubjectTimetable[subject_timetable_id][]" value="<?= $subject_timetable_data->id ?>" >

                    <div class="col-sm-2">
                        <?php
                        $Subjects = Subjects::find()
                            ->innerJoinWith('subjectGroupSubjects as sgp')
                            ->where(['sgp.subject_group_id' => $subject_group_subject_id])
                            ->andWhere(['subjects.status'=>Subjects::STATUS_ACTIVE])
                            ->andWhere(['sgp.status'=>SubjectGroupSubjects::STATUS_ACTIVE])
                            ->andWhere(['subjects.campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
                            ->all();
        ?>

                        <div class="form-group highlight-addon field-subject_id required has-success">
                            <label class="has-star" for="subject_id">Subjects</label>
                            <select id="subject_id" class="form-control is-valid" name="SubjectTimetable[subject_id][]" aria-required="true" aria-invalid="false">
                                <option value="">--Select--</option>
                                <?php
                foreach ($Subjects as  $Subjects_data) {
                    if ($subject_timetable_data->subject_id == $Subjects_data->id) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }

                    ?>
                                    <option value="<?= $Subjects_data->id ?>" <?= $selected ?>><?= $Subjects_data->subject_name ?></option>
                                <?php
                }

        ?>

                            </select>

                            <div class="invalid-feedback"></div>

                        </div>

                    </div>






                    <div class="col-sm-2">


                        <?php
                        $TeacherDetails = TeacherDetails::find()
                            ->innerJoinWith('classTeachers')
                            ->where(['class_teacher.class_id' => $class_id])
                            ->andWhere(['class_teacher.section_id' => $section_id])
                            ->all();




        ?>

                        <div class="form-group highlight-addon field-subjecttimetable-teacher_details_id required has-success">
                            <label class="has-star" for="subjecttimetable-teacher_details_id">Teacher Details</label>

                            <select id="subjecttimetable-teacher_details_id" class="form-control is-valid" name="SubjectTimetable[teacher_details_id][]" aria-required="true" aria-invalid="false">
                                <option value="">--Select--</option>
                                <?php

                foreach ($TeacherDetails as $TeacherDetailsData) {
                    if ($subject_timetable_data->teacher_details_id == $TeacherDetailsData->id) {
                        $selected_teacher_details_id = 'selected';
                    } else {
                        $selected_teacher_details_id = '';
                    }


                    ?>
              <option value="<?= $TeacherDetailsData->id ?>" <?= $selected_teacher_details_id ?>><?= $TeacherDetailsData->name ?></option>


                                <?php

                }
        ?>
                            </select>

                            <div class="invalid-feedback"></div>

                        </div>

                    </div>

                    <div class="col-sm-2">
                            <label for="time_from">Time From:</label>
                            <input class="form-control" type="time" name="SubjectTimetable[time_from][]" value="<?= $subject_timetable_data->time_from ?>">
                    </div>
                <div class="col-sm-2">
                        <label for="time_to">Time To:</label>
                        <input class="form-control" type="time" name="SubjectTimetable[time_to][]" value="<?= $subject_timetable_data->time_to ?>">
                    </div>
                    <div class="col-sm-2">
                            <div class="form-group highlight-addon field-subjecttimetable-room_id required has-success">
                            <label class="has-star" for="subjecttimetable-room_id"> Class Room</label>

                            <select id="subjecttimetable-room_id" class="form-control is-valid" name="SubjectTimetable[room_id][]" aria-required="true" aria-invalid="false">
                                <?php

        $ClassRooms = ClassRooms::find()
            ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->andWhere(['status' => ClassRooms::STATUS_ACTIVE])
            ->all(); ?>


                                <?php

        foreach ($ClassRooms as $ClassRoomsData) {

            if ($subject_timetable_data->room_id  == $ClassRoomsData->id) {
                $room_id_selected = 'selected';
            } else {
                $room_id_selected = '';
            }

            ?>
                                    <option value="<?= $ClassRoomsData->id ?>" <?= $room_id_selected ?>><?= $ClassRoomsData->class_room_title ?></option>

                                <?php
        }

        ?>


                            </select>

                            <div class="invalid-feedback"></div>

                        </div>

                    </div>




                    <div class="col-sm-2">



                        <label for="time_from">period:</label>
                        <input class="form-control" type="number" name="SubjectTimetable[period][]" value="<?= $subject_timetable_data->period ?>">



                    </div>






                </div>




            </div>
        </div>

    <?php } ?> 
 

    <?php DynamicFormWidget::begin([
        'widgetContainer' => $widgetContainer, // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.' . $widgetBody, // required: css class selector
        'widgetItem' => '.' . $widgetItem, // required: css class
        'limit' => 100, // the maximum times, an element can be cloned (default 999)
        'min' => 0, // 0 or 1 (default 1)
        'insertButton' => '.' . $insertButton, // css class
        'deleteButton' => '.' . $deleteButton, // css class
        'model' => $model,
        'formId' => $formId,
        'formFields' => [
            'subject_group_subject_id',
            'teacher_details_id',

        ],
    ]); ?>

    <div class="panel panel-default" >
        <div class="panel-heading">
            <i class="far fa-calendar-minus"></i> <?= $formName ?> Time Table
            <button type="button" class="pull-right <?= $insertButton ?> btn btn-success btn-xs"><i class="fa fa-plus"></i> Add <?= $formName ?> Time Table</button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body <?= $widgetBody ?>"><!-- widgetContainer -->



            <div class="<?= $widgetItem ?> panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <span class="panel-title-address"><?= $formName ?> Time Table : </span>
                    <button type="button" class="pull-right <?= $deleteButton ?> btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">

 
                <input type="hidden" name="SubjectTimetable[subject_timetable_id][]" value="" >


                    <div class="row">
                        <div class="col-sm-2">
                            <?php

                            echo   $form->field($model, 'subject_id[]')->dropDownList(ArrayHelper::map(Subjects::find()
        ->innerJoinWith('subjectGroupSubjects')
        ->where(['subject_group_subjects.subject_group_id' => $subject_group_subject_id])
        ->andWhere(['subjects.campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)])
        ->andWhere(['subjects.status'=>Subjects::STATUS_ACTIVE])
        ->andWhere(['subject_group_subjects.status'=>SubjectGroupSubjects::STATUS_ACTIVE])


        ->asArray()->all(), 'id', 'subject_name'), ['prompt' => '--Select--'])->label('Subject');


?>


                        </div>
                        <div class="col-sm-2">

                            <?php

echo   $form->field($model, 'teacher_details_id[]')->dropDownList(ArrayHelper::map(TeacherDetails::find()
    ->innerJoinWith('classTeachers')
    ->where(['class_teacher.class_id' => $class_id])
    ->andWhere(['class_teacher.section_id' => $section_id])
    ->asArray()->all(), 'id', 'name'), ['prompt' => '--Select--']);


?>


                        </div>

                        <div class="col-sm-2">
                            <label for="time_from">Time From:</label>
                            <input class="form-control" type="time" name="SubjectTimetable[time_from][]" id="<?= $time_from_id ?>">



                        </div>

                        <div class="col-sm-2">




                            <label for="time_to">Time To:</label>
                            <input class="form-control" type="time" name="SubjectTimetable[time_to][]" id="<?=  $time_to_id ?>">

                        </div>







                        <div class="col-sm-2">

                            <?php

echo   $form->field($model, 'room_id[]')->dropDownList(ArrayHelper::map(ClassRooms::find()
    ->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
    ->andWhere(['status' => ClassRooms::STATUS_ACTIVE])
    ->asArray()->all(), 'id', 'class_room_title'), ['prompt' => '--Select--']);


?>


                        </div>



                        <div class="col-sm-2">



                            <label for="time_from">period:</label>
                            <input class="form-control" type="number" name="SubjectTimetable[period][]" value="<?= $subject_timetable_data->period??"" ?>">



                        </div>



                    </div><!-- end:row -->




                </div>
            </div>





        </div>
    </div>





    <?php
     DynamicFormWidget::end();
    
    ?>
  

    <div class="form-group">
        <button type="button" id="<?= $submitButtonId ?>" onclick="submitFormData('<?= $formId ?>','<?= $submitButtonValue ?>')" class="btn btn-success">Save</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>





    var widgetContainer = <?= $widgetContainer ?> = null;
    $("." + widgetContainer).on("afterInsert", function(e, item) {
        $("." + widgetContainer).each(function(index) {
            $(this).html(widgetContainer + ": " + (index + 1))
        });
    });

    $("." + widgetContainer).on("afterDelete", function(e) {
        $("." + widgetContainer).each(function(index) {
            $(this).html(widgetContainer + ": " + (index + 1))
        });
    });

    var baseUrl = "<?= Url::base() ?>"


    function submitFormData(form_id, day_id) {

        var form_data = $('#' + form_id).serialize()


 

        var fnl_form_data = form_data + '&SubjectTimetable[day_id]=' + day_id

        $.ajax({
            type: 'post',
            data: fnl_form_data,
            url: baseUrl + '/admin/subject-timetable/add-or-update-time-table',
            success: function(data) {
                let res = JSON.parse(data)

                
                if (res.status == 'ok') {
                    console.log(res)
                   if(res.insert_or_update_error==true){
                    Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Some time table data not updated!',
                    footer: '<a href="'+baseUrl+'/admin/timetable-error-reports">Why do I have this issue?</a>'
                    })
           
                }else if(res.time_error==true){
                    Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: res.details,
                    footer: '<a href="'+baseUrl+'/admin/timetable-error-reports">Why do I have this issue?</a>'
                    })
                }else{
                  
                    Swal.fire({
                    icon: 'success',
                    title: 'success',
                    text: 'time table data  updated!',
                    })

                   }

                
 

                } else {
                    Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'failed',
                    })

                  

                }
            }
        })


    }

function deleteButton(id){
    $.ajax({
        type: 'post',
            data: {
                'id':id
            },
            url: baseUrl + '/admin/subject-timetable/subject-time-table-delete',
            success: function(data) {
                let res = JSON.parse(data)
     
                if (res.status == 'ok') {
                swal("Done!", res.details, "success").then(okay => {
                        if (okay) {
                            $('#'+id).remove();
                       
                        }
                    });


                } else {
                    console.log(res)

                   

                }
            }
    })

}




</script> 