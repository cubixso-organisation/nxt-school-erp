<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Campus */

$this->title = $model->name_of_the_educational_Institution;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Campuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="campus-view">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    <!-- School Logo -->
                    <?= Html::img($model->school_logo, [
                        'class' => 'img-thumbnail',
                        'alt' => 'School Logo',
                        'style' => 'width: 150px; height: 150px; object-fit: contain;'
                    ]) ?>
                </div>
                <div class="col-md-9">
                    <!-- Key Information -->
                    <h5 class="text-primary">Institution Details</h5>
                    <p><strong>Institution Name:</strong> <?= Html::encode($model->name_of_the_educational_Institution) ?></p>
                    <p><strong>Type:</strong> <?= Html::encode($model->educationalInstitutionType->title ?? 'N/A') ?></p>
                    <p><strong>Campus Code:</strong> <?= Html::encode($model->campus_code) ?></p>
                    <p><strong>Registration Number:</strong> <?= Html::encode($model->registration_number) ?></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <!-- Address Information -->
                <div class="col-md-6">
                    <h5 class="text-primary">Address Details</h5>
                    <p><strong>Address:</strong> <?= Html::encode($model->address) ?></p>
                    <p><strong>City:</strong> <?= Html::encode($model->city) ?></p>
                    <p><strong>District:</strong> <?= Html::encode($model->district->name ?? 'N/A') ?></p>
                    <p><strong>State:</strong> <?= Html::encode($model->state->state_name ?? 'N/A') ?></p>
                    <p><strong>Country:</strong> <?= Html::encode($model->country->country_name ?? 'N/A') ?></p>
                    <p><strong>Pincode:</strong> <?= Html::encode($model->pincode) ?></p>
                </div>
                <div class="col-md-6 text-center">
                    <h5 class="text-primary">Registration Document</h5>
                    <?= Html::img($model->registration_document, [
                        'class' => 'img-thumbnail',
                        'alt' => 'Registration Document',
                        'style' => 'width: 300px; height: auto; object-fit: contain;'
                    ]) ?>
                </div>
            </div>
            <hr>
            <!-- Authorized Person Details -->
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary">Authorized Person Details</h5>
                    <p><strong>Name:</strong> <?= Html::encode($model->name_of_the_authorized) ?></p>
                    <p><strong>Designation:</strong> <?= Html::encode($model->designation_of_the_authorized) ?></p>
                    <p><strong>Contact Number:</strong> <?= Html::encode($model->contact_number_of_the_authorized) ?></p>
                    <p><strong>Email:</strong> <?= Html::encode($model->email_id_of_the_authorized) ?></p>
                    <p><strong>Aadhaar:</strong> <?= Html::encode($model->aadhaar_of_the_authorized) ?></p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary">Contact Person Details</h5>
                    <p><strong>Name:</strong> <?= Html::encode($model->name_of_the_contact) ?></p>
                    <p><strong>Designation:</strong> <?= Html::encode($model->designation_of_the_contact) ?></p>
                    <p><strong>Contact Number:</strong> <?= Html::encode($model->contact_number_of_the_contact) ?></p>
                </div>
            </div>
            <hr>
            <!-- Registration Document -->
            <div class="row">
            <div class="col-md-12">
                <h5 class="text-primary">Coordinates</h5>
                <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3806.5387246690675!2d78.37365251083173!3d17.433910801394045!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTfCsDI2JzAyLjEiTiA3OMKwMjInMzQuNCJF!5e0!3m2!1sen!2sin!4v1733834229849!5m2!1sen!2sin" width="1200" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <!-- <h5 class="text-primary">Coordinates</h5>
                    <p><strong>Latitude:</strong> <?= Html::encode($model->lat ?? 'N/A') ?></p>
                    <p><strong>Longitude:</strong> <?= Html::encode($model->lng ?? 'N/A') ?></p>
                    <p><strong>Coordinates:</strong> <?= Html::encode($model->coordinates ?? 'N/A') ?></p> -->
                </div>
            </div>
            
        </div>
        <div class="card-footer text-right">
            <!-- Update Button -->
            <?= Html::a(Yii::t('app', 'Update'), ['my-campus-update'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
