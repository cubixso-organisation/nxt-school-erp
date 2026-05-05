<?php

namespace app\modules\exammanagement\models;

/**
 * This is the ActiveQuery class for [[ExamSchedules]].
 *
 * @see ExamSchedules
 */
class ExamSchedulesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ExamSchedules[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ExamSchedules|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
