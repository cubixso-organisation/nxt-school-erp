<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[PaymentDetails]].
 *
 * @see PaymentDetails
 */
class PaymentDetailsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return PaymentDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PaymentDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
