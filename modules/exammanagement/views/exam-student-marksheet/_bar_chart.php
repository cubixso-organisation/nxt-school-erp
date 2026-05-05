<?php

use yii\helpers\Url;



// Data for the chart
$data = array(12, 19, 3, 5, 2, 3, 7);

// Create the graph
$graph = new Graph(400, 300);
$graph->SetScale('textlin');

// Create the bar plot
$barplot = new BarPlot($data);
$graph->Add($barplot);

// Define the path to save the chart
$chartPath = Yii::getAlias('@webroot/uploads/chart.png');

// Ensure the directory exists
if (!file_exists(dirname($chartPath))) {
    mkdir(dirname($chartPath), 0777, true);
}

// Save the chart as an image
$graph->Stroke($chartPath);

// Return or render view
return $this->render('pdfView', [
    'chartPath' => Url::to('@web/uploads/chart.png', true),
]);
