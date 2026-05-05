<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\StudentDetailsAgentLead */

$this->title = Yii::t('app', 'Create Student Details Agent Lead');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Details Agent Leads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-details-agent-lead-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
