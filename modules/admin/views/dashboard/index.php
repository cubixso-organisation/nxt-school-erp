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



$login_type_campus = User::login_type_campus;
?>
<style>
   .bg-glass {
      position: relative;
      background: rgb(34 30 30 / 20%);
      /* Light transparent background for glass effect */
      border: 1.5px solid rgb(147 147 147);
      /* Soft border */
      backdrop-filter: blur(10px);
      /* Background blur for glass effect */
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
      /* Soft shadow */
      border-radius: 15px;
      overflow: hidden;
      /* To clip any child elements (e.g., background) */
      transition: transform 0.3s ease, box-shadow 0.3s ease;
   }

   .bg-glass:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
      /* Shadow effect on hover */
   }

   .bg-glass::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      /* background-image: url(https://previews.123rf.com/images/fontgraf/fontgraf1709/fontgraf170900016/85323447-school-supplies-frame-on-a-green-background.jpg); */
      background-color: #ffffff29;
      background-size: cover;
      background-position: center;
      filter: blur(4px);
      z-index: 0;
      opacity: 0.86;
   }

   .stats .card-body {
      position: relative;
      z-index: 1;
      /* Ensure content stays above the background */
      padding: 20px !important;
      color: #000;
      /* Black text to contrast with the glass background */
   }

   .stats .card-title {
      font-size: 18px !important;
      font-weight: 600;
      color: #000;
      /* Keep green title */
      text-align: center;
      margin-bottom: 10px;
   }

   .stats .card-text {
      font-size: 32px !important;
      color: #14549B;
      text-align: center;
      font-weight: bold;
   }

   .display-4 {
      font-size: 2.5rem;
      font-weight: 700;
   }

   .row.stats {
      /* gap: 20px; */
   }

   @media (max-width: 768px) {
      .display-4 {
         font-size: 2rem;
      }

      .stats .card-title {
         font-size: 16px;
      }
   }

   /* Modern and minimal card design */
   /* Modern and minimal card design */
   .modern-card {
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      border: 1.5px solid rgb(9 100 0);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      background-color: #ffffff;
      margin-bottom: 20px;
      /* Adds space between cards */
   }

   .modern-card:hover {
      transform: translateY(-5px);
   }

   .modern-card-header {
      padding: 15px;
      border-bottom: 1px solid #e0e0e0;
   }

   .modern-card-title {
      font-size: 18px;
      font-weight: 600;
      color: #333;
   }

   .modern-card-body {
      padding: 15px;
   }

   .modern-btn {
      background-color: #14549b;
      /* Green button */
      border: none;
      color: #fff;
      padding: 10px 20px;
      /* Increased padding for a more modern look */
      font-size: 14px;
      border-radius: 6px;
      /* Rounded buttons */
      transition: background-color 0.3s ease, transform 0.3s ease;
      margin-top: 10px;
      /* Adds space between buttons and content */
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      /* Subtle button shadow */
   }

   .modern-btn:hover {
      background-color: rgb(26, 112, 211);
      /* Darker green on hover */
      transform: translateY(-3px);
      /* Slight hover effect */
   }

   .section-title {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 15px;
      color: #000;
   }

   /* Sub-cards for campuses */
   .modern-sub-card {
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      margin-top: 15px;
      padding: 15px;
   }

   .modern-sub-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 10px;
      border-bottom: 1px solid #f0f0f0;
   }

   .modern-sub-card h5 {
      font-size: 16px;
      font-weight: 600;
   }
</style>


<div class="row stats" style="margin-top: 32px;">
   <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
      <div class="card bg-glass">
         <div class="card-body">
            <h4 class="card-title">Total Group Of Institutions</h4>
            <p class="card-text display-4"><?= $total_group_of_institutes ?></p>
         </div>
      </div>
   </div>

   <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
      <div class="card bg-glass">
         <div class="card-body">
            <h4 class="card-title">Total Individual Campus</h4>
            <p class="card-text display-4"><?= $total_group_of_individual ?></p>
         </div>
      </div>
   </div>

   <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
      <div class="card bg-glass">
         <div class="card-body">
            <h4 class="card-title">Total Units</h4>
            <p class="card-text display-4"><?= $total_campus ?></p>
         </div>
      </div>
   </div>

   <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
      <div class="card bg-glass">
         <div class="card-body">
            <h4 class="card-title">Total Students</h4>
            <p class="card-text display-4"><?= $total_students ?></p>
         </div>
      </div>
   </div>

   <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
      <div class="card bg-glass">
         <div class="card-body">
            <h4 class="card-title">Total Teachers</h4>
            <p class="card-text display-4"><?= $total_teachers ?></p>
         </div>
      </div>
   </div>

   <?php foreach ($stateWiseCounts as $key => $value) { ?>
      <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
         <div class="card bg-glass"> <!-- Apply bg-glass here -->
            <div class="card-body">
               <h4 class="card-title"><?= $key ?></h4>
               <p class="card-text display-4"><?= $value ?></p>
            </div>
         </div>
      </div>
   <?php } ?>
