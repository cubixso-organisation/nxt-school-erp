<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[SpecialCourses]].
 *
 * @see SpecialCourses
 */
class SpecialCoursesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return SpecialCourses[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SpecialCourses|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
