<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\EmployeeDetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Driver Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-details-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Driver Details').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a(Yii::t('app', 'Update'), ['driver-update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                          <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_SUBADMIN){ ?>
           
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
            'attribute' => 'user.username',
            'label' => Yii::t('app', 'User'),
        ],
    
        // [
        //     'attribute' => 'designation.title',
        //     'label' => Yii::t('app', 'Designation'),
        // ],
        // 'employ_name',

        [
            'attribute' => 'employ_name',
            'label' => Yii::t('app', 'Driver Name'),
            'value' => function($model){                   
                return $model->employ_name;                   
            },
           
        ],



        // 'profile_picture',
        // 'employee_id',
        'age',
        // [
        //     'attribute' => 'bloodGroup.title',
        //     'label' => Yii::t('app', 'Blood Group'),
        // ],
        'phone_number',
        'license_number',
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

