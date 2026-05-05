<div class="form-group" id="add-bus-details">
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
    'formName' => 'BusDetails',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
        "id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden'=>true]],
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
        'title' => ['type' => TabularForm::INPUT_TEXT],
        'vehicle_number' => ['type' => TabularForm::INPUT_TEXT],
        'route_no' => ['type' => TabularForm::INPUT_TEXT],
        'start_point' => ['type' => TabularForm::INPUT_TEXT],
        'end_point' => ['type' => TabularForm::INPUT_TEXT],
        'start_point_lat' => ['type' => TabularForm::INPUT_TEXT],
        'start_point_lng' => ['type' => TabularForm::INPUT_TEXT],
        'end_point_lat' => ['type' => TabularForm::INPUT_TEXT],
        'end_point_lng' => ['type' => TabularForm::INPUT_TEXT],
        'status' => ['type' => TabularForm::INPUT_TEXT],
        'current_status' => ['type' => TabularForm::INPUT_TEXT],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return
                    Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                    Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', ['title' =>  Yii::t('app', 'Delete'), 'onClick' => 'delRowBusDetails(' . $key . '); return false;', 'id' => 'bus-details-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => false,
            'type' => GridView::TYPE_DEFAULT,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Add Bus Details'), ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowBusDetails()']),
        ]
    ]
]);
echo  "    </div>\n\n";
?>

