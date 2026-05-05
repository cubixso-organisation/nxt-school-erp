<div class="form-group" id="add-tutorix-sections">
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
    'formName' => 'TutorixSections',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
        "id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden'=>true]],
        'subject_id' => [
            'label' => 'Tutorix subjects',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\TutorixSubjects::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Tutorix subjects')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'section_id' => ['type' => TabularForm::INPUT_TEXT],
        'name' => ['type' => TabularForm::INPUT_TEXTAREA],
        'status' => ['type'=>TabularForm::INPUT_DROPDOWN_LIST, 
            'items'=>[1 => 'Active', 0 => 'In Active', 2=>'Delete'],
            'columnOptions'=>['width'=>'185px']],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return
                    Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                    Html::a('<i class="fa fa-trash"></i>', '#', ['title' =>  Yii::t('app', 'Delete'), 'onClick' => 'delRowTutorixSections(' . $key . '); return false;', 'id' => 'tutorix-sections-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => false,
            'type' => GridView::TYPE_DEFAULT,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="fa fa-plus"></i>' . Yii::t('app', 'Add Tutorix Sections'), ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowTutorixSections()']),
        ]
    ]
]);
echo  "    </div>\n\n";
?>

