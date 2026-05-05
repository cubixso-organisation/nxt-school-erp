<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentHasParent]].
 *
 * @see StudentHasParent
 */
class StudentHasParentQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentHasParent[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentHasParent|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
