<?php

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use app\modules\admin\widgets\Menu;
use yii\helpers\Url;

?>

<li class="<?= Yii::$app->controller->id === 'dashboard' ? 'active' : '' ?>">
    <a href="<?= Url::toRoute(['/admin/dashboard', $schema = true]) ?>" class="nav-link <?= Yii::$app->controller->id === 'dashboard' ? 'active' : '' ?>">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm320 96c0-26.9-16.5-49.9-40-59.3L280 88c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 204.7c-23.5 9.5-40 32.5-40 59.3c0 35.3 28.7 64 64 64s64-28.7 64-64zM144 176a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm-16 80a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm288 32a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM400 144a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>

        <span>Dashboard</span>
    </a>
</li>

<li class="submenu ">
    <a href="#">
        <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/web-setting-1.png"> -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M487.4 315.7l-42.8-24.7c1.4-8.4 2.1-17.1 2.1-25.9s-.7-17.5-2.1-25.9l42.8-24.7c12.8-7.4 17.5-23.9 10-36.8l-39.6-68.6c-7.4-12.8-23.9-17.5-36.8-10l-42.8 24.7c-13.3-10.6-28.2-19.4-44.3-25.9V48c0-14.9-12.1-27-27-27H183.7c-14.9 0-27 12.1-27 27v49.7c-16.2 6.5-31 15.3-44.3 25.9L69.6 99c-12.8-7.4-29.3-2.8-36.8 10L-6.8 177.6c-7.4 12.8-2.8 29.3 10 36.8l42.8 24.7c-1.4 8.4-2.1 17.1-2.1 25.9s.7 17.5 2.1 25.9l-42.8 24.7c-12.8 7.4-17.5 23.9-10 36.8l39.6 68.6c7.4 12.8 23.9 17.5 36.8 10l42.8-24.7c13.3 10.6 28.2 19.4 44.3 25.9V464c0 14.9 12.1 27 27 27H328.3c14.9 0 27-12.1 27-27v-49.7c16.2-6.5 31-15.3 44.3-25.9l42.8 24.7c12.8 7.4 29.3 2.8 36.8-10l39.6-68.6c7.4-12.8 2.8-29.3-10-36.8zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"/></svg>



        <span> Web Settings</span><span class="menu-arrow">
    </a>
    <ul>
        <li>
            <a href="<?= Url::toRoute(['/admin/web-setting/cms']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/web-setting/cms']) ? 'active' : '' ?>">Web Setting</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/app-banner']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/app-banner']) ? 'active' : '' ?>">App Main Banner</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/banners']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/banners']) ? 'active' : '' ?>">Banners</a>
        </li>
    </ul>
</li>


<li class="submenu">
    <a href="#">
        <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/user.png"> -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z"/></svg>


        <span> Users</span><span class="menu-arrow">
    </a>
    <ul>
        <li>
            <a href="<?= Url::toRoute(['/admin/users']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/users']) ? 'active' : '' ?>">Users</a>
        </li>
    </ul>
</li>
<li class="submenu">
    <a href="#">
        <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/manage-institutes-1.png"> -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96 48 96C21.5 96 0 117.5 0 144L0 464c0 26.5 21.5 48 48 48l208 0 0-96c0-35.3 28.7-64 64-64s64 28.7 64 64l0 96 208 0c26.5 0 48-21.5 48-48l0-320c0-26.5-21.5-48-48-48L473.7 96 337.8 5.4zM96 192l32 0c8.8 0 16 7.2 16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-64c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-64zM96 320l32 0c8.8 0 16 7.2 16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-64c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16l32 0c8.8 0 16 7.2 16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-64zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-16 0 0-16c0-8.8-7.2-16-16-16z"/></svg>


        <span> Manage Institutes</span>
        <span class="menu-arrow">
    </a>
    <ul>
        <li>
            <a href="<?= Url::toRoute(['/admin/institutes']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/institutes']) ? 'active' : '' ?>">Institution </span></a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/educational-institution-types']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/educational-institution-types']) ? 'active' : '' ?>">Campus types</a>
        </li>
    </ul>
</li>
<li class="submenu">
    <a href="#">
        <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png"> -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm163.3 114.7c20.4 26.1 34.2 57.5 39.3 91.6h-83.1c-3.1-24.4-9.5-47.7-18.7-68.7 23.5-6.6 45.5-16.3 62.5-29.9zM256 32c44.8 0 86.2 14.6 119.7 39.1-19.8 17.7-43.8 31-70.2 38.2C290.4 85.2 274.6 80 256 80s-34.4 5.2-49.5 14.7c-26.4-7.2-50.4-20.4-70.2-38.2C169.8 46.6 211.2 32 256 32zM94.7 122.7c17 13.6 39 23.3 62.5 29.9-9.2 21-15.6 44.3-18.7 68.7H55.3c5.1-34.1 18.9-65.5 39.4-91.6zM32 256c0-7.3.4-14.5 1.1-21.6H128c-2.7 17.4-4.1 35.4-4.1 53.6s1.4 36.2 4.1 53.6H33.1c-.7-7.1-1.1-14.3-1.1-21.6zm62.7 133.3h83.1c3.1 24.4 9.5 47.7 18.7 68.7-23.5 6.6-45.5 16.3-62.5 29.9-20.4-26.1-34.2-57.5-39.3-91.6zm65.4-133.3c0-22.7 2.1-44.7 6-66.2 19.6 6.1 40.3 9.5 62 9.5s42.4-3.4 62-9.5c3.9 21.5 6 43.5 6 66.2s-2.1 44.7-6 66.2c-19.6-6.1-40.3-9.5-62-9.5s-42.4 3.4-62 9.5c-3.9-21.5-6-43.5-6-66.2zm67.3 183.8c15.1-9.5 29.4-20.4 42.8-32.6 13.3 12.1 27.7 23.1 42.8 32.6-13.9 6.2-29.1 9.8-45.1 9.8s-31.2-3.5-45.1-9.8zm125.7-25.4c9.2-21 15.6-44.3 18.7-68.7h83.1c-5.1 34.1-18.9 65.5-39.3 91.6-17-13.6-39-23.3-62.5-29.9zm93.2-133.1h-94.9c2.7-17.4 4.1-35.4 4.1-53.6s-1.4-36.2-4.1-53.6h94.9c.7 7.1 1.1 14.3 1.1 21.6s-.4 14.5-1.1 21.6zM301.5 430.8c11.6-18.4 20.7-39 27.1-60.8h82.8c-9.8 26.4-24.4 50.3-42.8 70.3-19.5-5.4-38.1-12.6-55.8-21.2z"/></svg>


        <span> Geography</span>
        <span class="menu-arrow">
    </a>
    <ul>
        <li>
            <a href="<?= Url::toRoute(['/admin/country']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/country']) ? 'active' : '' ?>">Country </span></a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/state']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/state']) ? 'active' : '' ?>">State</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/district']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/district']) ? 'active' : '' ?>">District</a>
        </li>
    </ul>
