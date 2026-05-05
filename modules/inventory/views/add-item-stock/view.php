<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\AddItemStock */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Add Item Stocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="add-item-stock-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Add Item Stock').' '. Html::encode($this->title) ?></h2>
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
        [
            'attribute' => 'itemCategory.item_category',
            'label' => Yii::t('app', 'Item Category'),
        ],
        [
            'attribute' => 'itemSupplierList.name',
            'label' => Yii::t('app', 'Item Supplier List'),
        ],
        [
            'attribute' => 'itemStore.id',
            'label' => Yii::t('app', 'Item Store'),
        ],
        [
            'attribute' => 'inventoryItems.id',
            'label' => Yii::t('app', 'Inventory Items'),
        ],
        'quantity',
        'purchase_price',
        'date',
        'attach_document',
        'description:ntext',
        'status',
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
        'description:ntext',
        'status',
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
        <h4>ItemSupplierList<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnItemSupplierList = [
        ['attribute' => 'id', 'visible' => false],
        'name',
        'phone',
        'email',
        'address',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
        'description:ntext',
    ];
    echo DetailView::widget([
        'model' => $model->itemSupplierList,
        'attributes' => $gridColumnItemSupplierList    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>ItemStore<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnItemStore = [
        ['attribute' => 'id', 'visible' => false],
        'item_store_name',
        'item_store_code',
        'description:ntext',
    ];
    echo DetailView::widget([
        'model' => $model->itemStore,
        'attributes' => $gridColumnItemStore    ]);
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
        'description:ntext',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->inventoryItems,
        'attributes' => $gridColumnInventoryItems    ]);
    ?>
    </div>
    </div>
</div>

