<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[SubjectGroupsClassSections]].
 *
 * @see SubjectGroupsClassSections
 */
class SubjectGroupsClassSectionsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return SubjectGroupsClassSections[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubjectGroupsClassSections|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
