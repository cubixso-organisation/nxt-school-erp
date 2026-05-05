<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\librarymanagement\models\IssueBooks */

$this->title = Yii::t('app', 'Issue Books');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Issue Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="issue-books-create">
    <div class="card">
        <div class="card-body">
            <!-- <h1><?= Html::encode($this->title) ?></h1> -->

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>