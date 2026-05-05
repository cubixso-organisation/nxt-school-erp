<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\IssueReturnInventory */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Issue Return Inventories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-return-inventory-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Issue Return Inventory').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                          <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUBADMIN){ ?>
             <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>  
             <?php  } ?>
        </div>
    </div>
    </div>
    </div>
    <div class="card">
       <div class="card-body">

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        'user_type',
        ['attribute' => 'issue_to',
        'value' => function ($model){
            return $model->issueTo->username;
        }
    ],
        [
            'attribute' => 'issue_by',
            'value' => function ($model){
                return $model->issueBy->username;
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
            'attribute' => 'itemCategory.item_category',
            'label' => Yii::t('app', 'Item Category'),
        ],
        [
            'attribute' => 'inventoryItems.item_name',
            'label' => Yii::t('app', 'Inventory Items'),
        ],
        'quantity',
        [
            'attribute' => 'status',
            'label' => Yii::t('app', 'Issue Status'),
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getStateOptionsBadges();
            }
        ],
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
</div>
</div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>ItemCategory<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnItemCategory = [
        ['attribute' => 'id', 'visible' => false],
        'item_category',
        'description',
        [
            'attribute' => 'status',
            'label' => Yii::t('app', 'Issue Status'),
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getStateOptionsBadges();
            }
        ],
    ];
    echo DetailView::widget([
        'model' => $model->itemCategory,
        'attributes' => $gridColumnItemCategory    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>InventoryItems<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnInventoryItems = [
        ['attribute' => 'id', 'visible' => false],
        'item_name',
        [
            'attribute' => 'itemCategory.item_category',
            'label' => Yii::t('app', 'Item Category'),
        ],
        // 'quantity',
        'available_quantity',
        'description',
        [
            'attribute' => 'status',
            'label' => Yii::t('app', 'Issue Status'),
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getStateOptionsBadges();
            }
        ],
    ];
    echo DetailView::widget([
        'model' => $model->inventoryItems,
        'attributes' => $gridColumnInventoryItems    ]);
    ?>
    </div>
    </div>
</div>

