<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\CampusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\User;
use app\modules\admin\models\base\Institutes;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Campuses');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="campus-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <p>
        <?php if (User::isInstituteAdmin()) {
            $instituteAdmin = Institutes::find()->where(['user_id' => Yii::$app->user->identity->id])->one();

        ?>


            <?= Html::a(Yii::t('app', 'Create Campus'), ['create'], [
                'class' => 'btn btn-success',
                'data' => [
                    'method' => 'post',
                    'params' => [
                        'contact_number_of_the_authorized' => $instituteAdmin->contact_number_of_the_authorized, // Replace with your actual parameter name and value
                    ],
                ],
            ]) ?>
        <?php } ?>


        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button d-none']) ?>
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

                return Html::a(

                    'Login',

                    Url::to(['/admin/users/auto-login', 'id' => $model->id, 'type' => User::login_type_campus]),

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
            'attribute' => 'institute_id',
            'label' => Yii::t('app', 'Institute'),
            'value' => function ($model) {
                return $model->institute->id;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Institutes::find()->asArray()->all(), 'id', 'id'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Institutes', 'id' => 'grid-campus-search-institute_id']
        ],
        'educational_institution_type_id',
        'name_of_the_educational_Institution',
        'user_id',
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
            'filterInputOptions' => ['placeholder' => 'Country', 'id' => 'grid-campus-search-country_id']
        ],
        [
            'attribute' => 'state_id',
            'label' => Yii::t('app', 'State'),
            'value' => function ($model) {
                if (!empty($model->state->state_name)) {
                    return $model->state->state_name;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\State::find()->asArray()->all(), 'id', 'state_name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'State', 'id' => 'grid-campus-search-state_id']
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
            'filterInputOptions' => ['placeholder' => 'District', 'id' => 'grid-campus-search-district_id']
        ],
        'pincode',
        'campus_code',
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
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-campus']],
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