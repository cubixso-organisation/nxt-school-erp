<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[ClassTeacher]].
 *
 * @see ClassTeacher
 */
class ClassTeacherQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ClassTeacher[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ClassTeacher|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
