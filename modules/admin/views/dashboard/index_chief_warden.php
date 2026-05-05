<?php
/* @var $this \yii\web\View */

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\PaymentDetails;
use app\modules\admin\models\WebSetting;
use app\modules\hostelmanagement\models\base\Hostels;
use app\modules\hostelmanagement\models\base\Rooms;
use yii\helpers\Url;

$this->title = 'Dashboard';
$this->params['subheading'] = '';



?>
<?php if (User::isChefWarden()) { ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h3>Hostel Management</h3>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-icon avatar-img rounded-circle">
                                <!-- <i class="fas">&#xf19d;</i> -->
                                <!-- <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon"> -->
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/hostel.png" style="width:50px;">
                            </div>
                            <div class="db-info">
                                <h6>No of Hostel in Campus</h6>
                                <h3><?= !empty($total_hostel) ? $total_hostel : 0 ?></h3>
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
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/stu.png" style="width:100px;">
                            </div>
                            <div class="db-info">
                                <h6>No Of Student in Hostel</h6>
                                <h3><?= !empty($total_hostelers) ? $total_hostelers : 0 ?></h3>
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
                                <img alt="Total Image" src="../themes/school-management/assets/img/dashimage/wa.png" style="width:100px;">
                            </div>
                            <div class="db-info">
                                <h6>No Of Warden in Hostel</h6>
                                <h3><?= !empty($total_warden) ? $total_warden : 0 ?></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <?php foreach ($total_hostel_individual as $hostel_name) : ?>
            <div class="col-xl-3 col-sm-6 col-12 mb-4">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-left">
                            <div class="db-icon avatar-img rounded-circle ml-3">
                                <img alt="Hostel Image" src="../themes/school-management/assets/img/dashimage/hostel.png" style="width:50px;">
                            </div>
                                <h4 class="card-title"><?= !empty($hostel_name->name) ? $hostel_name->name : 'Unknown Hostel' ?></h4>
                                <div class="extra-info">
                                    <p><strong>Total Wardens:</strong> <?= Hostels::getTotalWardens($hostel_name->id) ?></p>
                                    <p><strong>Total Students:</strong> <?= Hostels::getTotalStudents($hostel_name->id) ?></p>
                                    <p><strong>Total Floors:</strong> <?= Hostels::getTotalHostelFloors($hostel_name->id) ?></p>
                                    <p><strong>Total Rooms:</strong> <?= Hostels::getTotalHostelRooms($hostel_name->id) ?></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
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

<?php } ?>