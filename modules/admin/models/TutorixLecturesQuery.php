<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[TutorixLectures]].
 *
 * @see TutorixLectures
 */
class TutorixLecturesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return TutorixLectures[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TutorixLectures|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
