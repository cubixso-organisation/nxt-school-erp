<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\BusRouteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

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


       
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <?php  if(\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::role_campus_sub_admin || \Yii::$app->user->identity->user_role==User::ROLE_CAMPUS_ADMIN){ ?>
        <?= Html::a(Yii::t('app', 'Create Bus Route'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php  } ?>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-primary search-button d-none']) ?>
    </p>
    <div class="search-form" style="display:none">
   
    </div>
        </div>
    </div>
    <div class="card">
       <div class="card-body">





<?=  $this->render('bus_root', ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]); ?>



    



</div>
</div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).on('change','select[id^=status_list_]',function(){
var id=$(this).attr('data-id');
var val=$(this).val();

$.ajax({
	  type: "POST",
	 
      url: "/school_management_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>