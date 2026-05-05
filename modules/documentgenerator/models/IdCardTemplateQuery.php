<?php

namespace app\modules\documentgenerator\models;

/**
 * This is the ActiveQuery class for [[IdCardTemplate]].
 *
 * @see IdCardTemplate
 */
class IdCardTemplateQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return IdCardTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return IdCardTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
