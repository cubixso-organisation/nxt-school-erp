<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\BusRouteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Bus Routes');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="bus-route-index">
<div class="card">
       <div class="card-body">

    
    <?php echo $this->render('_search_create', ['model' => $searchModel]); ?>

  
    
        </div>
    </div>
    <div class="card">
       <div class="card-body">


 


<?=  $this->render('bus_root_without_search', ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]); ?>

</div>
</div>
</div>


<script>
   function redirectPage(url){
    let baseUrl = "<?= Url::base() ?>"
    window.location.href = url
   }
   
</script>

