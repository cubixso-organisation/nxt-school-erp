<?php

namespace app\modules\exammanagement\models;

/**
 * This is the ActiveQuery class for [[ExamHallTicket]].
 *
 * @see ExamHallTicket
 */
class ExamHallTicketQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ExamHallTicket[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ExamHallTicket|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
