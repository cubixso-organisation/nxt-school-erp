<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SpecialCourses */

$this->title = Yii::t('app', 'Create Special Courses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Special Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="special-courses-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_special_courcess_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
