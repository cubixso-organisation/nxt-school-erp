<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\AuthSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;

use kartik\grid\GridView;

// $this->title = Yii::t('app', 'Auth Sessions');
// $this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
 

?>
<div class="auth-session-index">
<div class="card">
       <div class="card-body">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
   
    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>
        </div>
    </div>
    <div class="card">
       <div class="card-body">
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
   
        ['attribute' => 'id', 'visible' => false],
   
        // 'auth_code',
   
        // 'device_token',
        // [
        //     'attribute' => 'create_user_id',
        //     'label' => 'parent',
        //     'value' => function ($model) {
        //         return $model->createUser ? $model->createUser->first_name : 'N/A';
        //     },
        // ],
        // [
        //     'attribute' => 'create_user_id',
        //     'label' => 'Contact No',
        //     'value' => function ($model) {
        //         return $model->createUser ? $model->createUser->contact_no : 'N/A';
        //     },
        // ],
        [
            'attribute' => 'parent_name',
            'label' => 'Parent Name',
            'value' => 'parent_name',
        ],
        [
            'attribute' => 'student_names',
            'label' => 'Student Names',
            'value' => 'student_names',
            'format' => 'raw', // Optional if you need HTML formatting
        ],
        [
            'attribute' => 'student_classes',
            'label' => 'Student Classes',
            'value' => 'student_classes',
            'format' => 'raw', // Optional for HTML formatting
        ],
        [
            'attribute' => 'contact_no',
            'label' => 'Contact No',
            'value' => 'contact_no',
        ],
        // [
        //     'attribute' => 'campus_id',
        //     'label' => 'Campus ID',
        //     'value' => 'campus_id',
        // ],
        [
            'attribute' => 'campus_name',
            'label' => 'Campus',
            'value' => 'campus_name',
        ],
        'created_on',
   
        // 'type_id',
       
    ];   
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-auth-session']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        'export' => false,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_PDF => false
                ]
            ]) ,
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
	 
      url: "/estudent_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>