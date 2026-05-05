<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TestUsers */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Test Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-users-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Test Users').' '. Html::encode($this->title) ?></h2>
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
        'username',
        'email:email',
        'first_name',
        'last_name',
        'contact_no',
        'alternative_contact',
        'date_of_birth',
        'gender',
        'description:ntext',
        'address:ntext',
        'profile_image',
        'status',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    <div class="row">
        <h4>User<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUser = [
        ['attribute' => 'id', 'visible' => false],
        'username',
        'auth_key',
        'password_hash',
        'password_reset_token',
        'email:email',
        'first_name',
        'last_name',
        'contact_no',
        'alternative_contact',
        'date_of_birth',
        'gender',
        'description:ntext',
        'address:ntext',
        'profile_image',
        'user_role',
        'oauth_client_user_id',
        'oauth_client',
        'access_token',
        'device_token',
        'device_type',
        'status',
        'is_active_wa_updates',
        'city_id',
        'online_status',
        'campus_id',
        'lat',
        'lng',
        'referal_code',
        'designation_name',
        'referal_id',
        'blood_group',
        'is_deleted',
        'info_delete',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model->updateUser,
        'attributes' => $gridColumnUser    ]);
    ?>
    <div class="row">
        <h4>User<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUser = [
        ['attribute' => 'id', 'visible' => false],
        'username',
        'auth_key',
        'password_hash',
        'password_reset_token',
        'email:email',
        'first_name',
        'last_name',
        'contact_no',
        'alternative_contact',
        'date_of_birth',
        'gender',
        'description:ntext',
        'address:ntext',
        'profile_image',
        'user_role',
        'oauth_client_user_id',
        'oauth_client',
        'access_token',
        'device_token',
        'device_type',
        'status',
        'is_active_wa_updates',
        'city_id',
        'online_status',
        'campus_id',
        'lat',
        'lng',
        'referal_code',
        'designation_name',
        'referal_id',
        'blood_group',
        'is_deleted',
        'info_delete',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model->createUser,
        'attributes' => $gridColumnUser    ]);
    ?>
</div>
