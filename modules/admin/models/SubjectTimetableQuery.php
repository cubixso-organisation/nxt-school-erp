<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[SubjectTimetable]].
 *
 * @see SubjectTimetable
 */
class SubjectTimetableQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return SubjectTimetable[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubjectTimetable|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
