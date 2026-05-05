<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\services\DirectionsWayPoint;
use dosamigos\google\maps\services\TravelMode;
use dosamigos\google\maps\overlays\PolylineOptions;
use dosamigos\google\maps\services\DirectionsRenderer;
use dosamigos\google\maps\services\DirectionsService;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\services\DirectionsRequest;
use dosamigos\google\maps\overlays\Polygon;
use dosamigos\google\maps\layers\BicyclingLayer;
 
 
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\BusDetails */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bus Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;





if (!empty($bus_route)) {
    function wayPoints($bus_route)
    {
        foreach ($bus_route as $bus_route_data) {
            $wayPoint[] = new LatLng(['lat' => $bus_route_data->lat, 'lng' => $bus_route_data->lng]);
        }
        foreach ($wayPoint as $wayPointData) {
            $waypoints[] =   new DirectionsWayPoint(['location' => $wayPointData]);
        }
        return $waypoints;
    }



    ?>
<div class="row">
<div class="bus-details-view">



    <?php


    $coord = new LatLng(['lat' => $model->start_point_lat, 'lng' =>$model->start_point_lng]);
    $map = new Map([
        'center' => $coord,
        'zoom' => 16,
    ]);


    // lets use the directions renderer
    $startPoint = new LatLng(['lat' => $model->start_point_lat, 'lng' =>$model->start_point_lng]);
    $endPoint = new LatLng(['lat' =>  $model->end_point_lat, 'lng' => $model->end_point_lng]);




    $kp = new LatLng(['lat' => 17.4932682, 'lng' => 78.3913929]);
    $jp = new LatLng(['lat' => 17.4986677, 'lng' => 78.3888094]);



    $waypoints1 = [
        new DirectionsWayPoint(['location' => $kp]),
        new DirectionsWayPoint(['location' => $jp])

    ];



    $directionsRequest = new DirectionsRequest([
        'origin' => $startPoint,
        'destination' => $endPoint,
        'waypoints' => wayPoints($bus_route),
        'travelMode' => TravelMode::DRIVING
    ]);

    // Lets configure the polyline that renders the direction
    $polylineOptions = new PolylineOptions([
        'strokeColor' => '#FFAA00',
        'draggable' => true
    ]);

    // Now the renderer
    $directionsRenderer = new DirectionsRenderer([
        'map' => $map->getName(),
        'polylineOptions' => $polylineOptions
    ]);

    // Finally the directions service
    $directionsService = new DirectionsService([
        'directionsRenderer' => $directionsRenderer,
        'directionsRequest' => $directionsRequest
    ]);

    // Thats it, append the resulting script to the map
    $map->appendScript($directionsService->getJs());

    // Lets add a marker now
    $marker = new Marker([
        'position' => $coord,
        'title' => 'My Home Town',
    ]);

    // Provide a shared InfoWindow to the marker
    $marker->attachInfoWindow(
        new InfoWindow([
            'content' => '<p>This is my super cool content</p>'
        ])
    );

    // Add marker to the map
    $map->addOverlay($marker);

    // Now lets write a polygon
    $coords = [
        new LatLng(['lat' => 25.774252, 'lng' => -80.190262]),
        new LatLng(['lat' => 18.466465, 'lng' => -66.118292]),
        new LatLng(['lat' => 32.321384, 'lng' => -64.75737]),
        new LatLng(['lat' => 25.774252, 'lng' => -80.190262])
    ];

    $polygon = new Polygon([
        'paths' => $coords
    ]);

    // Add a shared info window
    $polygon->attachInfoWindow(new InfoWindow([
            'content' => '<p>This is my super cool Polygon</p>'
        ]));

    // Add it now to the map
    $map->addOverlay($polygon);


    // Lets show the BicyclingLayer :)
    $bikeLayer = new BicyclingLayer(['map' => $map->getName()]);

    // Append its resulting script
    $map->appendScript($bikeLayer->getJs());

    // Display the map -finally :)
     $map->display();
}
?>



</div>

<div class="bus-details-view">
<div class="card">
<div class="card-body">
    <?=  $this->render('../bus-route/bus_root', ['dataProvider'=>$dataProviderBusRoute,'searchModel' => $searchModelBusRoute]); ?>
</div>
</div>
</div>
</div>