<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\OnlineAssessment */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Online Assessments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="online-assessment-view">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-9">
                    <h2><?= 'Online Assessment' . ' ' . Html::encode($this->title) ?></h2>
                </div>
                <div class="col-sm-3" style="margin-top: 15px">

                    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) { ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
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
                        'label' => 'Campus',
                    ],
                    [
                        'attribute' => 'subject.id',
                        'label' => 'Subject',
                    ],
                    [
                        'attribute' => 'academicYear.title',
                        'label' => 'Academic Year',
                    ],
                    'section_id',
                    'title',
                    'duration',
                    'total_marks',
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