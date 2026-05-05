<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AgentStudentJoin */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agent Student Joins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-student-join-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Agent Student Join').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'campus.id',
            'label' => Yii::t('app', 'Campus'),
        ],
        [
            'attribute' => 'student.id',
            'label' => Yii::t('app', 'Student'),
        ],

        [
            'attribute' => 'agent.username',
            'label' => Yii::t('app', 'Agent'),
        ],
        'amount',

        [
            'attribute' => 'payment_receipt',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::img(
                    $model['payment_receipt'],
                    [
                        'width' => '200px',
                        'height' => '200px',
                    ]
                );
            },
    
        ],



   
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

