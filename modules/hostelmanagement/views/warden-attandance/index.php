<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hostelmanagement\models\search\WardenAttandanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\base\Campus;
use app\modules\hostelmanagement\models\base\Hostels;
use app\modules\hostelmanagement\models\base\WardenAttandance;
use app\modules\hostelmanagement\models\HostellersAttandance;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Warden Attandances');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
$model = new WardenAttandance();

?>
<div class="warden-attandance-index">

    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id', 'visible' => false],

                [
                    'attribute' => 'campus_id',
                    'label' => Yii::t('app', 'Campus'),
                    'value' => function ($model) {
                        return $model->campus->name_of_the_educational_Institution;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(Campus::find()->Where(['id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-warden-attandance-search-campus_id']
                ],

                // 'hostel_id',
                [
                    'attribute' => 'hostel_id',
                    'label' => Yii::t('app', 'Hostel'),
                    'value' => function ($model) {
                        $hostel = Hostels::find()->where(['id' => $model->hostel_id])->one();
                        if (!empty($hostel)) {
                            return $hostel->name;
                        } else {
                            return "Not Set";
                        }
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Hostels::find()->Where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Hostels', 'id' => 'grid-hostellers-attandance-search-hostel_id']
                ],

                [
                    'attribute' => 'warden_id',
                    'label' => Yii::t('app', 'Warden'),
                    'value' => function ($model) {
                        return $model->warden ? $model->warden->username : 'N/A'; // Handle null by returning 'N/A'
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(
                        \app\models\User::find()
                            ->where(['id' => $model->warden ? $model->warden->id : null]) // Ensure the warden is not null
                            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                            ->asArray()
                            ->all(),
                        'id',
                        'username'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-warden-attendance-search-warden_id'],
                ],

                [
                    'attribute' => 'attandance',
                    'filter' => (new WardenAttandance)->getAttendanceOptions(),
                    'format' => 'raw',
                    'value' => function ($data) {
                        $html = '';

                        $html .= '<select class="form-group" id="status_list_' . $data->id . '" data-id="' . $data->id . '" >';
                        $lists = $data->getAttendanceOptions();

                        foreach ($lists as $key => $list) {

                            if ($key == $data->attandance) {
                                $html .= '<option value="' . $key . '" selected>' . $list . '</option>';
                            } else {
                                $html .= '<option value="' . $key . '">' . $list . '</option>';
                            }
                        }
                        $html .= '</select>';

                        return $html;
                    }
                ],

                [
                    'attribute' => 'date',
                    'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date',
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                    ]),
                ],
                [
                    'attribute' => 'attandance_by',
                    'label' => Yii::t('app', 'Attendance By'),
                    'value' => function ($model) {
                        return $model->attandanceBy->first_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(User::find()->asArray()->all(), 'id', 'first_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Name', 'id' => 'grid-hostellers-attandance-search-attandance_by']
                ],

                // [
                //         'attribute' => 'status',
                //         'format' => 'raw',
                //         'value' => function($model){                   
                //             return $model->getStateOptionsBadges();                   
                //         },


                //     ],

            ];
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-warden-attandance']],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                ],
                'export' => false,
                // your toolbar can include the additional full export menu
                'toolbar' => [
                    '{export}',
                    ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $gridColumn,
                        'target' => ExportMenu::TARGET_BLANK,
                        'fontAwesome' => true,
                        'dropdownOptions' => [
                            'label' => 'Full',
                            'class' => 'btn btn-default',
                            'itemsBefore' => [
                                '<li class="dropdown-header">Export All Data</li>',
                            ],
                        ],
                        'exportConfig' => [
                            ExportMenu::FORMAT_PDF => false
                        ]
                    ]),
                ],
            ]); ?>
        </div>
    </div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();

        $.ajax({
            type: "POST",

            url: "/Estudent_backend/gii/default/status-change",


            data: {
                id: id,
                val: val
            },
            success: function(data) {
                swal("Good job!", "Status Successfully Changed!", "success");
            }
        });
    });
</script>


<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();
        $.ajax({
            type: "POST",

            url: "<?= Url::toRoute(['status-change']) ?>",


            data: {
                id: id,
                val: val
            },
            success: function(data) {
                swal("Good job!", "Status Successfully Changed!", "success");
            }
        });
    });
</script>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();
        // alert(val);

        $.ajax({
            type: "POST",

            url: "<?= Url::toRoute(['status-change']) ?>",

            data: {
                id: id,
                val: val
            },
            success: function(data) {
                swal("Good job!", "Status Successfully Changed!", "success");
            }
        });
    });
</script>