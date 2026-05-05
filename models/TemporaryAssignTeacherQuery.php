<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\app\models\TemporaryAssignTeacher]].
 *
 * @see \app\models\TemporaryAssignTeacher
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
     * @return \app\models\TemporaryAssignTeacher[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\TemporaryAssignTeacher|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
