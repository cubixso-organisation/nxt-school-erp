<?php
use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = 'Generated Certificate Data';
$this->params['breadcrumbs'][] = ['label' => 'Generated Certificate Data', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'student_id',
        'student_name',
        'certificate_name',
        'certificate_file_path',
        'created_on',
    ],
]) ?>
