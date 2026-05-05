<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TeacherHasStudents]].
 *
 * @see TeacherHasStudents
 */
class TeacherHasStudentsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return TeacherHasStudents[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TeacherHasStudents|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
