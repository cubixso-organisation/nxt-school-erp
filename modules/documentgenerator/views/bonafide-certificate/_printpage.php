<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin: 0; padding: 0; text-align: center;">

    
    <div class="bonafide" >
            <h1 ><?= $certificate->header_center_text ?></h1>
        </div>
    

    <div class="content" style="text-align: center;">
        <div class="item" style="display: inline-block; vertical-align: top;">
            <p><?= $certificate->header_left_text ?><?= $studentDetails->admission_number?></p>
        </div>
        
        <div class="item7" >
            <p><?= $certificate->header_right_text ?><?= date('Y-m-d')?></p>
        </div>
    </div>

    <div class="bod-text" style="text-align: center;">
        <p style="margin: 0;"><?= $certiDesc ?></p>
    </div>

    <div class="content-footer" style="text-align: right;">
       
        
        <div class="item6" style="display: inline-block; vertical-align: top;">
            <p>
                <?php if (!empty($certificate->right_sig)) { ?>

                    <img src=" <?= $certificate->right_sig ?>" alt="Signature Right" style="width: 120px; height: 60px; display: block; margin: 0 auto;"><br>
                <?php } else { ?>

                <?php } ?>
                <?= $certificate->footer_right_text ?>
            </p>
        </div>
    </div>

</body>

</html>