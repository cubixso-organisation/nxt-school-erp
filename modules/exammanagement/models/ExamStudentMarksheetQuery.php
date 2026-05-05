<?php

namespace app\modules\exammanagement\models;

/**
 * This is the ActiveQuery class for [[ExamStudentMarksheet]].
 *
 * @see ExamStudentMarksheet
 */
class ExamStudentMarksheetQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return ExamStudentMarksheet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ExamStudentMarksheet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
