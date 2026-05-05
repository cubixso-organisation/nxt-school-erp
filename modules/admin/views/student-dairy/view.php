<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentDairy */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Dairies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-dairy-view">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-9">
                    <h2><?= Yii::t('app', 'Student Dairy') . ' ' . Html::encode($this->title) ?></h2>
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
                    [
                        'attribute' => 'campus.id',
                        'label' => Yii::t('app', 'Campus'),
                    ],
                    [
                        'attribute' => 'teacherDetails.name',
                        'label' => Yii::t('app', 'Teacher Details'),
                    ],
                    [
                        'attribute' => 'subjectTimetable.id',
                        'label' => Yii::t('app', 'Subject Timetable'),
                    ],
                    [
                        'attribute' => 'academicYear.title',
                        'label' => Yii::t('app', 'Academic Year'),
                    ],
                    [
                        'attribute' => 'class.title',
                        'label' => Yii::t('app', 'Class'),
                    ],
                    [
                        'attribute' => 'section.id',
                        'label' => Yii::t('app', 'Section'),
                    ],
                    [
                        'attribute' => 'subject.id',
                        'label' => Yii::t('app', 'Subject'),
                    ],
                    'dairy:ntext',
                    'remarks:ntext',
                    'submission_date',
                    [
                        'attribute' => 'document',
                        'label' => Yii::t('app', 'Document'),
                        'format' => 'html',
                        'value' => function ($model) {
                            $button = '';
                            if (!empty($model->document)) {
                                $button = "<a href='" . $model->document . "' target='_blank'  download class='btn btn-success'>Download</a>";
                            }
                            return $button;
                        }
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
</div>

</div>