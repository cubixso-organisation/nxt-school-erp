<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;
use app\modules\librarymanagement\models\base\IssueBooks;
use app\modules\librarymanagement\models\LibraryBooks;

/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\IssueBooks */

$model = IssueBooks::find()->where(['id' => (int)yii::$app->request->get('id')])->one();
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Issue Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-books-view">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-9">
                    <h2><?= Yii::t('app', 'Issue Books') . ' ' . Html::encode($this->title) ?></h2>
                </div>
                <div class="col-sm-3" style="margin-top: 15px">

                   <?php /* echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) */ ?>
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
                    'library_id',
                    [
                        'attribute' => 'book.book_title',
                        'label' => Yii::t('app', 'Book'),
                    ],
                    [
                        'attribute' => 'status',
                        'label' => Yii::t('app', 'Issue Status'),
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->getStateOptionsBadges();
                        }
                    ],
                    [
                        'attribute' => 'author',
                        'label' => Yii::t('app', 'Author'),
                    ],
                    [
                        'attribute' => 'subject_code',
                        'label' => Yii::t('app', 'Subject Code'),
                    ],
                    [
                        'attribute' => 'serial_no',
                        'label' => Yii::t('app', 'Serial No'),
                    ],
                    'due_date',
                    'note',
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