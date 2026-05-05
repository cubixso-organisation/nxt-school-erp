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
        'institute_id' => [
            'label' => 'Institutes',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Institutes::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Institutes')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
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
        'profile_photo' => ['type' => TabularForm::INPUT_TEXT],
        'student_name' => ['type' => TabularForm::INPUT_TEXT],
        'gender' => ['type' => TabularForm::INPUT_TEXT],
        'date_of_birth' => ['type' => TabularForm::INPUT_TEXT],
        'name_of_the_parent' => ['type' => TabularForm::INPUT_TEXT],
        'phone_number' => ['type' => TabularForm::INPUT_TEXT],
        'verified_phone' => ['type' => TabularForm::INPUT_CHECKBOX,
            'options' => [
                'style' => 'position : relative; margin-top : -9px'
            ]
        ],
        'previous_school_name' => ['type' => TabularForm::INPUT_TEXTAREA],
        'previous_school_address' => ['type' => TabularForm::INPUT_TEXT],
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
        'hostal_is_required' => ['type' => TabularForm::INPUT_TEXT],
        'bus_transport_required' => ['type' => TabularForm::INPUT_TEXT],
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

