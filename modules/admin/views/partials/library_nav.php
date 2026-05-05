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
        <i class="fas fa-tachometer-alt" style="color: green;"></i> <!-- Green FontAwesome icon for Dashboard -->
        <span>Dashboard</span>
    </a>
</li>

<?php if (Yii::$app->hasModule('library-management')) : ?>
    <li>
        <a href="<?= Url::toRoute(['/library-management/library-books']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/library-books']) ? 'active' : '' ?>">
            <i class="fas fa-book" style="color: green;"></i> <!-- Green FontAwesome icon for Available Books -->
            <span>Available Books</span>
        </a>
    </li>
    <li>
        <a href="<?= Url::toRoute(['/library-management/issue-books']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/issue-books']) ? 'active' : '' ?>">
            <i class="fas fa-book-open" style="color: green;"></i> <!-- Green FontAwesome icon for Issue Books -->
            <span>Issue Books</span>
        </a>
    </li>
    <li>
        <a href="<?= Url::toRoute(['/library-management/library-racks']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/library-racks']) ? 'active' : '' ?>">
            <i class="fas fa-book-open" style="color: green;"></i> <!-- Green FontAwesome icon for Issue Books -->

            <span>Books Racks</span>
        </a>
    </li>
    <li>
        <a href=" <?= Url::toRoute(['/library-management/library-members']) ?>" class="<?= Yii::$app->request->url == Url::to(['/library-management/library-members']) ? 'active' : '' ?>">
            <i class="fas fa-users" style="color: green;"></i> <!-- Green FontAwesome icon for Members -->
            <span>Members</span>
        </a>
    </li>
<?php endif; ?>