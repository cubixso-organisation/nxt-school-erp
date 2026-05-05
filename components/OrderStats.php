<?php

namespace app\components;

use app\modules\admin\models\DeliveryGold;
use app\modules\admin\models\Orders;
use yii\base\Component;

class OrderStats extends Component
{

    public function getStatusButtons($model)
    {

        switch ($model->delivery_status) {

 
            case Orders::delivery_status_pending:
                return '<button type="button" class="btn btn-success float-right" id="order-status_' . $model->id . '" value="' . Orders::delivery_status_processing . '" data-id="' . $model->id . '"><i class="far fa-check-square"></i> Processing
             </button>&nbsp;
       
             
             ';
                break;

            case Orders::delivery_status_processing:
                return '<button type="button" class="btn btn-success float-right" id="order-status_' . $model->id . '" value="' . Orders::delivery_status_completed . '" data-id="' . $model->id . '"><i class="far fa-check-square"></i> Delivered
                 </button>&nbsp;';
                break;

       
            // default:
            // return '<button type="button" class="btn btn-success float-right" id="order-status_' . $model->id . '" value="' . Orders::delivery_status_completed . '" data-id="' . $model->id . '"><i class="far fa-check-square"></i> Delivered 
            // </button>&nbsp;';


        }
    }



    public function getSellStatusButtons($model)
    {

        switch ($model->delivery_status) {

 
            case Orders::delivery_status_pending:
                return '<button type="button" class="btn btn-success float-right" id="order-status_' . $model->id . '" value="' . Orders::delivery_status_processing . '" data-id="' . $model->id . '"><i class="far fa-check-square"></i> Processing
             </button>&nbsp;
       
             
             ';
                break;

            case Orders::delivery_status_processing:
                return '<button type="button" class="btn btn-success float-right" id="order-status_' . $model->id . '" value="' . Orders::delivery_status_completed . '" data-id="' . $model->id . '"><i class="far fa-check-square"></i> Delivered
                 </button>&nbsp;';
                break;

       
            // default:
            // return '<button type="button" class="btn btn-success float-right" id="order-status_' . $model->id . '" value="' . Orders::delivery_status_completed . '" data-id="' . $model->id . '"><i class="far fa-check-square"></i> Delivered 
            // </button>&nbsp;';


        }
    }





}
