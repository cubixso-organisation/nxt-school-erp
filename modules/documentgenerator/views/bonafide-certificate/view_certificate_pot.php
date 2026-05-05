<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .letter-container {
            width: 21cm;
            /* A4 width */
            height: 27.7cm;
            /* A4 height */
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.8);
            /* Adjust the alpha value for transparency */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            background-image: url('<?= $model->background_image ?>');
            background-size: contain;
            /* You can experiment with different values like 'cover', 'contain', '100% 100%', etc. */
            background-position: cover;
            background-repeat: no-repeat;
        }

        .sample-text {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="letter-container">
        <div class="sample-text">
            <table width="100%" cellspacing="0" cellpadding="0" style="position: absolute;top: 0; margin-left: auto;margin-right: auto;left: 0;right: 0;width:810px">
                <tr>
                    <!-- <td style="position: absolute;right:0;">
                        <img style="position: relative; top:230px;" src="<?= $model->student_image ?>" width="100" height="auto">
                    </td> -->
                </tr>
                <tr>
                    <td valign="top" style="text-align:left;position: relative;top: 420px;right: -30px;"><?= $model->header_left_text ?></td>
                    <td valign="top" style="text-align:center;position: relative;top:420px;left: -100px;"><?= $model->header_center_text ?></td>
                    <td valign="top" style="text-align:right;position: relative;top: 420px;right: 106px;"><?= $model->header_right_text ?></td>
                </tr>
                <tr>
                    <td colspan="3" valign="top" style="position: relative; top:650px">
                        <p style="font-size: 14px; line-height: 30px; text-align:center;"><br><?= $model->body_text ?></p>
                    </td>
                </tr>
                <tr>
                    <!-- <td valign="top" style="text-align:left;position: relative;top: 800px;left: 40px;">.................................<br><?= $model->footer_left_text ?></td> -->
                    <!-- <td valign="top" style="text-align:center;position: relative; top:800px">.................................<br><?= $model->footer_center_text ?></td> -->
                    <td valign="top" style="text-align:right;position: relative; top:800px;left: 100px;">.................................<br><?= $model->footer_right_text ?></td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>