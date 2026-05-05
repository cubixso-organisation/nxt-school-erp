<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\app\models\LibraryMembers]].
 *
 * @see \app\models\LibraryMembers
 */
class LibraryMembersQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\LibraryMembers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\LibraryMembers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
