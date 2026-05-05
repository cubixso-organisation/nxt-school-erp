<?php

namespace app\modules\exammanagement\models;

/**
 * This is the ActiveQuery class for [[GradeDefination]].
 *
 * @see GradeDefination
 */
class GradeDefinationQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return GradeDefination[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return GradeDefination|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
