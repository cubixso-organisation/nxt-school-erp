<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

/* @var $model app\forms\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\FlashAlert;


$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

function getUrl()
{
	// Use $_SERVER['HTTP_HOST'] to get the current web URL
	$baseUrl = $_SERVER['HTTP_HOST'];

	return $baseUrl;
}
// function getInfoDomain()
// {

// 	$baseUrl = getUrl();
// 	$curl = curl_init();

// 	curl_setopt_array($curl, array(
// 		CURLOPT_URL => 'https://estudent-central.anxion.co.in/central-db/get-domain-info?domain=' . $baseUrl,
// 		CURLOPT_RETURNTRANSFER => true,
// 		CURLOPT_ENCODING => '',
// 		CURLOPT_MAXREDIRS => 10,
// 		CURLOPT_TIMEOUT => 0,
// 		CURLOPT_FOLLOWLOCATION => true,
// 		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 		CURLOPT_CUSTOMREQUEST => 'GET',
// 		CURLOPT_HTTPHEADER => array(
// 			'Cookie: PHPSESSID=tao5dpja00isi6aap7bgtdimk0; _csrf=bb17b9c99b4b6913daff3ed1af384107246e03efb4755202c123f6ee4ff92fb2a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22qBASpzs1v6SyaIqM6KBHl7yYWUkbEe1c%22%3B%7D'
// 		),
// 	));

// 	$response = curl_exec($curl);

// 	curl_close($curl);
// 	return $response;
// }

// $domianInfo = getInfoDomain();
// $decodeData = json_decode($domianInfo, true);

?>
<style>
	.leftimage {
		align-items: center;
		background-image: url("https://ik.imagekit.io/toupbgbzw/Admin%20Login%20Illustration.png?updatedAt=1724740416729");
		flex-direction: column;
		background-size: cover;
		justify-content: end;
		width: 400px;
		display: flex;
		background-blend-mode: multiply;
		border-radius: 8px 20px 20px 8px;
		position: relative;
	}

	.btn-primary {
		background-color: #24843A;
	}

	.btn-primary:hover {
		background-color: #24843A;
	}
</style>


<div class="main-wrapper login-body">
	<div class="login-wrapper">
		<div class="container">
			<div class="loginbox">
				<div class="leftimage">

				</div>
				<div class="login-right">
					<div class="login-right-wrap">
						<h1>Welcome to


							E Student.</h1>

						<h2>Staff Login</h2>
						<h2>Sign in</h2>


						<?= FlashAlert::widget() ?>





						<?php $form = ActiveForm::begin([
							'id' => 'login-form',
							'action' => 'staff-login'
						]); ?>

						<?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Enter your email or phone number') ?>

						<?= $form->field($model, 'password')->passwordInput() ?>


						<div class="form-group text-center">
							<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
						</div>

						<?php ActiveForm::end(); ?>


						<div class="">


							<p>Forgot your password? <?= Html::a('Restore!', ['auth/password-request']) ?></p>
						</div>

						<div class="staff-login">
							<a href="<?= Url::toRoute(['login']) ?>" class="btn btn-success bg-transparent text-black btn -sm w-100">Admin Login</a>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>