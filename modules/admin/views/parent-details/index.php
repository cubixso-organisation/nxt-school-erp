<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\ParentDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\base\Campus;
use app\modules\admin\models\base\StudentDetails;
use app\modules\admin\models\ParentDetails;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Parent Details');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


?>
<div class="parent-details-index">
    <div class="card">
        <div class="card-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <p>
                <?php if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) { ?>
                    <?= Html::a(Yii::t('app', 'Create Parent Details'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php  } ?>
                <!-- <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-primary search-button']) ?> -->
            </p>
            <div class="search-form" style="display:none">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
            $gridColumn = [
                ['class' => 'yii\grid\SerialColumn'],

                ['attribute' => 'id', 'visible' => false],

                // [
                //     'attribute' => 'user_id',
                //     'label' => Yii::t('app', 'User'),
                //     'value' => function ($model) {
                //         return $model->user ? $model->user->username : null;
                //     },
                //     'filterType' => GridView::FILTER_SELECT2,
                //     'filter' => \yii\helpers\ArrayHelper::map(
                //         \app\modules\admin\models\User::find()
                //             ->where(['user_role' => User::ROLE_PARENT])
                //             ->andWhere(['campus_id' => (new User())->getCampusId()])
                //             ->asArray()->all(), 
                //         'id', 
                //         'username'
                //     ),
                //     'filterWidgetOptions' => [
                //         'pluginOptions' => ['allowClear' => true],
                //     ],
                //     'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-parent-details-search-user_id']
                // ],
                [
                    'label' => Yii::t('app', 'Student Names'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        // Retrieve the related StudentDetails
                        $studentDetails = $model->studentDetails;
                
                        // Check if there are any student details and return their names
                        if (!empty($studentDetails)) {
                            return implode(', ', array_map(function($student) {
                                return $student->student_name; // Access the student_name property
                            }, $studentDetails));
                        }
                        return null; // Or return a default value
                    },
                   
                ],
                

                'name_of_the_father',

                'name_of_the_mother',

                'current_address:ntext',

                // 'permanent_address:ntext',

                'contact_number',
                [
                    'label' => 'WhatsApp',
                    'format' => 'raw',
                    'value' => function ($model) {
                        // Ensure the phone number contains only digits
                        $phone = preg_replace('/\D/', '', $model->contact_number);
                        
                        // Default message
                        // $name = $model->name_of_the_father;
                        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);

                        // Ensure the campusId is retrieved correctly
                        $campusId = $campusId ? $campusId : null;
                        
                        // Fetch the school name using the campusId
                        $schoolName = Campus::getCampusName();
                        
                        // Encode the message, ensuring it's correctly formatted
                        $message = urlencode("Dear Parent,\n\nGreetings from " . ($schoolName) . ".\n\nWe would like to discuss some important matters about your child. Please get in touch at your earliest convenience.");
                        
                
                        // WhatsApp URL
                        $url = "https://api.whatsapp.com/send?phone=$phone&text=$message";
                
                        // WhatsApp icon with link
                        return Html::a(
                            Html::img('https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg', ['alt' => 'WhatsApp', 'style' => 'width:20px; height:20px;']),
                            $url,
                            ['target' => '_blank', 'title' => 'Contact on WhatsApp']
                        );
                    }
                ],

                // 'father_education_qualification:ntext',

                // 'mother_education_qualification:ntext',

                // 'father_aadhaar_number',

                // 'mother_aadhaar_number',

                // 'father_occupation',

                // 'mother_occupation',


                [


                    'attribute' => 'status',
                    "format" => 'raw',
                    'label' => Yii::t('app', 'Status'),
                    'filter'  => (new ParentDetails())->getStateOptions(),
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'status', 'id' => 'grid-state-search-status'],


                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->getStateOptionsBadges();
                    },


                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                                return Html::a('<span class="fas fa-eye" aria-hidden="true"></span>', $url);
                            }
                        },
                        'update' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
                                return Html::a('<span class="fas fa-pencil-alt" aria-hidden="true"></span>', $url);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN || \Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
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
                'filterModel' => $searchModel,
                'columns' => $gridColumn,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-parent-details']],
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
                    ]),
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

            url: "/school_management_backend/gii/default/status-change",


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