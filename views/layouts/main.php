
<?php
use yii\helpers\Html;
use app\modules\admin\models\WebSetting;
$setting = new WebSetting();
$title = $setting->getSettingBykey('website_title');
$meta_des = $setting->getSettingBykey('home_page_meta_description');
$icon = $setting->getSettingBykey('website_favicon');

use app\assets\LoginAsset;
LoginAsset::register($this);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
  <meta name="verify-admitad" content="a02dbb88a1"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description"  content="<?php echo $meta_des ; ?>" />
  <meta name="title"  content="<?php echo $title ; ?>" />
  <link rel="icon" href="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/uploads/<?php echo $icon;?>" type="image/x-icon">
	<?= Html::csrfMetaTags() ?>
	<title><?= isset($title)?$title:'' ?></title>
	<?php $this->head() ?>
</head>


<body>
<?php $this->beginBody() ?>



		<?= $content ?>



<?php  $this->render('//partials/footer'); ?>

<?php $this->endBody() ?>





</body>
</html>
<?php $this->endPage() ?>
