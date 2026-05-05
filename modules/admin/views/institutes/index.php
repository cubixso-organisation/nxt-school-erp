<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\InstitutesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Institutes');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);

// shadowLogin

?>

<div class="institutes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Institutes'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-primary search-button d-none']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        // [

        //     'format' => 'raw',
        //     'label' => 'Login',

        //     'value' => function ($model, $key, $index, $column) {

        //         return Html::a(

        //             'Login',

        //             Url::to(['shadow-login', 'id' => $model->user_id, 'shadow-login' => 1]),

        //             [

        //                 'id' => 'grid-custom-button',

        //                 'data-pjax' => true,
        //                 'class' => 'newwindow',
        //                 'action' => Url::to(['shadow-login', 'id' => $model->user_id]),

        //                 'class' => 'button btn btn-primary',
        //                 'target' => '_blank'

        //             ]


        //         );
        //     }

        // ],
 

        [

            'format' => 'raw',
            'label' => 'Login',

            'value' => function ($model, $key, $index, $column) {

                if($model->subscription_type==Institutes::subscription_type_group_of_institutions){
                    $id = $model->id;
                    $type = User::login_type_institutes;
                }else if($model->subscription_type==Institutes::subscription_type_individual_institution){
                    $campus = Campus::find()->where(['institute_id'=>$model->id])->one();
                    $id = isset($campus->id) ? $campus->id:'';
                    $type = User::login_type_campus;
                }

                return Html::a(

                    'Login',

                    Url::to(['/admin/users/auto-login', 'id' => $id, 'type' =>$type]),

                    [

                        'id' => 'grid-custom-button',

                        'data-pjax' => true,
                        'class' => 'newwindow',
                        'action' => Url::to(['shadow-login', 'id' => $model->user_id]),

                        'class' => 'button btn btn-primary',
                        'target' => '_blank'

                    ]


                );
            }

        ],



        [
            'attribute' => 'subscription_type',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->getSubscriptionTypeBadges();
            },


        ],



        'name_of_the_educational_Institution',
        [
            'attribute' => 'user_id',
            'label' => Yii::t('app', 'User'),
            'value' => function ($model) {
                return isset($model->user->username) ? $model->user->username:'';
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\User::find()->asArray()->all(), 'id', 'username'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-institutes-search-user_id']
        ],
        'onboarding_date',
        'expiry_date',
        [
            'attribute' => 'country_id',
            'label' => Yii::t('app', 'Country'),
            'value' => function ($model) {
                return $model->country->country_name;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Country::find()->asArray()->all(), 'id', 'country_name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Country', 'id' => 'grid-institutes-search-country_id']
        ],
        [
            'attribute' => 'state_id',
            'label' => Yii::t('app', 'State'),
            'value' => function ($model) {
                return $model->state->state_name??"";
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\State::find()->asArray()->all(), 'id', 'state_name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'State', 'id' => 'grid-institutes-search-state_id']
        ],
        [
            'attribute' => 'district_id',
            'label' => Yii::t('app', 'District'),
            'value' => function ($model) {
                return $model->district->name;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\District::find()->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'District', 'id' => 'grid-institutes-search-district_id']
        ],
        'pincode',
        'institute_code',
        'registration_number',

        [
            'attribute' => 'registration_document',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::img(
                    $model['registration_document'],
                    [
                        'width' => '100px',
                        'height' => '100px',
                    ]
                );
            },
    
        ],


        'name_of_the_authorized',
        'designation_of_the_authorized',
        'contact_number_of_the_authorized',
        'name_of_the_contact',
        'designation_of_the_contact',
        'contact_number_of_the_contact',
        'email_id_of_the_authorized:email',
        'aadhaar_of_the_authorized',
    
        // 'status',

        [
            'attribute' => 'school_logo',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::img(
                    $model['school_logo'],
                    [
                        'width' => '100px',
                        'height' => '100px',
                    ]
                );
            },
    
        ],


        [
            'class' => 'yii\grid\ActionColumn',
        ],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-institutes']],
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