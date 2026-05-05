<?php
   use yii\helpers\Html;
   use yii\bootstrap4\Nav;
   use app\modules\admin\widgets\Menu;
   use app\modules\admin\models\Campus;
   use app\modules\admin\models\Institutes;
   use app\modules\admin\models\PaymentDetails;
   use app\modules\admin\models\User;
   use yii\helpers\Url;
   use app\modules\admin\models\WebSetting;
   
   $setting = new WebSetting();
   $demo_location = $setting->getSettingBykey('demo_location');
   
   $module =  (new Campus())->checkModuleActivationSTatus(Institutes::activation_modules_agent);
   
   $activation_modules_bus_tracking_module =  (new Campus())->checkModuleActivationSTatus(Institutes::activation_modules_bus_tracking);
   
   $activation_modules_fee_module_module =  (new Campus())->checkModuleActivationSTatus(Institutes::activation_modules_fee_module);
   
   $checkIndividualCampus = (new Campus())->checkIndividualCampus();
   ?>
<li class="<?= Yii::$app->request->url == Url::to(['/admin/dashboard', $schema = true]) ? 'active' : '' ?>">
   <a href="<?= Url::toRoute(['/admin/dashboard', $schema = true]) ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/dashboard-1.png' ?>">
   <span>Dashboard</span>
   </a>
</li>
<?php
   if (Yii::$app->hasModule('hostel-management')) {
   
   ?>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/warden-to-hostel']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/warden-to-hostel']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/w.png' ?>">
   <span>Assign Warden to Hostel</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/hostels']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/campus.png' ?>">
   <span>View Hostels</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/floor']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/floor']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/f.png' ?>">
   <span>Floor</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/rooms/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/rooms/create']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/r.png' ?>">
   <span>Create Rooms</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/rooms']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/rooms']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/r.png' ?>">
   <span>View Rooms</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/hostellers/create']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/rooms']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/st.png' ?>">
   <span>Create Hostelers</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/hostellers']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostels/create-warden']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/st.png' ?>">
   <span>Hostelers</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/warden-attandance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/warden-attandance']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/w.png' ?>">
   <span> Warden Attendance</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/warden-attandance/index-day-wise-attendance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/warden-attandance/index-day-wise-attendance']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/w.png' ?>">
   <span> Today's Warden Attendance</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/hostellers-attandance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostellers-attandance']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/st.png' ?>">
   <span>Hostelers Attendance</span>
   </a>
</li>
<li>
   <a href="<?= Url::toRoute(['/hostel-management/hostellers-attandance/index-day-wise-attendance']) ?>" class="<?= Yii::$app->request->url == Url::to(['/hostel-management/hostellers-attandance/index-day-wise-attendance']) ? 'active' : '' ?>">
   <img alt="img" src="<?= Url::base() . '/themes/school-management/assets/img/dashicons/st.png' ?>">
   <span>Today's Hostelers Attendance</span>
   </a>
</li>

<?php } ?>