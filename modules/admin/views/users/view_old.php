<?php

use app\models\User;
use app\modules\admin\models\base\Institutes;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
?>
<div class="user-view">
    <div class="card shadow-lg">
        <div class="card-header text-center bg-primary text-white">
            <h2 class="mb-0"><?= Yii::t('app', 'User Profile') ?></h2>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Profile Image -->
                <?php
                $common_logo = Url::base() . '/web/new_logo.png';
$getInstituteIdOfUser = (new Institutes())->getInstituteIdOfUser();
$name_of_the_educational_Institution = Institutes::find()->where(['id' => $getInstituteIdOfUser])->one();
$logo = !empty($name_of_the_educational_Institution->school_logo) ? $name_of_the_educational_Institution->school_logo : $common_logo;
?>
<div class="col-md-4 text-center">
    <img src="<?= $logo ?>" 
         class="img-thumbnail" 
         alt="Institution Logo" 
         style="width: 150px; height: 150px; object-fit: contain;">
</div>

                <!-- User Details -->
                <div class="col-md-8">
                    <h4 class="text-primary"><?= Html::encode($model->first_name . ' ' . $model->last_name) ?></h4>
                    <p class="text-muted"><strong><?= Html::encode($model->user_role) ?></strong></p>
                    <p><i class="fa fa-envelope"></i> <?= Html::encode($model->email) ?></p>
                    <p><i class="fa fa-phone"></i> <?= Html::encode($model->contact_no) ?></p>
                </div>
            </div>
            <hr>
            <!-- Additional Information -->
            <!-- <div class="row">
                <div class="col-md-6">
                    <p><strong>Campus:</strong> <?= Html::encode($model->campus_id ?: 'N/A') ?></p>
                    <p><strong>Blood Group:</strong> <?= Html::encode($model->blood_group ?: 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Latitude:</strong> <?= Html::encode($model->lat ?: 'N/A') ?></p>
                    <p><strong>Longitude:</strong> <?= Html::encode($model->lng ?: 'N/A') ?></p>
                </div>
            </div> -->
        </div>
        <?php if (User::isAdmin()) : ?>
            <!-- Action Buttons for Admin -->
            <div class="card-footer text-right">
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
