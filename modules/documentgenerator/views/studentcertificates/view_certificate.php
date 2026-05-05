<style type="text/css">
    * {
        padding: 0;
        margin: 0;
    }

    body {
        font-family: 'arial';
    }

    .tc-container {
        width: 100%;
        position: relative;
        text-align: center;
        padding: 2%;
    }

    .tc-container tr td {
        vertical-align: bottom;
    }

    /*.tc-container{
        width: 100%;
        padding: 2%;
        position: relative;
        z-index: 2;
    }*/
    .tcmybg {
        background: top center;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 1;
    }

    .tc-container tr td h1,
    h2,
    h3 {
        margin-top: 0;
        font-weight: normal;
    }

    /*@media (max-width:210mm) and (min-width:297mm){
        .tc-container{
            margin-top: 200px;
            margin-bottom: 100px;}
    }*/
</style>


<div class="" style="position: relative; text-align: center; font-family: 'arial';">
    <img src="<?= $model->background_image ?>" style="width: 100%;" />

    <table width="100%" cellspacing="0" cellpadding="0" style="position: absolute;top: 0; margin-left: auto;margin-right: auto;left: 0;right: 0;width:810px">
        <tr>
            <td style="position: absolute;right:0;">
                <img style="position: relative; top:230px;" src="<?= $model->student_image ?>" width="100" height="auto">
            </td>
        </tr>
        <tr>
            <td valign="top" style="text-align:left; position: relative; top:360px"><?= $model->header_left_text ?></td>
            <td valign="top" style="text-align:center; position: relative; top:360px"><?= $model->header_center_text ?></td>
            <td valign="top" style="text-align:right; position: relative; top:360px"><?= $model->header_right_text ?></td>
        </tr>
        <tr>
            <td colspan="3" valign="top" style="position: relative; top:400px">
                <p style="font-size: 14px; line-height: 24px; text-align:center;"><br><?= $model->body_text ?></p>
            </td>
        </tr>
        <tr>
            <td valign="top" style="text-align:left;position: relative; top:480px">.................................<br><?= $model->footer_left_text ?></td>
            <td valign="top" style="text-align:center;position: relative; top:480px">.................................<br><?= $model->footer_center_text ?></td>
            <td valign="top" style="text-align:right;position: relative; top:480px">.................................<br><?= $model->footer_right_text ?></td>
        </tr>
    </table>
</div>