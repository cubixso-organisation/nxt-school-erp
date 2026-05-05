<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\modules\admin\widgets\BoxGridView;
use app\modules\admin\widgets\LinkedColumn;
use app\modules\admin\Module as AdminModule;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Key Person';
$this->params['breadcrumbs'][] = $this->title;

$js = <<< JS
    function sendRequest(status, id){
       if(status == true){
           val = 1;
       }else{
           val = 0; 
       }
        $.ajax({
            url:'users/update-status',
            method:'post',
            data:{val:val,id:id},
            success:function(data){
              alert('status updated');
            },
            error:function(jqXhr,status,error){
                alert(error);
            }
        });
    }
JS;

$this->registerJs($js, \yii\web\View::POS_READY);

?>





<?php 



if (User::isCampusAdmin()) {

    ?>
	<?php echo  Html::a('Add New Key Person', ['key-person-create'], ['class' => 'btn btn-sm btn-success']) ?>

<?php
}

?>

<div class="user-index">
<div class="card">
    <div class="card-body">
	
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			'id',

			[
				'class' => LinkedColumn::class,
				'header' => '<a href="#">Name</a>',
				'attribute' => 'first_name',
				'value' => 'first_name',
			],

			[
				'class' => LinkedColumn::class,
				'header' => '<a href="#">Email</a>',
				'attribute' => 'username',
				'value' => 'email',
			],
			'contact_no',
			'user_role',
		

				 [ 
					'attribute' => 'status',
					'filter'  =>  \app\models\User::getStatusesList(),
					"format" => 'raw',
					'value' => function ($data) {
						$html = '';
					 
						$html .= '<select id="status_list_'. $data->id. '" data-id="'.$data->id.'" >';
						$lists = $data->getStatusesList();
					
						foreach ( $lists as $key => $list ) {
							
							if($key==$data->status){
								$html .= '<option value="' . $key . '" selected>' . $list . '</option>';
								
							}else{
								$html .= '<option value="' . $key . '">' . $list . '</option>';
								
							}
						}                
						$html .= '</select>';      
						
						return $html;
				}
				] ,
				///end now status



			'created_at:date:Registered',
            [
				'class' => LinkedColumn::class,
				'header' => '<a href="#">registered</a>',
				'attribute' => 'registered',
				'value' => 'registered',
			],
            [
                'class' => 'kartik\grid\ActionColumn',
                 'template' => '{view} {update} {delete}',
                 'buttons' => [
                'view'=> function($url,$model) {
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                        return Html::a( '<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                    } 
                    },
                'update'=> function($url,$model) {
                    $url = 'key-person-update?id='.$model->id;
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
                        return Html::a( '<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
    
                    } 
                    },
                // 'delete'=> function($url,$model) {
                // if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
                //         return Html::a( '<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url,[
                //             'data' => [
                //                         'method' => 'post',
                //                          // use it if you want to confirm the action
                //                          'confirm' => 'Are you sure?',
                //                      ],
                //                     ]);
                //     } 
                //     },
    
    
            ]
            ],

            
		],
	]); ?>

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
	 
      url: "<?= Url::toRoute(['users/status-change'])?>",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});
</script>