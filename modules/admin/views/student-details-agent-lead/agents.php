<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\EmployeeDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\StudentDetailsAgentLead;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Agents Details');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);

 
?>
<div class="employee-details-index">
<div class="card">
       <div class="card-body">
    

    <p>
    <?php  if (\Yii::$app->user->identity->user_role==User::ROLE_ADMIN || \Yii::$app->user->identity->user_role==User::ROLE_CAMPUS_ADMIN) { ?>
        <?= Html::a(Yii::t('app', 'Create Agents'), ['agents-create'], ['class' => 'btn btn-success']) ?>
        <?php  } ?>
    </p>
  
        </div>
    </div>
    <div class="card">
       <div class="card-body">
<?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        ['attribute' => 'id', 'visible' => false],

        // [
        //         'attribute' => 'user_id',
        //         'label' => Yii::t('app', 'User'),
        //         'value' => function($model){
        //             return $model->user->username;
        //         },
        //         'filterType' => GridView::FILTER_SELECT2,
        //         'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()->asArray()->all(), 'id', 'username'),
        //         'filterWidgetOptions' => [
        //             'pluginOptions' => ['allowClear' => true],
        //         ],
        //         'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-employee-details-search-user_id']
        //     ],




        'employee_id',



        [
            'attribute' => 'id_proof',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::img(
                    $model['id_proof'],
                    [
                        'width' => '100px',
                        'height' => '100px',
                    ]
                );
            },

        ],




            [
                'attribute' => 'admissions',
                'label' => Yii::t('app', 'Admissions'),
                'value' => function ($model) {
                    $student_details_agent_lead = StudentDetailsAgentLead::find()->where(['agent_id'=>$model->user_id])
                    ->orderBy(['count' => SORT_DESC])
                    ->count();
                    return   $student_details_agent_lead;
                }
            ],


        'employ_name',



        'age',

        'gender',



        'phone_number',

        'email:email',

        [
            'class' => 'kartik\grid\ActionColumn',
             'template' => '{view} {update} ',
             'buttons' => [
            'view'=> function ($url, $model) {
                $url ='agent-view?id='.$model->id;

                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                }
            },
            'update'=> function ($url, $model) {
                $url ='agent-update?id='.$model->id;
                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                }
            },
            'delete'=> function ($url, $model) {
                $url ='agent-delete?id='.$model->id;

                if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN|| \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                    return Html::a('<span class="fas fa-trash-alt" aria-hidden="true"></span>', $url, [
                        'data' => [
                                    'method' => 'post',
                                     // use it if you want to confirm the action
                                     'confirm' => 'Are you sure?',
                                 ],
                                ]);
                }
            },


        ]
        ],
    ];
?>
    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions'=>function($model){
        if($model->agent_type==EmployeeDetails::agent_type_manual_payment){
            return ['class' => 'table-warning'];
        }
    },

    'filterModel' => $searchModel,
    'columns' => $gridColumn,

 
    'pjax' => true,
    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-employee-details']],
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
	 
      url: "/school_management_backend/gii/default/status-change",
     
 
      data: {id:id,val:val},
	  success: function(data){
		  swal("Good job!", "Status Successfully Changed!", "success");
	  }
	});
});


</script>