<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[UserOtp]].
 *
 * @see UserOtp
 */
class UserOtpQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return UserOtp[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserOtp|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
