<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\documentgenerator\models\Studentcertificates */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Studentcertificates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="studentcertificates-view">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-9">
                    <h2><?= Yii::t('app', 'Studentcertificates') . ' ' . Html::encode($this->title) ?></h2>
                </div>
                <div class="col-sm-3" style="margin-top: 15px">

                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) { ?>
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
                    'certificate_name',
                    'header_left_text:ntext',
                    'header_center_text:ntext',
                    'header_right_text:ntext',
                    'body_text:ntext',
                    'footer_left_text:ntext',
                    'footer_center_text:ntext',
                    'footer_right_text:ntext',
                    // 'certificate_design:ntext',
                    // 'header_height',
                    // 'footer_height',
                    // 'body_height',
                    // 'body_width',
                    // 'student_image',
                    // 'background_image',
                    [
                        'attribute' => 'student_image',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::img($model->student_image, ['class' => 'img-thumbnail', 'alt' => 'Student Image', 'width' => '80', 'height' => '80']);
                        },
                    ],
                    
                    [
                        'attribute' => 'background_image',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::img($model->background_image, ['class' => 'img-thumbnail', 'alt' => 'Background Image', 'width' => '80', 'height' => '80']);
                        },
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





