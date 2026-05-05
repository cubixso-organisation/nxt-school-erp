<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\NoticeBoards */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notice Boards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-boards-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Notice Boards').' '. Html::encode($this->title) ?></h2>
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
        'title',
        [
            'attribute' => 'description',
            'format'=>'raw',
            'label' => Yii::t('app', 'description'),
            'value' => function($model){                   
                return $model->description;                   
            },
       
        ],

  
        [
            'attribute' => 'section_id',
            'label' => Yii::t('app', 'Section'),
            'value' => function($model){                   
                $section_name_class =  $model->section->studentClass->title.'-'.$model->section->section_name;  
                
             return $section_name_class;                 

            },
        
        ],

        
        'expiry_date',
        [




                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){                   
                    return $model->getStateOptionsBadges();                   
                },
               
               
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



</div>

