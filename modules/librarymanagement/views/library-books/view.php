<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\LibraryBooks */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Library Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="library-books-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Library Books').' '. Html::encode($this->title) ?></h2>
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
        'book_title',
        
        [
            'attribute' => 'description',
            'label' => Yii::t('app', 'Description'),
            'format' => 'raw',
            'value' => function ($model) {
                return $model->description;
            }
        ],
        'book_number',
        'isbn_number',
        'publisher',
        'author',
        'subject',
        [
            'attribute' => 'rackNumber.rack_number',
            'label' => Yii::t('app', 'Rack Number'),
        ],
        'qty',
        'available',
        'book_price',
        'campus_id',
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
        <h4>LibraryRacks<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnLibraryRacks = [
        ['attribute' => 'id', 'visible' => false],
        'rack_location',
    ];
    echo DetailView::widget([
        'model' => $model->rackNumber,
        'attributes' => $gridColumnLibraryRacks]);
    ?>
    </div>
    </div>
</div>

