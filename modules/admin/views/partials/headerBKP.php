<style>
   .notification-count {
      position: absolute;
      top: 0;
      right: 0;
      background-color: red;
      color: white;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 12px;
   }
</style>

<style>
   #searchDropdown {
      display: none;
      position: absolute;
      background-color: white !important;
      border: 1px solid #ccc;
      z-index: 1;
      max-height: 200px;
      color: #000 !important;
      overflow-y: auto;
      width: 100%;
   }

   #searchDropdown .dropdown-item {
      color: #000 !important;
   }

   #searchDropdown .dropdown-item {
      cursor: pointer;
      padding: 10px;
      color: #000;
      border-bottom: 1px solid #ccc;
   }

   #searchDropdown .dropdown-item:last-child {
      border-bottom: none;
   }

   #searchDropdown .dropdown-item a {
      color: black;
      text-decoration: none;
   }

   #searchDropdown .dropdown-item a:hover {
      color: green;
   }

   .float-container {
      position: fixed;
      top: 33%;
      right: 0;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      flex-direction: column;
      width: auto;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      -webkit-box-align: end;
      -ms-flex-align: end;
      align-items: flex-end;
   }

   .float-container a {
      z-index: 99;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      width: 200px;
      height: 30px;
      margin-right: -190px;
      margin-bottom: 10px;
      padding: 10px 20px;
      -webkit-transition: all 0.3s ease-in-out;
      transition: all 0.3s ease-in-out;
      text-decoration: none;
      color: white;
      border-color: #46b8da;
      border-radius: 5px 0 0 5px;
      background-color: #eb690b;
      -webkit-box-shadow: 0 2px 4px #7d7d7d;
      box-shadow: 0 2px 4px #7d7d7d;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      -webkit-box-pack: start;
      -ms-flex-pack: start;
      justify-content: flex-start;
      font-family: sans-serif;
   }

   .float-container a:hover {
      margin-right: 0;
      background-color: #c45100;
      -webkit-box-shadow: 0 2px 4px #7d7d7d;
      box-shadow: 0 2px 4px #7d7d7d;
   }


   /* Media queries */
   @media screen and (max-width:440px) {
      .float-container .icon:last-child {
         display: none;
      }

      .float-container {
         position: fixed;
         top: auto;
         bottom: 0;

         display: -webkit-box;
         display: -ms-flexbox;
         display: flex;
         -ms-flex-direction: row;
         flex-direction: row;

         width: 100%;

         -webkit-box-orient: vertical;
         -webkit-box-direction: normal;
         -ms-flex-direction: auto;
         -webkit-box-align: auto;
         -ms-flex-align: auto;
         align-items: auto;
      }

      .float-container a.icon {
         right: 0;
         bottom: 0;

         width: 100%;
         margin-right: 0;
         margin-bottom: 0;
         padding: 5px;

         border-radius: 0;
         -webkit-box-shadow: 0 0 0 #7d7d7d;
         box-shadow: 0 0 0 #7d7d7d;
         -webkit-box-pack: center;
         -ms-flex-pack: center;
         justify-content: center;
         border-left: 1px solid darkorange;
         border-right: 1px solid darkorange;
      }
   }
</style>
<?php

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\admin\models\Notification;

$session = Yii::$app->session;
?>
<?php
$common_logo = Url::base() . '/web/new_logo.png';
// echo $common_logo;
// exit;



if (User::isInstituteAdmin()) {
   $getInstituteIdOfUser = (new Institutes())->getInstituteIdOfUser();
   $name_of_the_educational_Institution = Institutes::find()->where(['id' => $getInstituteIdOfUser])->one();
   $logo =  !empty($name_of_the_educational_Institution->school_logo) ? $name_of_the_educational_Institution->school_logo : $common_logo;
} elseif (User::isCampusAdmin() || User::isChefWarden()) {
   // Check if the user is either CampusAdmin or ChefWarden
   $campus = User::getCampusesByUser(Yii::$app->user->identity->id);
   $campus_name = Campus::find()->where(['id' => $campus])->one();
   $logo =  !empty($campus_name->school_logo) ? $campus_name->school_logo : $common_logo;
} elseif (User::isAdmin()) {
   $logo = $common_logo;
} elseif (User::isCampusSubAdmin()) {
   $logo = $common_logo;
} elseif (User::isLibraryManager()) {
   $logo = $common_logo;
}

