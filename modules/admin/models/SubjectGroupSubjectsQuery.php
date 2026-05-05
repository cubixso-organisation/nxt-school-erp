<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[SubjectGroupSubjects]].
 *
 * @see SubjectGroupSubjects
 */
class SubjectGroupSubjectsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return SubjectGroupSubjects[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubjectGroupSubjects|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
