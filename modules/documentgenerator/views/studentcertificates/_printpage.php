<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin: 0; padding: 0; text-align: center;">

    <div class="content-img" style="text-align: center;">
        <p><img src="<?= $studentDetails->profile_photo ?>" alt="Student Image" style="width: 90px; height: 90px; display: block; margin: 0 auto;"></p>
    </div>

    <div class="content" style="text-align: center;">
        <div class="item" style="display: inline-block; vertical-align: top;">
            <p><?= $certificate->header_left_text ?></p>
        </div>
        <div class="item2" style="display: inline-block; vertical-align: top;font-size:25px;font-weight: bold;">
            <p><?= $certificate->header_center_text ?></p>
        </div>
        <div class="item3" style="display: inline-block; vertical-align: top;">
            <p><?= $certificate->header_right_text ?></p>
        </div>
    </div>

    <div class="bod-text" style="text-align: center;">
        <p style="margin: 0;"><?= $certiDesc ?></p>
    </div>

    <div class="content-foot" style="text-align: center;">
        <div class="item4" style="display: inline-block; vertical-align: top; margin-top:-30px">
            <p>
                <?php if (!empty($certificate->left_sig)) { ?>
                    <img src="   <?= $certificate->left_sig ?>" alt="Signature Left" style="width: 120px; height: 60px; display: block; margin: 0 auto;"><br>
                <?php } else { ?>

                <?php } ?>

                <?= $certificate->footer_left_text ?>
            </p>
        </div>
        <div class="item5" style="display: inline-block; vertical-align: top;">
            <p>
                <?php if (!empty($certificate->right_sig)) { ?>
                    <img src=" <?= $certificate->right_sig ?>" alt="Signature Center" style="width: 120px; height: 60px; display: block; margin: 0 auto; padding-right:50px"><br>
                <?php } else { ?>

                <?php } ?>
                <?= $certificate->footer_center_text ?>
            </p>
        </div>
        <div class="item6" style="display: inline-block; vertical-align: top;">
            <p>
                <?php if (!empty($certificate->right_sig)) { ?>

                    <img src=" <?= $certificate->center_sig ?>" alt="Signature Right" style="width: 120px; height: 60px; display: block; margin: 0 auto;"><br>
                <?php } else { ?>

                <?php } ?>
                <?= $certificate->footer_right_text ?>
            </p>
        </div>
    </div>

</body>

</html>