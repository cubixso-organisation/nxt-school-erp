<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[AcademicYears]].
 *
 * @see AcademicYears
 */
class AcademicYearsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return AcademicYears[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AcademicYears|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
