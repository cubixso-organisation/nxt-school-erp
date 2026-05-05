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

$this->title = 'Teacher List';
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
<?php $this->beginBlock('content-title');


if (User::isInstituteAdmin() || User::isCampusAdmin()) {
?>

	<?php echo  Html::a('Add New', ['create'], ['class' => 'btn btn-sm btn-success']) ?>

<?php
}

$this->endBlock(); ?>
<!-- <?php



		if (User::isInstituteAdmin() || User::isCampusAdmin()) {
		?>
	<?php echo  Html::a('Add New', ['create'], ['class' => 'btn btn-sm btn-success']) ?>

<?php
		}

?> -->

<div class="user-index">
	<div class="card">
		<div class="card-body">

			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel'  => $searchModel,
				'columns'      => [
					['class' => 'yii\grid\SerialColumn'],
					// 'id',

					[
						// 'class' => LinkedColumn::class,
						'header' => 'Name',
						'attribute' => 'first_name',
						'value' => 'first_name',
					],
					//'username',
					[
						// 'class' => LinkedColumn::class,
						'header' => 'Email',
						'attribute' => 'username',
						'value' => 'email',
					],
					'contact_no',

					[
						// 'class' => LinkedColumn::class,
						'header' => 'User Role',
						'attribute' => 'user_role',
						'value' => 'user_role',
					],

					[
						'class' => 'kartik\grid\ActionColumn',
						'template' => ' {update}',
						'buttons' => [
							'update' => function ($url, $model, $key) {
								// Customizing the update URL
								$url = Url::to(['users/teacher-update', 'id' => $model->id]);
								return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
							},
						],
					],
				],
			]); ?>

		</div>
	</div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
	$(document).on('change', 'select[id^=status_list_]', function() {
		var id = $(this).attr('data-id');
		var val = $(this).val();
		$.ajax({
			type: "POST",

			url: "<?= Url::toRoute(['users/status-change']) ?>",


			data: {
				id: id,
				val: val
			},
			success: function(data) {
				swal("Good job!", "Status Successfully Changed!", "success");
			}
		});
	});
</script>