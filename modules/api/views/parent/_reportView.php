<?php




use yii\helpers\Html;
use kartik\export\ExportMenu;
use app\models\User;
use app\modules\admin\models\base\Banner;
use app\modules\admin\models\PaymentDetails;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Payment Details');
$this->params['breadcrumbs'][] = $this->title;


?>




<div class="payment-details-index">

    <div class="card">
       <div class="card-body">
        <table style="  border-collapse: collapse; width: 100%; border: 1px solid black;" >
           <tr>
            <td style="border: 1px solid black;">Payment Id</td>
            <td style="border: 1px solid black;"><?= !empty($payment_details->paid_reference_number)?$payment_details->paid_reference_number:'' ?></td>
           </tr>
           <?php if($payment_details->payment_mode==PaymentDetails::payment_mode_online){ ?>
           <tr>
            <td style="border: 1px solid black;">Approval Status</td>
            <td style="border: 1px solid black;"><?= $payment_details->status==1?'success':'pending' ?></td>
           </tr>
           <?php }else{?>
            <tr>
            <td style="border: 1px solid black;">Collected By</td>
            <td style="border: 1px solid black;"><?= $payment_details->fee_collected_by ?></td>
           </tr>

       <?php     } ?>

           <tr>
            <td style="border: 1px solid black;">Paid On</td>
            <td style="border: 1px solid black;"><?= $payment_details->created_on ?></td>
           </tr>


           <tr>
            <td style="border: 1px solid black;">Payment Mode</td>
            <td style="border: 1px solid black;"> <?php
            if( $payment_details->payment_mode==PaymentDetails::payment_mode_online){
               echo "online";
            }else if( $payment_details->payment_mode==PaymentDetails::payment_mode_offline){
               echo "cash";

            }else if( $payment_details->payment_mode==PaymentDetails::payment_mode_net_banking){
               echo "Net Banking";

            }
           
            
            ?></td>
           </tr>

           
           <tr>
            <td style="border: 1px solid black;">Amount</td>
            <td style="border: 1px solid black;"> <?= $payment_details->paid_amount ?></td>
           </tr>


        </table>
       </div>
    </div>


</div>


