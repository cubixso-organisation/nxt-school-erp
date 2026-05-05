<?php

namespace app\modules\documentgenerator\models;

/**
 * This is the ActiveQuery class for [[GeneratedCertificateData]].
 *
 * @see GeneratedCertificateData
 */
class GeneratedCertificateDataQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return GeneratedCertificateData[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return GeneratedCertificateData|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
