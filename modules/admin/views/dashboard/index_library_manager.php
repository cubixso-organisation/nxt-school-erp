<?php
/* @var $this \yii\web\View */

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\PaymentDetails;
use app\modules\admin\models\WebSetting;
use yii\helpers\Url;

$this->title = 'Dashboard';
$this->params['subheading'] = '';



?>
<?php if (User::isLibraryManager()) { ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h3>Library Management</h3>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                                <!-- <i class="fas">&#xf19d;</i> -->
                                <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/314.jpg" style="width:90px;">
                            </div>
                            <div class="db-info">
                                <h6>Total Books</h6>
                                <h3><?= !empty($total_books) ? $total_books : 0 ?></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                                <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                                <!-- <i class="fa fa-address-book" data-bs-toggle="tooltip" title="fa fa-address-book"></i> -->
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/racks.png" style="width:100px;">

                            </div>
                            <div class="db-info">
                                <h6>No Of Racks</h6>
                                <h3><?= !empty($total_racks) ? $total_racks : 0 ?></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                                <!-- <i class="fas">&#xf0db;</i> -->
                                <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/Total-parent.png">

                            </div>
                            <div class="db-info">
                                <h6>Library Members</h6>
                                <h3><?= !empty($total_lib_members) ? $total_lib_members : 0 ?></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="container">
        <div class="row">







        </div>
    </div>

    <div class="row">
        <?php if (!empty($data['all_group_of_campus'])) {
            foreach ($data['all_group_of_campus'] as $all_campus_data) {
        ?>
                <div class="col-md-12">
                    <div class="card card-primary collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title"><?= $all_campus_data->name_of_the_educational_Institution ?></h3>
                            <div class="card-tools">
                                <?php
                                $login_type_campus = User::login_type_campus;
                                ?>
                                <a href="<?= Url::to(['/admin/users/auto-login', 'id' => $all_campus_data->id, 'type' => $login_type_campus]) ?>">
                                    <button type="button" class="btn btn-primary">Login</button>
                                </a>
                                <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <?= campus::getCampusDashBoardCards($all_campus_data->id) ?>
                            </div>
                        </div>
                    </div>
                </div>
        <?php }
        } ?>
    </div>
    </div>
<?php } ?>