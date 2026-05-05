<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentDetailsAgentLead */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Details Agent Leads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-details-agent-lead-view">
<div class="card">
       <div class="card-body">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Student Details Agent Lead').' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'agent.username',
            'label' => Yii::t('app', 'Agent'),
        ],
        'profile_photo',
        'student_name',
        'gender',
        'date_of_birth',
        'name_of_the_parent',
        'phone_number',
        'verified_phone',
        'previous_school_name:ntext',
        'previous_school_address',
        [
            'attribute' => 'studentClass.title',
            'label' => Yii::t('app', 'Student Class'),
        ],
        'special_courses_id',
        [
            'attribute' => 'section.id',
            'label' => Yii::t('app', 'Section'),
        ],
        'academic_year',
        'hostal_is_required',
        'bus_transport_required',
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



</div>

