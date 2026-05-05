<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[EducationalInstitutionTypes]].
 *
 * @see EducationalInstitutionTypes
 */
class EducationalInstitutionTypesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return EducationalInstitutionTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EducationalInstitutionTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
