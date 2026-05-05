<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[AppBanner]].
 *
 * @see AppBanner
 */
class AppBannerQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return AppBanner[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AppBanner|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
