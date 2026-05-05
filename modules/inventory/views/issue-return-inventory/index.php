<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\inventory\models\search\IssueReturnInventorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Issue Return Inventories');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="issue-return-inventory-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) { ?>
                    <?= Html::a(Yii::t('app', 'Issue Item'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
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

                'user_type',

                [
                    'attribute' => 'issue_to',
                    'value' => function ($model) {
                        return $model->issueTo->username;
                    }
                ],
                [
                    'attribute' => 'issue_by',
                    'value' => function ($model) {
                        return $model->issueBy->user_role;
                    }
                ],

                'issue_date',

                'expected_return_date',

                [
                    'attribute' => 'return_date',
                    'format' => 'raw', // Allows rendering HTML
                    'value' => function ($model) {
                        return isset($model->return_date) ? Yii::$app->formatter->asDate($model->return_date) : 'Not Returned';
                    },
                ],

                'note:ntext',

                [
                    'attribute' => 'item_category_id',
                    'label' => Yii::t('app', 'Item Category'),
                    'value' => function ($model) {
                        return $model->itemCategory->item_category;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\inventory\models\ItemCategory::find()->asArray()->all(), 'id', 'item_category'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Item category', 'id' => 'grid-issue-return-inventory-search-item_category_id']
                ],

                [
                    'attribute' => 'inventory_items_id',
                    'label' => Yii::t('app', 'Inventory Items'),
                    'value' => function ($model) {
                        return $model->inventoryItems->item_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(\app\modules\inventory\models\InventoryItems::find()->asArray()->all(), 'id', 'id'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Inventory items', 'id' => 'grid-issue-return-inventory-search-inventory_items_id']
                ],

                'quantity',

                [
                    'attribute' => 'status',
                    'filter'  =>  app\modules\inventory\models\base\IssueReturnInventory::getStateOptions(),
                    "format" => 'raw',
                    'value' => function ($data) {
                        $html = '';


                        $html .= '<select id="status_list_' . $data->id . '" data-id="' . $data->id . '" ' . ($data->status == 2 ? 'disabled' : '') . '>';

                        $lists = $data->getStateOptions();

                        foreach ($lists as $key => $list) {

                            if ($key == $data->status) {
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
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                                return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                            }
                        },
                        'update' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                                return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                                return Html::a('<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url, [
                                    'data' => [
                                        'method' => 'post',
                                        // use it if you want to confirm the action
                                        'confirm' => 'Are you sure?',
                                    ],
                                ]);
                            }
                        },


                    ]



                ],
            ];
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-issue-return-inventory']],
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).on('change', 'select[id^=status_list_]', function() {
        var id = $(this).attr('data-id');
        var val = $(this).val();

        $.ajax({
            type: "POST",
            url: "<?= Url::toRoute(['issue-return-inventory/status-change']) ?>",
            data: {
                id: id,
                val: val
            },
            success: function(data) {
                swal("Good job!", "Status Successfully Changed!", "success");


                if (val === '2') {

                    $('#status_list_' + id).prop('disabled', true);
                } else {

                    $('#status_list_' + id).prop('disabled', false);
                }
            }
        });
    });
</script>
