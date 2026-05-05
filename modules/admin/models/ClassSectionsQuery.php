<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[ClassSections]].
 *
 * @see ClassSections
 */
class ClassSectionsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ClassSections[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ClassSections|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
