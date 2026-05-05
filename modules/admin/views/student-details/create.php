<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentDetails */

$this->title = Yii::t('app', 'Create Student Details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-details-create">
<div class="card">
       <div class="card-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
       </div>
</div>
</div>
