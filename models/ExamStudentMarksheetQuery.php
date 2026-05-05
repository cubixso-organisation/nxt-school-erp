<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\app\models\ExamStudentMarksheet]].
 *
 * @see \app\models\ExamStudentMarksheet
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
     * @return \app\models\ExamStudentMarksheet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\ExamStudentMarksheet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
