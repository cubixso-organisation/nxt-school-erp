<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TutorixSections]].
 *
 * @see TutorixSections
 */
class TutorixSectionsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return TutorixSections[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TutorixSections|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
