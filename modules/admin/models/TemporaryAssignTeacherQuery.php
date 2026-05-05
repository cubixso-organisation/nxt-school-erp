<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TemporaryAssignTeacher]].
 *
 * @see TemporaryAssignTeacher
 */
class TemporaryAssignTeacherQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return TemporaryAssignTeacher[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TemporaryAssignTeacher|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
