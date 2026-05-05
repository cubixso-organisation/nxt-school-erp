<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Campus */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Campuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campus-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Campus') . ' ' . Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">

            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'institute.id',
                'label' => Yii::t('app', 'Institute'),
            ],
            'educational_institution_type_id',
            'name_of_the_educational_Institution',
            'user_id',
            [
                'attribute' => 'country.id',
                'label' => Yii::t('app', 'Country'),
            ],
            [
                'attribute' => 'state.id',
                'label' => Yii::t('app', 'State'),
            ],
            [
                'attribute' => 'district.name',
                'label' => Yii::t('app', 'District'),
            ],
            'pincode',
            'address:ntext',
            'campus_code',
            'registration_number',
            'registration_document',
            'name_of_the_authorized',
            'designation_of_the_authorized',
            'contact_number_of_the_authorized',
            'name_of_the_contact',
            'designation_of_the_contact',
            'contact_number_of_the_contact',
            'email_id_of_the_authorized:email',
            'aadhaar_of_the_authorized',
            'lat',

            'lng',
            'coordinates',
            'radius',
            'city',
            'status',
            'school_logo',
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>














</div>