?>

<?php
$error = Yii::$app->session->getFlash('error');
$success = Yii::$app->session->getFlash('success');

if ($error) {
   $this->registerJs("
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
        };
        toastr.error('$error');
    ");
}

if ($success) {
   $this->registerJs("
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
        };
        toastr.success('$success');
    ");
}
?>
<div class="header">
   <div class="header-left mt-2">
      <a href="<?= Yii::$app->homeUrl ?>" class="logo">
         <img src="<?= $logo ?>" alt="Logo" width="120" height="120">

      </a>
      <a href="<?= Yii::$app->homeUrl ?>" class="logo logo-small">
         <img src="<?= $logo ?>" alt="Logo" width="30" height="30">
      </a>
   </div>
   <div class="menu-toggle">
      <a href="javascript:void(0);" id="toggle_btn">
         <i class="fas fa-bars"></i>
      </a>
   </div>

   <div class="top-nav-search">
      <form>
         <input type="text" class="form-control" id="searchInput" placeholder="Search here" oninput="handleInput(this.value)">
         <div id="searchDropdown" class="dropdown-menu" style="display: none;"></div>
         <!-- <button class="btn" type="submit"><i class="fas fa-search"></i></button> -->
      </form>
   </div>
   <a class="mobile_btn" id="mobile_btn">
      <i class="fas fa-bars"></i>
   </a>
   <ul class="nav user-menu">
      <?php
      $loginUserFrom = Yii::$app->session->get('loginUserFrom');
      $loginFromInstitute = Yii::$app->session->get('loginFromInstitute');
      if (!empty($loginUserFrom)) {
         $institutes = Institutes::find()->where(['id' => $loginFromInstitute])->one();

         //get user
         $user = User::find()->where(['id' => $loginUserFrom])->one();
         if ($user->user_role == User::ROLE_ADMIN) {
            if ($institutes->subscription_type ?? 0 == Institutes::subscription_type_individual_institution) {
               if ($loginUserFrom != Yii::$app->user->identity->id) {
                  echo Html::a('Admin Login', ["/admin/users/go-to-admin", 'id' => $loginUserFrom], ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px;']);
               }
            } else {
               $identity_of_id =   Yii::$app->user->identity->id;
               $get_current_user = User::find()->where(['id' => $identity_of_id])->one();

               if ($user->user_role == User::ROLE_ADMIN && $get_current_user->user_role == User::ROLE_INSTITUTE_ADMIN) {
                  echo    Html::a('Admin Login', ["/admin/users/go-to-admin", 'id' => $loginUserFrom], ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px;']);
               } else {
                  if ($get_current_user->user_role != User::ROLE_ADMIN) {
                     $user_id = $institutes->user_id;
                     echo    Html::a('Admin Login', ["/admin/users/go-to-admin", 'id' => $user_id], ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px;']);
                  }
               }
            }
         } else {
            if (!empty($loginFromInstitute)) {
               $institutes = Institutes::find()->where(['id' => $loginFromInstitute])->one();

               if (!empty($institutes)) {
                  if ($institutes->subscription_type == Institutes::subscription_type_group_of_institutions) {
                     if ($loginUserFrom != Yii::$app->user->identity->id) {
                        echo    Html::a('Admin Login', ["/admin/users/go-to-admin", 'id' => $loginUserFrom], ['class' => 'btn btn-primary']);
                     }
                  }
               }
            }
         }
      }
      ?>
      <?php if (Yii::$app->user->identity->user_role == User::isCampusAdmin()) : ?>
         <li class="nav-item dropdown noti-dropdown language-drop me-2">
            <a href="#" class="dropdown-toggle nav-link header-nav-list" data-bs-toggle="dropdown">
               <img src="<?= Url::base() . '/web/ThemeColors.png' ?>" width="100%" alt="">
            </a>
            <div class="dropdown-menu">
               <div class="noti-content" id="color-drop-down">
                  <!-- Color Selection Form -->
                  <?= Html::beginForm(['dashboard/change-themes-color'], 'post', ['class' => 'row']) ?>
                  <!-- First Color Picker for Background Color -->
                  <div class="form-group col-md-6" style="padding-left: 25px;padding-top: 5px;">
                     <?= Html::label('Background') ?>
                     <?= Html::input('color', 'bg_color_preference', Yii::$app->user->identity->bg_color_preference, ['class' => 'form-control mb-2']) ?>
                  </div>

                  <!-- Second Color Picker for Button Color -->
                  <div class="form-group col-md-6" style="padding-right: 25px;padding-top: 5px;">
                     <?= Html::label('Buttons') ?>
                     <?= Html::input('color', 'button_color_preference', Yii::$app->user->identity->button_color_preference, ['class' => 'form-control mb-2']) ?>
                  </div>

                  <!-- Change Color Button -->
                  <div class="form-group col-md-12" style="padding-left: 25px;">
                     <?= Html::submitButton('Change Color', ['class' => 'btn btn-primary']) ?>
                  </div>
                  <?= Html::endForm() ?>
               </div>
            </div>
         </li>
      <?php endif; ?>











      <li class="nav-item dropdown noti-dropdown me-2">
         <a href="#" class="dropdown-toggle nav-link header-nav-list" data-bs-toggle="dropdown">
            <img src="<?= Url::base() . '/web/Notification_.png' ?>" width="100%" alt="">
            <span class="notification-count"></span> <!-- Change the number to reflect the notification count -->
         </a>
         <div class="dropdown-menu notifications">
            <div class="topnav-dropdown-header">
               <span class="notification-title">Notifications</span>
               <a href="<?= Url::to(Yii::$app->request->baseUrl . '/admin/dashboard/clear-notification') ?>" class="clear-noti"> Clear All </a>
            </div>
            <div class="noti-content">
               <ul id="append-notification" class="notification-list">
                  <!-- Dynamic Notification -->
               </ul>
            </div>

         </div>
      </li>
      <li class="nav-item dropdown has-arrow new-user-menus">
         <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
            <span class="user-img">

               <div class="user-text">
                  <h6><?= Yii::$app->user->identity->username ?></h6>
                  <p class="text-muted mb-0"><?= Yii::$app->user->identity->user_role ?></p>
               </div>
            </span>
         </a>
         <div class="dropdown-menu">
            <?php
            $userRole = Yii::$app->user->identity->user_role;
            if ($userRole != User::ROLE_CHEF_WARDEN) {
               echo '<a class="dropdown-item" href="' . Url::toRoute(['/admin/users/view', 'id' => Yii::$app->user->identity->id]) . '">My Profile</a>';
            }
            ?>
            <?php
            $userRole = Yii::$app->user->identity->user_role;
            if ($userRole == User::ROLE_ADMIN) { // Comparing for equality
               echo '<a class="dropdown-item" href="' . Url::toRoute(['/admin/users/update', 'id' => Yii::$app->user->identity->id]) . '">Change Password</a>';
            }
            ?>
            <a class="dropdown-item" href="<?= Url::toRoute('/auth/logout'); ?>">Logout</a>
         </div>


      </li>
   </ul>
</div>
<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>








<?php $url = Url::to(Yii::$app->request->baseUrl . '/admin/dashboard/dashboard-notification'); ?>
<script>
   $(document).ready(function() {
      // Function to update data
      function updateData() {
         $.ajax({
            url: '<?= $url ?>',
            type: 'GET',
            success: function(response) {
               // Update data on the page
               var notifications = JSON.parse(response);
               if (notifications.status == "OK") {
                  var notificationsHTML = '';
                  notifications.details.forEach(function(notification) {
                     console.log(notification.description);

                     notificationsHTML += '<li class="notification-message">';
                     notificationsHTML += '<a href="">';
                     notificationsHTML += '<div class="media d-flex">';
                     notificationsHTML += '<div class="media-body flex-grow-1">';
                     notificationsHTML += '<p class="noti-details"><span class="noti-title">' + notification.title + ' </span>' + notification.description + '</p>';
                     notificationsHTML += '<p class="noti-time"><span class="notification-time">' + notification.created_on + '</span></p>';
                     notificationsHTML += '</div>';
                     notificationsHTML += '</div>';
                     notificationsHTML += '</a>';
                     notificationsHTML += '</li>';
                  });
                  console.log(notifications.count);
                  // Update data on the page
                  $('.notification-list').empty().html(notificationsHTML);
                  $('.notification-count').empty().html(notifications.count);
               }


            },
            error: function(xhr, status, error) {
               console.error('Error:', error);
            }
         });
      }

      // Initial call to update data
      updateData();

      // Periodically update data every 5 minutes (300000 milliseconds)
      setInterval(updateData, 10000);
   });
</script>