<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TeacherAttenddence]].
 *
 * @see TeacherAttenddence
 */
class TeacherAttenddenceQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return TeacherAttenddence[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TeacherAttenddence|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
