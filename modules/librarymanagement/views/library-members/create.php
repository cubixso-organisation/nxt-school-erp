<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\LibraryMembers */

$this->title = Yii::t('app', 'Create Library Members');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Library Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="library-members-create">
    <div class="card">
       <div class="card-body">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
</div>
