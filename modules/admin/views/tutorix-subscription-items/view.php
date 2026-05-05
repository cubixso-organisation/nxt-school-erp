<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TutorixSubscriptionItems */

$this->title = Yii::t('app', 'Subscription Details') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tutorix Subscription Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tutorix-subscription-items-view">
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><?= Yii::t('app', 'Subscription Information') ?></h3>
            </div>
            <div class="card-body row">
                <div class="col-md-6">
                    <p><strong><?= Yii::t('app', 'Subscription ID:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->subscription_id) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Class:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->class->name) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Parent:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->parent->name_of_the_father) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Item Price:') ?></strong><span style="margin-left: 10px;"><?= $model->item_price ?></span></p>
                    <p><strong><?= Yii::t('app', 'Start Date:') ?></strong><span style="margin-left: 10px;"><?= Yii::$app->formatter->asDate($model->start_date) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Expiry Date:') ?></strong><span style="margin-left: 10px;"><?= Yii::$app->formatter->asDate($model->expiry_date) ?></span></p>
                </div>
                <div class="col-md-6">
                    <p><strong><?= Yii::t('app', 'Trial State:') ?></strong><span style="margin-left: 10px;"><?= $model->getTrailStateOptionsBadges() ?></span></p>
                    <p><strong><?= Yii::t('app', 'Payment Status:') ?></strong><span style="margin-left: 10px;"><?= $model->getPaymentStateOptionsBadges() ?></span></p>
                    <!-- <p><strong><?= Yii::t('app', 'User Access Token:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->tutorix_user_access_token) ?></span></p> -->
                    <p><strong><?= Yii::t('app', 'Status:') ?></strong><span style="margin-left: 10px;"><?= $model->getStateOptionsBadges() ?></span></p>
                    <!-- <p><strong><?= Yii::t('app', 'Unique ID:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->unique_id) ?></span></p> -->
                </div>
            </div>
        </div>

        <!-- Student Details -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h4 class="card-title"><?= Yii::t('app', 'Student Details') ?></h4>
            </div>
            <div class="card-body row">
                <div class="col-md-6">
                    <p><strong><?= Yii::t('app', 'Student Name:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->student->student_name) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Admission Number:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->student->admission_number) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Gender:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->student->gender) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Date of Birth:') ?></strong><span style="margin-left: 10px;"><?= Yii::$app->formatter->asDate($model->student->date_of_birth) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Category:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->student->category) ?></span></p>
                </div>
                <div class="col-md-6">
                    <p><strong><?= Yii::t('app', 'Phone Number:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->student->phone_number) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Section:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->student->section_id) ?></span></p>
                    <!-- <p><strong><?= Yii::t('app', 'Status:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->student->status) ?></span></p> -->
                </div>
            </div>
        </div>

        <!-- Parent Details -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h4 class="card-title"><?= Yii::t('app', 'Parent Details') ?></h4>
            </div>
            <div class="card-body row">
                <div class="col-md-6">
                    <p><strong><?= Yii::t('app', 'Father Name:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->parent->name_of_the_father) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Mother Name:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->parent->name_of_the_mother) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Contact Number:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->parent->contact_number) ?></span></p>
                </div>
                <div class="col-md-6">
                    <p><strong><?= Yii::t('app', 'Father Education:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->parent->father_education_qualification) ?></span></p>
                    <p><strong><?= Yii::t('app', 'Mother Education:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->parent->mother_education_qualification) ?></span></p>
                    <!-- <p><strong><?= Yii::t('app', 'Status:') ?></strong><span style="margin-left: 10px;"><?= Html::encode($model->parent->status) ?></span></p> -->
                </div>
            </div>
        </div>
    </div>
</div>
