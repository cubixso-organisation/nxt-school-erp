<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SubjectGroupsClassSections */

$this->title = Yii::t('app', 'Create Subject Groups Class Sections');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subject Groups Class Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-groups-class-sections-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
