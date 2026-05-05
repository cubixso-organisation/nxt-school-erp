<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[AttendanceTimeTables]].
 *
 * @see AttendanceTimeTables
 */
class AttendanceTimeTablesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return AttendanceTimeTables[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AttendanceTimeTables|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
