<?php

namespace app\modules\exammanagement\models;

/**
 * This is the ActiveQuery class for [[ScheduledExamMarksDevision]].
 *
 * @see ScheduledExamMarksDevision
 */
class ScheduledExamMarksDevisionQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ScheduledExamMarksDevision[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ScheduledExamMarksDevision|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
