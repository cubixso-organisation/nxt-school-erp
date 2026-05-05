<?php

namespace app\modules\admin\models\search;

use app\models\User;
use app\modules\admin\models\FeeStructures;
use app\modules\admin\models\PayFees;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\PaymentDetails;

/**
 * app\modules\admin\models\search\PaymentDetailsSearch represents the model behind the search form about `app\modules\admin\models\PaymentDetails`.
 */
class PaymentDetailsSearch extends PaymentDetails
{

    public $academic_year;
public $student_id_search;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'academic_year', 'student_id', 'class_id', 'section_id', 'pay_fees_id', 'payment_mode', 'fee_collected_by', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['paid_reference_number', 'remarks','student_id_search', 'razorpay_order_id', 'razorpay_payment_id', 'created_on', 'updated_on'], 'safe'],
            [['paid_amount', 'balance_amount'], 'number'],
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
        $query = PaymentDetails::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'campus_id' => $this->campus_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'pay_fees_id' => $this->pay_fees_id,
            'payment_mode' => $this->payment_mode,
            'paid_amount' => $this->paid_amount,
            'balance_amount' => $this->balance_amount,
            'fee_collected_by' => $this->fee_collected_by,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);
$query->andFilterWhere([
            'student_id' => $this->student_id_search,
        ]);
        $query->andFilterWhere(['like', 'paid_reference_number', $this->paid_reference_number])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'razorpay_order_id', $this->razorpay_order_id])
            ->andFilterWhere(['like', 'razorpay_payment_id', $this->razorpay_payment_id]);

        if (!empty($this->class_id)) {
            $classIds = is_array($this->class_id) ? $this->class_id : [$this->class_id];
            // Use IN condition to filter based on multiple selected class_ids
            $query->andWhere(['in', 'class_id', $classIds]);
        }

        if (isset($this->created_on) && $this->created_on != '') {

            //you dont need the if function if yourse sure you have a not null date
            $date_explode = explode(" - ", $this->created_on);
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['between', 'created_on', $date1, $date2]);
        }
        if (isset($this->updated_on) && $this->updated_on != '') {

            $date_explode = explode(" - ", $this->updated_on);
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['between', 'updated_on', $date1, $date2]);
        }


        return $dataProvider;
    }



    public function campusSearch($params, $student_id = '', $status = '', $academic_year = '', $date = '')
    {

        $query = PaymentDetails::find()->joinWith(['payFees as pf']);
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);


        if (!empty($campus_id)) {
            $query->where(['payment_details.campus_id' => $campus_id]);
        }

        if (!empty($academic_year)) {
            $query->where(['pf.academic_year_id' => $academic_year]);
        }
        if (!empty($student_id)) {
            $query->andWhere(['payment_details.student_id' => $student_id]);
        }

        if (!empty($date)) {
            $query->andWhere(['payment_details.created_on' => $date]);
        }

        if (!empty($status)) {
            $query->andWhere(['payment_details.status' => $status]);
        } elseif ($status === 0) {
            $query->andWhere(['payment_details.status' => PaymentDetails::status_failed]);
        }



        $query->groupBy('id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'payment_details.id' => $this->id,
            'payment_details.campus_id' => $this->campus_id,
            'payment_details.student_id' => $this->student_id,
            'payment_details.class_id' => $this->class_id,
            'payment_details.section_id' => $this->section_id,
            'payment_details.pay_fees_id' => $this->pay_fees_id,
            'payment_details.payment_mode' => $this->payment_mode,
            'payment_details.paid_amount' => $this->paid_amount,
            'payment_details.balance_amount' => $this->balance_amount,
            'payment_details.fee_collected_by' => $this->fee_collected_by,
            'payment_details.status' => $this->status,

            'payment_details.create_user_id' => $this->create_user_id,
            'payment_details.update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'paid_reference_number', $this->paid_reference_number])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'razorpay_order_id', $this->razorpay_order_id])
            ->andFilterWhere(['like', 'razorpay_payment_id', $this->razorpay_payment_id]);

        if (isset($this->created_on) && $this->created_on != '') {
            //you dont need the if function if yourse sure you have a not null date
            $date_explode = explode(" - ", $this->created_on);
            $date = trim($date_explode[0]);
            $date_up = trim($date_explode[1]);

            $date1 = str_replace('/', '-', $date);
            $date2 = str_replace('/', '-', $date_up);

            // var_dump($date1);exit;
            $query->andWhere(['between', 'DATE(payment_details.created_on)', $date1, $date2]);
        }
        if (isset($this->updated_on) && $this->updated_on != '') {

            $date_explode = explode(" - ", $this->updated_on);
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['between', 'payment_details.updated_on', $date1, $date2]);
        }


        //    var_dump($query->createCommand()->getRawSql());exit;

        return $dataProvider;
    }

    public function todaysTransactionSearch($params, $student_id = '', $status = '', $academic_year = '', $date = '')
    {

        $query = PaymentDetails::find()->joinWith(['payFees as pf']);
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);


        if (!empty($campus_id)) {
            $query->where(['payment_details.campus_id' => $campus_id]);
        }

        if (!empty($academic_year)) {
            $query->where(['pf.academic_year_id' => $academic_year]);
        }
        if (!empty($student_id)) {
            $query->andWhere(['payment_details.student_id' => $student_id]);
        }

        if (!empty($date)) {
            $query->andWhere(['payment_details.created_on' => $date]);
        }

        if (!empty($status)) {
            $query->andWhere(['payment_details.status' => $status]);
        } elseif ($status === 0) {
            $query->andWhere(['payment_details.status' => PaymentDetails::status_failed]);
        }

        $date1 = date('Y-m-d');

        // Get tomorrow's date
        $date2 = date('Y-m-d', strtotime('+1 day'));
        $query->andWhere(['between', 'DATE(payment_details.created_on)', $date1, $date2]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'payment_details.id' => $this->id,
            'payment_details.campus_id' => $this->campus_id,
            'payment_details.student_id' => $this->student_id,
            'payment_details.class_id' => $this->class_id,
            'payment_details.section_id' => $this->section_id,
            'payment_details.pay_fees_id' => $this->pay_fees_id,
            'payment_details.payment_mode' => $this->payment_mode,
            'payment_details.paid_amount' => $this->paid_amount,
            'payment_details.balance_amount' => $this->balance_amount,
            'payment_details.fee_collected_by' => $this->fee_collected_by,
            'payment_details.status' => $this->status,

            'payment_details.create_user_id' => $this->create_user_id,
            'payment_details.update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'paid_reference_number', $this->paid_reference_number])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'razorpay_order_id', $this->razorpay_order_id])
            ->andFilterWhere(['like', 'razorpay_payment_id', $this->razorpay_payment_id]);


        if (isset($this->created_on) && $this->created_on != '') {
            //you dont need the if function if yourse sure you have a not null date
            $date_explode = explode(" - ", $this->created_on);
            $date = trim($date_explode[0]);
            $date_up = trim($date_explode[1]);

            $date1 = str_replace('/', '-', $date);
            $date2 = str_replace('/', '-', $date_up);

            // var_dump($date1);exit;
            $query->andWhere(['between', 'DATE(payment_details.created_on)', $date1, $date2]);
        }
        if (isset($this->updated_on) && $this->updated_on != '') {

            $date_explode = explode(" - ", $this->updated_on);
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['between', 'payment_details.updated_on', $date1, $date2]);
        }


        //    var_dump($query->createCommand()->getRawSql());exit;

        return $dataProvider;
    }


    public function campusSubAdminSearchSearch($params, $student_id = '', $status = '')
    {
        $query = PaymentDetails::find();

        $query->where(['campus_id' => ((new User())->getCampusId())]);

        if (!empty($student_id)) {
            $query->andWhere(['student_id' => $student_id]);
        }
        if (!empty($status)) {
            $query->andWhere(['status' => $status]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Remove the exact match condition for 'created_on'
        $query->andFilterWhere([
            'id' => $this->id,
            'campus_id' => $this->campus_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'pay_fees_id' => $this->pay_fees_id,
            'payment_mode' => $this->payment_mode,
            'paid_amount' => $this->paid_amount,
            'balance_amount' => $this->balance_amount,
            'fee_collected_by' => $this->fee_collected_by,
            'status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'paid_reference_number', $this->paid_reference_number])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'razorpay_order_id', $this->razorpay_order_id])
            ->andFilterWhere(['like', 'razorpay_payment_id', $this->razorpay_payment_id]);

        // Handle date filtering for created_on and updated_on fields
        if (isset($this->created_on) && !empty($this->created_on)) {
            $date_explode = explode(" - ", $this->created_on);
            if (count($date_explode) === 2) {
                $date1 = date('Y-m-d', strtotime(trim($date_explode[0])));
                $date2 = date('Y-m-d', strtotime(trim($date_explode[1])));
                $query->andFilterWhere(['between', 'DATE(created_on)', $date1, $date2]);
            }
        }

        if (isset($this->updated_on) && !empty($this->updated_on)) {
            $date_explode = explode(" - ", $this->updated_on);
            if (count($date_explode) === 2) {
                $date1 = date('Y-m-d 00:00:00', strtotime(trim($date_explode[0])));
                $date2 = date('Y-m-d 23:59:59', strtotime(trim($date_explode[1])));
                $query->andFilterWhere(['between', 'updated_on', $date1, $date2]);
            }
        }

        return $dataProvider;
    }




    // public function todaysTransactionSearch($params, $student_id = '', $status = '')
    // {
    //     $query = PaymentDetails::find();

    //     $query->where(['campus_id' => ((new User())->getCampusId())]);

    //     if (!empty($student_id)) {
    //         $query->andWhere(['student_id' => $student_id]);
    //     }
    //     if (!empty($status)) {
    //         $query->andWhere(['status' => $status]);
    //     }
    //     $query->andWhere(['=', 'DATE(created_on)', date('Y-m-d')]);
    //     $dataProvider = new ActiveDataProvider([
    //         'query' => $query,
    //         'sort' => [
    //             'defaultOrder' => [
    //                 'created_on' => SORT_DESC,
    //             ],
    //         ],
    //     ]);

    //     $this->load($params);

    //     if (!$this->validate()) {
    //         return $dataProvider;
    //     }

    //     // Remove the exact match condition for 'created_on'
    //     $query->andFilterWhere([
    //         'id' => $this->id,
    //         'campus_id' => $this->campus_id,
    //         'student_id' => $this->student_id,
    //         'class_id' => $this->class_id,
    //         'section_id' => $this->section_id,
    //         'pay_fees_id' => $this->pay_fees_id,
    //         'payment_mode' => $this->payment_mode,
    //         'paid_amount' => $this->paid_amount,
    //         'balance_amount' => $this->balance_amount,
    //         'fee_collected_by' => $this->fee_collected_by,
    //         'status' => $this->status,
    //         'create_user_id' => $this->create_user_id,
    //         'update_user_id' => $this->update_user_id,
    //     ]);

    //     $query->andFilterWhere(['like', 'paid_reference_number', $this->paid_reference_number])
    //         ->andFilterWhere(['like', 'remarks', $this->remarks])
    //         ->andFilterWhere(['like', 'razorpay_order_id', $this->razorpay_order_id])
    //         ->andFilterWhere(['like', 'razorpay_payment_id', $this->razorpay_payment_id]);

    //     // Handle date filtering for created_on and updated_on fields
    //     if (isset($this->created_on) && !empty($this->created_on)) {
    //         $date_explode = explode(" - ", $this->created_on);
    //         if (count($date_explode) === 2) {
    //             $date1 = date('Y-m-d', strtotime(trim($date_explode[0])));
    //             $date2 = date('Y-m-d', strtotime(trim($date_explode[1])));
    //             $query->andFilterWhere(['between', 'DATE(created_on)', $date1, $date2]);
    //         }
    //     }

    //     if (isset($this->updated_on) && !empty($this->updated_on)) {
    //         $date_explode = explode(" - ", $this->updated_on);
    //         if (count($date_explode) === 2) {
    //             $date1 = date('Y-m-d 00:00:00', strtotime(trim($date_explode[0])));
    //             $date2 = date('Y-m-d 23:59:59', strtotime(trim($date_explode[1])));
    //             $query->andFilterWhere(['between', 'updated_on', $date1, $date2]);
    //         }
    //     }

    //     return $dataProvider;
    // }









    /**
     * Creates data provider instance with managersearch query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function managersearch($params)
    {
        $query = PaymentDetails::find()
            ->where(['city_id' => \Yii::$app->user->identity->city_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
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
            'pay_fees_id' => $this->pay_fees_id,
            'payment_mode' => $this->payment_mode,
            'paid_amount' => $this->paid_amount,
            'balance_amount' => $this->balance_amount,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'paid_reference_number', $this->paid_reference_number])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        if (isset($this->created_on) && $this->created_on != '') {

            //you dont need the if function if yourse sure you have a not null date
            $date_explode = explode(" - ", $this->created_on);
            //   var_dump($date_explode);exit;
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['between', 'created_on', $date1, $date2]);
            // var_dump($query->createCommand()->getRawSql());exit;
        }
        if (isset($this->updated_on) && $this->updated_on != '') {

            //you dont need the if function if yourse sure you have a not null date
            $date_explode = explode(" - ", $this->updated_on);
            //   var_dump($date_explode);exit;
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['between', 'updated_on', $date1, $date2]);
            //  var_dump($query->createCommand()->getRawSql());exit;
        }

        return $dataProvider;
    }
}
