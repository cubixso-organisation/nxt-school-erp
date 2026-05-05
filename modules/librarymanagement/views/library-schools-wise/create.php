<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\LibrarySchoolsWise */

$this->title = Yii::t('app', 'Create Library Schools Wise');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Library Schools Wises'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="library-schools-wise-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
