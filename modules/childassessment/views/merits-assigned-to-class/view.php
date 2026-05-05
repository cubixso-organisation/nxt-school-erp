<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\childassessment\models\MeritsAssignedToClass */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Merits Assigned To Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merits-assigned-to-class-view">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-9">
                    <h2><?= 'Merits Assigned To Class' . ' ' . Html::encode($this->title) ?></h2>
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
                        'attribute' => 'class.title',
                        'label' => 'Class',

                    ],

                    [
                        'attribute' => 'section_id',
                        'label' => 'Section',
                        'value' => function ($model) {
                            return $model->section->section_name;
                        }
                    ],
                    [
                        'attribute' => 'merit.name',
                        'label' => 'Merit',
                    ],
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