<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentHasNotice]].
 *
 * @see StudentHasNotice
 */
class StudentHasNoticeQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentHasNotice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentHasNotice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
