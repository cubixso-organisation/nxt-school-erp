<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[DaycareTeachers]].
 *
 * @see DaycareTeachers
 */
class DaycareTeachersQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return DaycareTeachers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DaycareTeachers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
