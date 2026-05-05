<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[CampusWebSettings]].
 *
 * @see CampusWebSettings
 */
class CampusWebSettingsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return CampusWebSettings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CampusWebSettings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
