<?php

namespace app\modules\librarymanagement\models;

/**
 * This is the ActiveQuery class for [[LibrarySchoolsWise]].
 *
 * @see LibrarySchoolsWise
 */
class LibrarySchoolsWiseQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return LibrarySchoolsWise[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LibrarySchoolsWise|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
