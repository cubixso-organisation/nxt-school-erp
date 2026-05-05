<?php

use yii\helpers\Html;
use kartik\grid\GridView;


$this->title = 'Generated Certificate Data';
$this->params['breadcrumbs'][] = ['label' => 'Generated Certificate Data', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'], // Add a serial column for custom ID
        // 'student_id',
        'student_name',
        'certificate_name',
        [
            'attribute' => 'certificate_file_path',
            'format' => 'raw', // Use raw format to allow HTML content
            'value' => function ($model) {
                // Assuming 'certificate_file_path' contains the actual path to the PDF file
                $pdfFilePath = $model->certificate_file_path;
    
                // Display a PDF icon with a link to the PDF file
                return Html::a('<span class="fas fa-file-pdf fa-2x" aria-hidden="true"></span>', $pdfFilePath, [
                    'title' => Yii::t('yii', 'Open PDF'),
                    'data-pjax' => '0',
                    'target' => '_blank', // Open the link in a new tab
                ]);
            },
        ],
        'created_on',
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', ['view-generated-certificate', 'id' => $model->id], [
                        'title' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ]);
                },
            ],
        ],
    ],
]);
?>
