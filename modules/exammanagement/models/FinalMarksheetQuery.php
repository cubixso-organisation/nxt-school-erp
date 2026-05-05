<?php

namespace app\modules\exammanagement\models;

/**
 * This is the ActiveQuery class for [[FinalMarksheet]].
 *
 * @see FinalMarksheet
 */
class FinalMarksheetQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return FinalMarksheet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinalMarksheet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
