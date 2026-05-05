<div class="form-group" id="add-campus">
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
    'formName' => 'Campus',
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
        'name_of_the_educational_Institution' => ['type' => TabularForm::INPUT_TEXT],
        'educational_institution_type_id' => [
            'label' => 'Educational institution types',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\EducationalInstitutionTypes::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Educational institution types')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'user_id' => ['type' => TabularForm::INPUT_TEXT],
        'country_id' => [
            'label' => 'Country',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Country::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Country')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'district_id' => [
            'label' => 'District',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\District::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose District')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'pincode' => ['type' => TabularForm::INPUT_TEXT],
        'address' => ['type' => TabularForm::INPUT_TEXTAREA],
        'campus_code' => ['type' => TabularForm::INPUT_TEXT],
        'registration_number' => ['type' => TabularForm::INPUT_TEXT],
        'registration_document' => ['type' => TabularForm::INPUT_TEXT],
        'name_of_the_authorized' => ['type' => TabularForm::INPUT_TEXT],
        'designation_of_the_authorized' => ['type' => TabularForm::INPUT_TEXT],
        'contact_number_of_the_authorized' => ['type' => TabularForm::INPUT_TEXT],
        'name_of_the_contact' => ['type' => TabularForm::INPUT_TEXT],
        'designation_of_the_contact' => ['type' => TabularForm::INPUT_TEXT],
        'contact_number_of_the_contact' => ['type' => TabularForm::INPUT_TEXT],
        'email_id_of_the_authorized' => ['type' => TabularForm::INPUT_TEXT],
        'aadhaar_of_the_authorized' => ['type' => TabularForm::INPUT_TEXT],
        'subscription_type' => ['type' => TabularForm::INPUT_TEXT],
        'activation_modules' => ['type' => TabularForm::INPUT_TEXT],
        'lat' => ['type' => TabularForm::INPUT_TEXT],
        'lng' => ['type' => TabularForm::INPUT_TEXT],
        'coordinates' => ['type' => TabularForm::INPUT_TEXT],
        'city' => ['type' => TabularForm::INPUT_TEXT],
        'status' => ['type' => TabularForm::INPUT_TEXT],
        'school_logo' => ['type' => TabularForm::INPUT_TEXT],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return
                    Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                    Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', ['title' =>  Yii::t('app', 'Delete'), 'onClick' => 'delRowCampus(' . $key . '); return false;', 'id' => 'campus-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => false,
            'type' => GridView::TYPE_DEFAULT,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Add Campus'), ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowCampus()']),
        ]
    ]
]);
echo  "    </div>\n\n";
?>

