<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[AssessmentResults]].
 *
 * @see AssessmentResults
 */
class AssessmentResultsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return AssessmentResults[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AssessmentResults|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
