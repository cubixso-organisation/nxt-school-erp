<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hostelmanagement\models\search\HostellersAttandanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\hostelmanagement\models\base\HostellersAttandance;
use app\modules\hostelmanagement\models\base\Hostels;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
use yii\bootstrap4\BootstrapAsset;
use yii\helpers\Url;
use yii\jui\JuiAsset;
use yii\web\YiiAsset;

YiiAsset::register($this);
BootstrapAsset::register($this);
JuiAsset::register($this);
$this->title = Yii::t('app', 'Hostellers Attandances');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="hostellers-attandance-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            if (empty($index)) {

            ?>

                <p>

                    <?= Html::a('Generate Today Attendance', ['generate-today-attendance'], ['class' => 'btn btn-info']) ?>
                </p>

            <?php } ?>
            </p>
            <div class="search-form" style="display:none">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
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
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Campus::find()->Where(['id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
                        ->asArray()->all(), 'id', 'name_of_the_educational_Institution'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Campus', 'id' => 'grid-hostellers-attandance-search-campus_id']
                ],

                [
                    'attribute' => 'hostel_id',
                    'label' => Yii::t('app', 'Hostel'),
                    'value' => function ($model) {
                        return $model->hostel->name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Hostels::find()->Where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Hostels', 'id' => 'grid-hostellers-attandance-search-hostel_id']
                ],
                'campus_id',
                [
                    'attribute' => 'student_id',
                    'label' => Yii::t('app', 'Student Name'),
                    'value' => function ($model) {
                        return isset($model->student->first_name)? $model->student->first_name:'';
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(User::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'first_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Name', 'id' => 'grid-hostellers-attendance-search-student_id']
                ],
                

                // 'student_id',

                [
                    'attribute' => 'room_id',
                    'label' => Yii::t('app', 'Room'),
                    'value' => function ($model) {
                        return $model->room->name_of_the_room;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\hostelmanagement\models\Rooms::find()->joinWith(['hostel as ho'])->Where(['ho.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->asArray()->all(), 'id', 'name_of_the_room'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Rooms', 'id' => 'grid-hostellers-attandance-search-room_id']
                ],


                [
                    'attribute' => 'attandance',
                    'filter' => (new HostellersAttandance)->getAttendanceOptions(),
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
                ///end now status



                // [


                //     'attribute' => 'attandance',
                //     "format" => 'raw',
                //     'label' => Yii::t('app', 'attendance'),


                //     'value' => function ($model) {
                //         return $model->getAttendanceOptionsBadges();
                //     },
                //     'filter'  => (new HostellersAttandance())->getAttendanceOptions(),
                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filterWidgetOptions' => [
                //         'pluginOptions' => ['allowClear' => true],
                //     ],
                //     'filterInputOptions' => ['placeholder' => 'attendance', 'id' => 'grid-state-search-attendance'],





                // ],


                    [
                        'attribute' => 'date',
                        'format' => ['datetime', 'php:Y-m-d H:i:s'],
                        'filter' => \yii\jui\DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date',
                            'options' => ['class' => 'form-control'],
                            'dateFormat' => 'yyyy-MM-dd',
                        ]),
                    ],


                [
                    'attribute' => 'attandance_by',
                    'label' => Yii::t('app', 'Attendance By'),
                    'value' => function ($model) {
                        return $model->attandanceBy->first_name??"";
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(User::find()->Where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['user_role' => User::ROLE_CHEF_WARDEN])->orWhere(['user_role' => User::ROLE_WARDEN])->asArray()->all(), 'id', 'first_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Name', 'id' => 'grid-hostellers-attandance-search-attandance_by']
                ],


                // [
                //     'class' => 'kartik\grid\ActionColumn',
                //     'template' => '{view} {update} {delete}',
                //     'buttons' => [
                //         'view' => function ($url, $model) {
                //             if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                //                 return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                //             }
                //         },
                //         'update' => function ($url, $model) {
                //             if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                //                 return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                //             }
                //         },
                //         'delete' => function ($url, $model) {
                //             if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                //                 return Html::a('<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url, [
                //                     'data' => [
                //                         'method' => 'post',
                //                         // use it if you want to confirm the action
                //                         'confirm' => 'Are you sure?',
                //                     ],
                //                 ]);
                //             }
                //         },


                //     ]



                // ],
            ];
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-hostellers-attandance']],
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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