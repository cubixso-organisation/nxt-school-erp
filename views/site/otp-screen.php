<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        .otp-card {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .otp-card h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }

        .otp-card p {
            font-size: 16px;
            color: #666;
            margin-bottom: 25px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #007bff;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: 500;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .resend {
            margin-top: 15px;
            font-size: 14px;
        }

        .resend a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .resend a:hover {
            color: #0056b3;
        }
    </style>

    <?php

    use yii\helpers\Url;

    $contact_no = $user->contact_no; // Original contact number

    // Get the last 4 digits
    $last_four_digits = substr($contact_no, -4);

    // Replace the rest with 'x'
    $masked_contact_no = str_repeat("x", strlen($contact_no) - 4) . $last_four_digits;

    ?>
    <div class="container">
        <div class="otp-card">
            <h2>OTP Verification</h2>
            <p>Please enter the OTP sent to your registered mobile number ending with <?= $masked_contact_no ?>.</p>

            <form action="<?= Url::toRoute(['site/verify-otp']) ?>" method="POST">
                <div class="input-group">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                    <input type="text" name="otp_code" maxlength="6" placeholder="Enter OTP" required>
                    <input type="hidden" name="session_code" placeholder="" value="<?= $send_otp ?>">
                    <input type="hidden" name="contact_no" placeholder="" value="<?= $user->contact_no ?>">
                    <input type="hidden" name="user_id" placeholder="" value="<?= $user->id ?>">
                </div>

                <button type="submit" class="btn">Verify OTP</button>

                <div class="resend">
                    <p>Didn’t receive the OTP? <a href="<?= Url::toRoute(['site/send-otp', 'id' => base64_encode($user->id)]) ?>">Resend OTP</a></p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>