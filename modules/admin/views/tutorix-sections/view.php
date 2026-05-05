<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixSections */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tutorix Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tutorix-sections-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Tutorix Sections').' '. Html::encode($this->title) ?></h2>
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
        'section_id',
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
<?php
if($providerTutorixLectures->totalCount){
    $gridColumnTutorixLectures = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'class.name',
                'label' => Yii::t('app', 'Class')
            ],
            [
                'attribute' => 'subject.name',
                'label' => Yii::t('app', 'Subject')
            ],
                        'lecture_id',
            'name:ntext',
            'status',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerTutorixLectures,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-tutorix-lectures']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Tutorix Lectures')),
        ],
        'export' => false,
        'columns' => $gridColumnTutorixLectures
    ]);
}

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
</div>

