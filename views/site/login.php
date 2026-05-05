<?php

use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

use app\widgets\FlashAlert;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        #background-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .login-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #006f7c; /* Slate-like dark background */
            /* border: 5px solid #708090;  */
            color: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
            width: 520px;
            height:355px;
            text-align: center;
            font-family: 'Courier New', Courier, monospace; /* Chalk-like font */
        }

        .login-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #f8f8ff; /* Ghost white for chalk effect */
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8); /* Chalk-like text shadow */
        }

        .form-field {
            margin-bottom: 15px;
            width: 100%;
        }

        .form-field input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #ff9c2a;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #ff9c2a;
        }

        .forgot-password {
            margin-top: -8px;
            /* font-size: 14px; */
        }

        .forgot-password a {
            color: #ff9c2a;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
        label {
    display: none;
    
}
    </style>
</head>

<body>
    <!-- Video Background -->
    <!-- <video id="background-video" autoplay muted loop>
    <source src="<?= Url::base() ?>/web/6915230_Motion Graphics_Motion Graphic_1920x1080.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video> -->

    <!-- Login Form -->
    <div class="login-container">
    
						<h1><img src="<?= Url::base() ?>/web/nxt_logo1.png" alt="logo" style="width:150px;border-radius:10px;"></h1>
						

						<?= FlashAlert::widget() ?>

						<?php $form = ActiveForm::begin([
							'id' => 'login-form',
						]); ?>

						<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

						<?= $form->field($model, 'password')->passwordInput() ?>


						<div class="form-group text-center">
							<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
						</div>

						<?php ActiveForm::end(); ?>


						<div class="forgot-password">


							<p>Forgot your password? <?= Html::a('Restore!', ['auth/password-request']) ?></p>
						</div>

						<!-- <div class="staff-login">
							<a href="<?= Url::toRoute(['staff-login']) ?>" class="btn btn-success bg-transparent text-black btn -sm w-100">Staff Login</a>
						</div> -->

					
    </div>
</body>

</html>
