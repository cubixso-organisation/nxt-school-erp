<?php

namespace app\modules\documentgenerator\models;

/**
 * This is the ActiveQuery class for [[BonafideCertificate]].
 *
 * @see BonafideCertificate
 */
class BonafideCertificateQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return BonafideCertificate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BonafideCertificate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
