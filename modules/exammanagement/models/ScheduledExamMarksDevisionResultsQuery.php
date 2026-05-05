<?php

namespace app\modules\exammanagement\models;

/**
 * This is the ActiveQuery class for [[ScheduledExamMarksDevisionResults]].
 *
 * @see ScheduledExamMarksDevisionResults
 */
class ScheduledExamMarksDevisionResultsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ScheduledExamMarksDevisionResults[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ScheduledExamMarksDevisionResults|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