</li>


<!-- <li class="submenu">
    <a href="#">
        <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M320 32c-6.7 0-13.3 2.1-18.7 6L18.7 198.4c-14.4 9.6-14.4 30.4 0 40l282.7 160.4c11.3 6.4 25.3 6.4 36.6 0L581.3 238.4c14.4-9.6 14.4-30.4 0-40L338.7 38c-5.4-3.9-12-6-18.7-6zM46.2 226.7c4.1-2.7 9.4-2.7 13.5 0L320 397.3 580.3 226.7c4.1-2.7 9.4-2.7 13.5 0s6.2 7.6 6.2 12.3v12.3l-270.2 153.3c-10.4 5.9-23.6 5.9-33.9 0L39.9 251.3v-12.3c0-4.7 2.1-9.5 6.2-12.3zM320 426.7L84.7 280c-4.7-2.7-10.5-2.7-15.2 0-4.7 2.7-7.7 7.6-7.7 12.3v96c0 35.3 28.7 64 64 64h341.3c35.3 0 64-28.7 64-64v-96c0-4.7-3-9.5-7.7-12.3s-10.5-2.7-15.2 0L320 426.7z"/></svg>


        <span> Tutorix</span>
        <span class="menu-arrow">
    </a>
    <ul>
    <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-subscription-items']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-subscription-items']) ? 'active' : '' ?>">Subscriptions</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-subscription-items/free-index']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-subscription-items/free-index']) ? 'active' : '' ?>">Free Subscription</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-subscription-items/paid-index']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-subscription-items/paid-index']) ? 'active' : '' ?>">Paid Subscription</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-subscription-items/active-index']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-subscription-items/active-index']) ? 'active' : '' ?>">Subscription Active</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-subscription-items/expaired-index']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-subscription-items/expaired-index']) ? 'active' : '' ?>">Subscription Expaired</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-subscription-items/pending-index']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-subscription-items/panding-index']) ? 'active' : '' ?>">Payment Pending</a>
        </li>


        
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-class']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-class']) ? 'active' : '' ?>">Class </span></a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-subjects']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-subjects']) ? 'active' : '' ?>">Subjects</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-sections']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-sections']) ? 'active' : '' ?>">Sections</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-lectures']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-lectures']) ? 'active' : '' ?>">Lectures</a>
        </li>

        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-subscription-year']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-subscription-year']) ? 'active' : '' ?>">Subscription Years</a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-coupon']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-coupon']) ? 'active' : '' ?>">Coupons</a>
        </li>

        <li>
            <a href="<?= Url::toRoute(['/admin/tutorix-subscriptions']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/tutorix-subscriptions']) ? 'active' : '' ?>">Invoices</a>
        </li>
    </ul>
</li> -->



<li class="submenu">
    <a href="#">
        <!-- <img alt="Total Image" src="../themes/school-management/assets/img/dashicons/Geography-1.png"> -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc. -->
<path d="M320 32c-53 0-96 43-96 96s43 96 96 96 96-43 96-96-43-96-96-96zm0 192c-53 0-96 43-96 96v32h192v-32c0-53-43-96-96-96zm208-64c-35.3 0-64 28.7-64 64v96h48c26.5 0 48 21.5 48 48v32h96v-32c0-26.5-21.5-48-48-48h-48v-96c0-35.3-28.7-64-64-64zm-416 0c-35.3 0-64 28.7-64 64v96H16c-26.5 0-48 21.5-48 48v32h96v-32c0-26.5 21.5-48 48-48h48v-96c0-35.3-28.7-64-64-64zM240 336h160v48H240v-48zM80 336H16v48h64v-48zM624 336h-64v48h64v-48z"/></svg>


        <span> Students</span>
        <span class="menu-arrow">
    </a>
    <ul>
        <li>
            <a href="<?= Url::toRoute(['/admin/student-details/students']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/student-details']) ? 'active' : '' ?>">Student Details </span></a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['/admin/auth-session']) ?>" class="<?= Yii::$app->request->url == Url::to(['/admin/auth-session']) ? 'active' : '' ?>">Login Users </span></a>
        </li>
       
    </ul>
</li>
