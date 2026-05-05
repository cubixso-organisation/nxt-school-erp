<div class="form-group" id="add-student-details">
<?php
use kartik\grid\GridView;
use kartik\builder\TabularForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\Pjax;

$dataProvider = new ArrayDataProvider([
    'allModels' => $row,
    'pagination' => [
        'pageSize' => -1
    ]
]);
echo TabularForm::widget([
    'dataProvider' => $dataProvider,
    'formName' => 'StudentDetails',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
        "id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden'=>true]],
        'campus_id' => [
            'label' => 'Campus',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Campus')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'user_id' => [
            'label' => 'User',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()->orderBy('username')->asArray()->all(), 'id', 'username'),
                'options' => ['placeholder' => Yii::t('app', 'Choose User')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'admission_number' => ['type' => TabularForm::INPUT_TEXT],
        'rool_number' => ['type' => TabularForm::INPUT_TEXT],
        'profile_photo' => ['type' => TabularForm::INPUT_TEXT],
        'student_name' => ['type' => TabularForm::INPUT_TEXT],
        'gender' => ['type' => TabularForm::INPUT_TEXT],
        'date_of_birth' => ['type' => TabularForm::INPUT_TEXT],
        'category' => ['type' => TabularForm::INPUT_TEXT],
        'religion' => ['type' => TabularForm::INPUT_TEXT],
        'caste' => ['type' => TabularForm::INPUT_TEXT],
        'phone_number' => ['type' => TabularForm::INPUT_TEXT],
        'student_class_id' => [
            'label' => 'Student class',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentClass::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Student class')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'section_id' => [
            'label' => 'Class sections',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\ClassSections::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Class sections')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'academic_year' => ['type' => TabularForm::INPUT_TEXT],
        'academic_year_id' => [
            'label' => 'Academic years',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\AcademicYears::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Academic years')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'hostal_is_required' => ['type' => TabularForm::INPUT_TEXT],
        'bus_transport_required' => ['type' => TabularForm::INPUT_TEXT],
        'email' => ['type' => TabularForm::INPUT_TEXT],
        'admission_date' => ['type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\datecontrol\DateControl::classname(),
            'options' => [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Admission Date'),
                        'autoclose' => true,
                    ]
                ],
            ]
        ],
        'blood_group_id' => [
            'label' => 'Blood groups',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BloodGroups::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Blood groups')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'student_house' => ['type' => TabularForm::INPUT_TEXT],
        'height' => ['type' => TabularForm::INPUT_TEXT],
        'weight' => ['type' => TabularForm::INPUT_TEXT],
        'current_address' => ['type' => TabularForm::INPUT_TEXTAREA],
        'permanent_address' => ['type' => TabularForm::INPUT_TEXTAREA],
        'national_Identification_number' => ['type' => TabularForm::INPUT_TEXT],
        'status' => ['type'=>TabularForm::INPUT_DROPDOWN_LIST, 
            'items'=>[1 => 'Active', 0 => 'Inactive', 2=>'Delete'],
            'columnOptions'=>['width'=>'185px']],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return
                    Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                    Html::a('<i class="fa fa-trash"></i>', '#', ['title' =>  Yii::t('app', 'Delete'), 'onClick' => 'delRowStudentDetails(' . $key . '); return false;', 'id' => 'student-details-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => false,
            'type' => GridView::TYPE_DEFAULT,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="fa fa-plus"></i>' . Yii::t('app', 'Add Student Details'), ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowStudentDetails()']),
        ]
    ]
]);
echo  "    </div>\n\n";
?>

