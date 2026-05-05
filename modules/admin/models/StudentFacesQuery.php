<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentFaces]].
 *
 * @see StudentFaces
 */
class StudentFacesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentFaces[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentFaces|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
