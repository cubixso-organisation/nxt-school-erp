<?php

namespace app\modules\admin\models\search;

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\FeeStructures;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\PayFees;
use app\modules\admin\models\PaymentDetails;

/**
 * app\modules\admin\models\search\PayFeesSearch represents the model behind the search form about `app\modules\admin\models\PayFees`.
 */
class PayFeesSearch extends PayFees
{
    public $academic_year;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'academic_year', 'student_id', 'fee_structures_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['reference_number', 'created_on', 'updated_on', 'section_id', 'student_class_id', 'academic_year_id'], 'safe'],
            [['fees_cut'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PayFees::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'campus_id' => $this->campus_id,
            'student_id' => $this->student_id,
            'fee_structures_id' => $this->fee_structures_id,
            'fees_cut' => $this->fees_cut,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'pay_fees.academic_year_id' => $this->academic_year_id
        ]);


        $query->andFilterWhere(['like', 'reference_number', $this->reference_number]);

        return $dataProvider;
    }



    public function campusSearch($params, $pending_fee = '', $academic_year_id = '')
    {
        // var_dump();exit;
        $query = PayFees::find();

        $query->where(['pay_fees.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);
        // var_dump($query);exit;
        $query->joinWith('student.studentClass');
        $query->joinWith('student.section');
        if (!empty($pending_fee)) {

            $query->andWhere(['>', 'balance_fee', 0]);
        }
        if (!empty($params['PayFeesSearch']['academic_year_id'])) {
            $query->andWhere(['pay_fees.academic_year_id' => $params['PayFeesSearch']['academic_year_id']]);
        }



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],

        ]);







        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pay_fees.id' => $this->id,
            'pay_fees.campus_id' => $this->campus_id,
            'pay_fees.student_id' => $this->student_id,
            'pay_fees.fee_structures_id' => $this->fee_structures_id,
            'pay_fees.fees_cut' => $this->fees_cut,
            'pay_fees.status' => $this->status,
            'pay_fees.created_on' => $this->created_on,
            'pay_fees.updated_on' => $this->updated_on,
            'pay_fees.create_user_id' => $this->create_user_id,
            'pay_fees.update_user_id' => $this->update_user_id,
            'class_sections.id' => $this->section_id,
            'student_class.id' => $this->student_class_id,
            'pay_fees.academic_year_id' => $this->academic_year ?: $this->academic_year_id



        ]);


        $query->andFilterWhere(['like', 'pay_fees.reference_number', $this->reference_number]);
        // var_dump($query->createCommand()->getRawSql());
        // exit;
        return $dataProvider;
    }





    public function campusSubAdminSearch($params, $pending_fee = '', $academic_year_id = '')
    {
        $query = PayFees::find();

        $query->where(['pay_fees.campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)]);

        $query->joinWith('student.studentClass');
        $query->joinWith('student.section');
        if (!empty($params['PayFeesSearch']['academic_year_id'])) {
            $query->andWhere(['pay_fees.academic_year_id' => $params['PayFeesSearch']['academic_year_id']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);





        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pay_fees.id' => $this->id,
            'pay_fees.campus_id' => $this->campus_id,
            'pay_fees.student_id' => $this->student_id,
            'pay_fees.fee_structures_id' => $this->fee_structures_id,
            'pay_fees.fees_cut' => $this->fees_cut,
            'pay_fees.status' => $this->status,
            'pay_fees.created_on' => $this->created_on,
            'pay_fees.updated_on' => $this->updated_on,
            'pay_fees.create_user_id' => $this->create_user_id,
            'pay_fees.update_user_id' => $this->update_user_id,
            'class_sections.id' => $this->section_id,
            'student_class.id' => $this->student_class_id,
            'pay_fees.academic_year_id' => $this->academic_year ?: $this->academic_year_id



        ]);


        $query->andFilterWhere(['like', 'pay_fees.reference_number', $this->reference_number]);

        return $dataProvider;
    }
}