</div>

<!-- foreach loop for state-wise cards -->





</div>

<?php if (User::isAdmin()) { ?>
   <div class="row">
      <?php if (!empty($data['all_group_of_institutions'])) { ?>
         <div class="col-md-12">
            <h4 class="section-title">Group Of Institutions</h4>
         </div>
         <?php foreach ($data['all_group_of_institutions'] as $all_group_of_institutions_data) { ?>
            <div class="col-12 col-md-6 col-lg-3 d-flex">
               <div class="modern-card flex-fill">
                  <div class="modern-card-header">
                     <h5 class="modern-card-title"><?= $all_group_of_institutions_data->name_of_the_educational_Institution ?></h5>
                  </div>
                  <div class="modern-card-body">
                    <?php $login_type_institutes = User::login_type_institutes; ?>
                     <a href="<?= Url::to(['/admin/users/auto-login', 'id' => $all_group_of_institutions_data->id, 'type' => $login_type_institutes]) ?>">                       
 <button class="btn modern-btn">Login</button>
                     </a>
                     <button class="btn modern-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExampleIns-<?= $all_group_of_institutions_data->id ?>" aria-expanded="false">
                        Dashboard
                     </button>
                  </div>
               </div>
            </div>
            <div class="collapse" id="collapseExampleIns-<?= $all_group_of_institutions_data->id ?>">
               <div class="modern-card-body">
                  <?php if (!empty($all_group_of_institutions_data->campuses)) {
                     foreach ($all_group_of_institutions_data->campuses as $campuses_data) { ?>
                        <div class="modern-sub-card">
                           <div class="modern-sub-card-header">
                              <h5><?= $campuses_data->name_of_the_educational_Institution ?></h5>
                              <?php $login_type_campus = User::login_type_campus; ?>
                              <a href="<?= Url::to(['/admin/users/auto-login', 'id' => $campuses_data->id, 'type' => $login_type_campus]) ?>">
                                 <button class="btn modern-btn">Login</button>
                              </a>
                              <button class="btn modern-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExampleCamp-<?= $campuses_data->id ?>" aria-expanded="false">
                                 Dashboard
                              </button>
                           </div>
                           <div class="collapse" id="collapseExampleCamp-<?= $campuses_data->id ?>">
                              <div class="modern-card-body">
                                 <?= campus::getCampusDashBoardCards($campuses_data->id) ?>
                              </div>
                           </div>
                        </div>
                  <?php }
                  } ?>
               </div>
            </div>
         <?php } ?>
      <?php } ?>
   </div>

   <div class="row">
      <?php if (!empty($data['all_individual_campus'])) { ?>
         <div class="col-md-12">
            <h4 class="section-title">Individual Campus</h4>
         </div>
         <?php foreach ($data['all_individual_campus'] as $all_individual_campus_data) { ?>
            <div class="col-12 col-md-6 col-lg-3 d-flex">
               <div class="modern-card flex-fill">
                  <div class="modern-card-header">
                     <h5 class="modern-card-title"><?= $all_individual_campus_data->name_of_the_educational_Institution ?></h5>
                     <h6 class="text-danger">
                        Expiry Date:
                        <?= !empty($all_individual_campus_data->expiry_date) && $all_individual_campus_data->expiry_date != '0000-00-00'
                           ? date('d F Y', strtotime($all_individual_campus_data->expiry_date))
                           : "Not Set" ?>
                     </h6>
                  </div>
                  <div class="modern-card-body">
                     <a class="btn modern-btn" href="<?= Url::to(['/admin/users/auto-login', 'id' => $all_individual_campus_data->id, 'type' => $login_type_campus]) ?>">Login</a>
                     <button class="btn modern-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample-<?= $all_individual_campus_data->id ?>" aria-expanded="false">
                        Dashboard
                     </button>
                  </div>
               </div>
            </div>
            <div class="collapse" id="collapseExample-<?= $all_individual_campus_data->id ?>">
               <div class="modern-card-body">
                  <?= campus::getCampusDashBoardCards($all_individual_campus_data->id) ?>
               </div>
            </div>
         <?php } ?>
      <?php } ?>
   </div>
<?php } ?>
