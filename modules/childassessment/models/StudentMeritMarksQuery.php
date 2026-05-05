<?php

namespace app\modules\childassessment\models;

/**
 * This is the ActiveQuery class for [[StudentMeritMarks]].
 *
 * @see StudentMeritMarks
 */
class StudentMeritMarksQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentMeritMarks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentMeritMarks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
