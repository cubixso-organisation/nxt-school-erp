<?php

use app\modules\leavemanagement\models\base\StaffLeaveApplied;
use app\modules\leavemanagement\models\base\StaffLeaveTypes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\leavemanagement\models\StaffLeaveApplied */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Staff Leave Applieds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$approveUrl = \yii\helpers\Url::to(['staff-leave-applied/approve', 'id' => $model->id]);
$rejectUrl = \yii\helpers\Url::to(['staff-leave-applied/reject', 'id' => $model->id]);

$script = <<< JS
    $(document).ready(function() {
        $('#approveBtn').on('click', function() {
            $.ajax({
                type: 'POST',
                url: '{$approveUrl}',
                success: function(response) {
                    console.log('Leave Approved');
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        });

        $('#rejectBtn').on('click', function() {
            $.ajax({
                type: 'POST',
                url: '{$rejectUrl}',
                success: function(response) {
                    console.log('Leave Rejected');
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        });
    });
JS;

$this->registerJs($script);

?>

<div class="staff-leave-applied-view">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-9">
                    <h2 class="mb-4"><?= 'Staff Leave Applied' . ' ' . Html::encode($this->title) ?></h2>
                </div>
                <div class="col-sm-3 text-right">
                    <?php if ($model->status != StaffLeaveApplied::STATUS_APPROVED) { ?>
                        <a href="<?= Url::toRoute(['/admin/leave-management/staff-leave-applied/leave-approve', 'id' => $model->id]) ?>" class="btn btn-primary">Approve</a>
                    <?php } ?>
                    <?php if ($model->status != StaffLeaveApplied::STATUS_REJECTED) { ?>
                        <?= Html::a('Reject', ['reject', 'id' => $model->id], ['class' => 'btn btn-danger', 'id' => 'rejectBtn']) ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <!-- The rest of your view remains unchanged -->
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-4 text-muted">Applicant Name:</dt>
                    <dd class="col-sm-8"><?= Html::encode($model->user->first_name . "" . $model->user->last_name) ?></dd>

                    <dt class="col-sm-4 text-muted">Leave Type:</dt>
                    <dd class="col-sm-8"><?= Html::encode($model->leaveType->title) ?></dd>

              

                    <dt class="col-sm-4 text-muted">Leave Reason:</dt>
                    <dd class="col-sm-8"><?= Html::encode($model->leave_reason) ?></dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row">
                    <dt class="col-sm-4 text-muted">From Date:</dt>
                    <dd class="col-sm-8"><?= Html::encode($model->from_date) ?></dd>

                    <dt class="col-sm-4 text-muted">To Date:</dt>
                    <dd class="col-sm-8"><?= Html::encode($model->to_date) ?></dd>

                    <dt class="col-sm-4 text-muted">Document Uploaded:</dt>
                    <dd class="col-sm-8"> <a href="<?= Html::encode($model->document_uploaded??"") ?>" class="btn btn-success" download> View</a> </dd>

                    <dt class="col-sm-4 text-muted">Status:</dt>
                    <dd class="col-sm-8"><?= $model->getStateOptionsBadges() ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
</div>