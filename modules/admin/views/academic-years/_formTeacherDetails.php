<div class="form-group" id="add-teacher-details">
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
    'formName' => 'TeacherDetails',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
        "id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden'=>true]],
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
        'name' => ['type' => TabularForm::INPUT_TEXT],
        'profile_image' => ['type' => TabularForm::INPUT_TEXT],
        'class_id' => [
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
            'label' => 'Student details agent lead',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentDetailsAgentLead::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Student details agent lead')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'id_number' => ['type' => TabularForm::INPUT_TEXT],
        'date_of_birth' => ['type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\datecontrol\DateControl::classname(),
            'options' => [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                'saveFormat' => 'php:Y-m-d',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Date Of Birth'),
                        'autoclose' => true
                    ]
                ],
            ]
        ],
        'gender' => ['type' => TabularForm::INPUT_TEXT],
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
        'father_name' => ['type' => TabularForm::INPUT_TEXT],
        'contact_number' => ['type' => TabularForm::INPUT_TEXT],
        'email' => ['type' => TabularForm::INPUT_TEXT],
        'address' => ['type' => TabularForm::INPUT_TEXTAREA],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return
                    Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                    Html::a('<i class="fa fa-trash"></i>', '#', ['title' =>  Yii::t('app', 'Delete'), 'onClick' => 'delRowTeacherDetails(' . $key . '); return false;', 'id' => 'teacher-details-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => false,
            'type' => GridView::TYPE_DEFAULT,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="fa fa-plus"></i>' . Yii::t('app', 'Add Teacher Details'), ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowTeacherDetails()']),
        ]
    ]
]);
echo  "    </div>\n\n";
?>

