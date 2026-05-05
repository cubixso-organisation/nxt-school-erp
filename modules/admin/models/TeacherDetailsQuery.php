<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TeacherDetails]].
 *
 * @see TeacherDetails
 */
class TeacherDetailsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return TeacherDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TeacherDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
