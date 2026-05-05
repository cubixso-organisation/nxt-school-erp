<?php

namespace app\modules\documentgenerator\models;

/**
 * This is the ActiveQuery class for [[Studentcertificates]].
 *
 * @see Studentcertificates
 */
class StudentcertificatesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Studentcertificates[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Studentcertificates|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
