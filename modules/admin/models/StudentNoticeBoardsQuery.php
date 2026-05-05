<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[StudentNoticeBoards]].
 *
 * @see StudentNoticeBoards
 */
class StudentNoticeBoardsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return StudentNoticeBoards[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentNoticeBoards|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
