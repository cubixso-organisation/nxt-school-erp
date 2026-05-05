<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\exammanagement\models\Grade */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Grades'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-view">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-9">
                    <h2><?= Yii::t('app', 'Grade') . ' ' . Html::encode($this->title) ?></h2>
                </div>
                <div class="col-sm-3 text-right">
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) { ?>
                        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $gridColumn = [
                        ['attribute' => 'id', 'visible' => false],
                        'maximum_exam_marks',
                        'status',
                    ];
                    echo DetailView::widget([
                        'model' => $model,
                        'attributes' => $gridColumn,
                        'options' => ['class' => 'table table-bordered']
                    ]);
                    ?>
                </div>
                <div class="col-sm-6">
                    <h4>Class Sections <?= ' ' . Html::encode($this->title) ?></h4>
                    <?php
                    $gridColumnClassSections = [
                        ['attribute' => 'id', 'visible' => false],
                        'campus_id',
                        'student_class_id',
                        'section_name',
                        'status',
                    ];
                    echo DetailView::widget([
                        'model' => $model->section,
                        'attributes' => $gridColumnClassSections,
                        'options' => ['class' => 'table table-bordered']
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    if ($providerGradeDefination->totalCount) {
                        $gridColumnGradeDefination = [
                            ['class' => 'yii\grid\SerialColumn'],
                            'max_marks',
                            'min_marks',
                            'grade',
                            'cgpa',
                        ];
                        echo GridView::widget([
                            'dataProvider' => $providerGradeDefination,
                            'pjax' => true,
                            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-grade-defination']],
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Grade Definition')),
                            ],
                            'export' => false,
                            'columns' => $gridColumnGradeDefination,
                        ]);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>