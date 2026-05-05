<div class="form-group" id="add-staff-details">
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
        'formName' => 'StaffDetails',
        'checkboxColumn' => false,
        'actionColumn' => false,
        'attributeDefaults' => [
            'type' => TabularForm::INPUT_TEXT,
        ],
        'attributes' => [
            'id' => ['type' => TabularForm::INPUT_HIDDEN],
            'name' => ['type' => TabularForm::INPUT_TEXT],
            'campus_id' => [
                'label' => 'Campus',
                'type' => TabularForm::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\Select2::className(),
                'options' => [
                    'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                    'options' => ['placeholder' => 'Choose Campus'],
                ],
                'columnOptions' => ['width' => '200px']
            ],
            // 'payroll_id' => [
            //     'label' => 'Payroll',
            //     'type' => TabularForm::INPUT_WIDGET,
            //     'widgetClass' => \kartik\widgets\Select2::className(),
            //     'options' => [
            //         'data' => \yii\helpers\ArrayHelper::map(\app\modules\staffmanagement\models\Payroll::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
            //         'options' => ['placeholder' => 'Choose Payroll'],
            //     ],
            //     'columnOptions' => ['width' => '200px']
            // ],
            'aadhar_card' => ['type' => TabularForm::INPUT_TEXT],
            'pan_card' => ['type' => TabularForm::INPUT_TEXT],
            'status' => [
                'type' => TabularForm::INPUT_DROPDOWN_LIST,
                'items' => [1 => 'Active', 0 => 'In Active', 2 => 'Delete'],
                'columnOptions' => ['width' => '185px']
            ],
            'update_create_id' => ['type' => TabularForm::INPUT_TEXT],
            'del' => [
                'type' => 'raw',
                'label' => '',
                'value' => function ($model, $key) {
                    return
                        Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                        Html::a('<i class="fa fa-trash"></i>', '#', ['title' =>  'Delete', 'onClick' => 'delRowStaffDetails(' . $key . '); return false;', 'id' => 'staff-details-del-btn']);
                },
            ],
        ],
        'gridSettings' => [
            'panel' => [
                'heading' => false,
                'type' => GridView::TYPE_DEFAULT,
                'before' => false,
                'footer' => false,
                'after' => Html::button('<i class="fa fa-plus"></i>' . 'Add Staff Details', ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowStaffDetails()']),
            ]
        ]
    ]);
    echo  "    </div>\n\n";
    ?>