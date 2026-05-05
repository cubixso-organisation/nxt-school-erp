<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixLectures */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tutorix Lectures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tutorix-lectures-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Tutorix Lectures').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'class.name',
            'label' => Yii::t('app', 'Class'),
        ],
        [
            'attribute' => 'subject.name',
            'label' => Yii::t('app', 'Subject'),
        ],
        [
            'attribute' => 'section.name',
            'label' => Yii::t('app', 'Section'),
        ],
        'lecture_id',
        'name:ntext',
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
        <h4>TutorixClass<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnTutorixClass = [
        ['attribute' => 'id', 'visible' => false],
        'name:ntext',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->class,
        'attributes' => $gridColumnTutorixClass    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>TutorixSubjects<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnTutorixSubjects = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'class.name',
            'label' => Yii::t('app', 'Class'),
        ],
        'name:ntext',
    ];
    echo DetailView::widget([
        'model' => $model->subject,
        'attributes' => $gridColumnTutorixSubjects    ]);
    ?>
    </div>
    </div>
    <div class="card">
       <div class="card-body">
    <div class="row">
        <h4>TutorixSections<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnTutorixSections = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'class.name',
            'label' => Yii::t('app', 'Class'),
        ],
        [
            'attribute' => 'subject.name',
            'label' => Yii::t('app', 'Subject'),
        ],
        'name:ntext',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model->section,
        'attributes' => $gridColumnTutorixSections    ]);
    ?>
    </div>
    </div>
</div>

