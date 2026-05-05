<?php

namespace app\modules\librarymanagement\models;

/**
 * This is the ActiveQuery class for [[LibraryRacks]].
 *
 * @see LibraryRacks
 */
class LibraryRacksQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return LibraryRacks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LibraryRacks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
