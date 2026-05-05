<div class="form-group" id="add-student-attendance-bus">
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
    'formName' => 'StudentAttendanceBus',
    'checkboxColumn' => false,
    'actionColumn' => false,
    'attributeDefaults' => [
        'type' => TabularForm::INPUT_TEXT,
    ],
    'attributes' => [
        "id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden'=>true]],
        'bus_route_id' => [
            'label' => 'Bus route',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusRoute::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Bus route')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'student_has_bus_id' => [
            'label' => 'Student has bus',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\StudentHasBus::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Student has bus')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'actual_pickup_point' => [
            'label' => 'Bus route',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusRoute::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Bus route')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'student_picked_up_point' => [
            'label' => 'Bus route',
            'type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\widgets\Select2::className(),
            'options' => [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\BusRoute::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Bus route')],
            ],
            'columnOptions' => ['width' => '200px']
        ],
        'status' => ['type' => TabularForm::INPUT_TEXT],
        'student_status' => ['type' => TabularForm::INPUT_TEXT],
        'unique_key' => ['type' => TabularForm::INPUT_TEXT],
        'session_key' => ['type' => TabularForm::INPUT_TEXT],
        'remarks' => ['type' => TabularForm::INPUT_TEXT],
        'pickup_point_time' => ['type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\datecontrol\DateControl::classname(),
            'options' => [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Pickup Point Time'),
                        'autoclose' => true,
                    ]
                ],
            ]
        ],
        'school_reached_time' => ['type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\datecontrol\DateControl::classname(),
            'options' => [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose School Reached Time'),
                        'autoclose' => true,
                    ]
                ],
            ]
        ],
        'school_left_time' => ['type' => TabularForm::INPUT_TEXT],
        'home_reached_time' => ['type' => TabularForm::INPUT_WIDGET,
            'widgetClass' => \kartik\datecontrol\DateControl::classname(),
            'options' => [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Home Reached Time'),
                        'autoclose' => true,
                    ]
                ],
            ]
        ],
        'del' => [
            'type' => 'raw',
            'label' => '',
            'value' => function($model, $key) {
                return
                    Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                    Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', ['title' =>  Yii::t('app', 'Delete'), 'onClick' => 'delRowStudentAttendanceBus(' . $key . '); return false;', 'id' => 'student-attendance-bus-del-btn']);
            },
        ],
    ],
    'gridSettings' => [
        'panel' => [
            'heading' => false,
            'type' => GridView::TYPE_DEFAULT,
            'before' => false,
            'footer' => false,
            'after' => Html::button('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Add Student Attendance Bus'), ['type' => 'button', 'class' => 'btn btn-success kv-batch-create', 'onClick' => 'addRowStudentAttendanceBus()']),
        ]
    ]
]);
echo  "    </div>\n\n";
?>

