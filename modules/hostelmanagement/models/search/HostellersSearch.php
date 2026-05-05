<?php


namespace app\modules\hostelmanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hostelmanagement\models\Hostellers;

/**
 * app\modules\hostelmanagement\models\search\HostellersSearch represents the model behind the search form about `app\modules\hostelmanagement\models\Hostellers`.
 */
class HostellersSearch extends Hostellers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'student_id', 'campus_id', 'hostel_id', 'sty_type', 'room_id', 'is_all_items_checked', 'is_balance_amount_paid', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['joining_date', 'bill_date', 'next_bill_date', 'address', 'aadhar_number', 'photo', 'aadhar_front', 'aadhar_back', 'application_form_file', 'leave_of_date', 'leave_month', 'created_on', 'updated_on'], 'safe'],
            [['advance_payment', 'fees'], 'number'],
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
        $query = Hostellers::find()->where(['status' => Hostellers::STATUS_ACTIVE])->orWhere(['status' => Hostellers::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'student_id' => $this->student_id,
            'campus_id' => $this->campus_id,
            'hostel_id' => $this->hostel_id,
            'joining_date' => $this->joining_date,
            'bill_date' => $this->bill_date,
            'next_bill_date' => $this->next_bill_date,
            'sty_type' => $this->sty_type,
            'advance_payment' => $this->advance_payment,
            'fees' => $this->fees,
            'room_id' => $this->room_id,
            'leave_of_date' => $this->leave_of_date,
            'is_all_items_checked' => $this->is_all_items_checked,
            'is_balance_amount_paid' => $this->is_balance_amount_paid,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'aadhar_number', $this->aadhar_number])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'aadhar_front', $this->aadhar_front])
            ->andFilterWhere(['like', 'aadhar_back', $this->aadhar_back])
            ->andFilterWhere(['like', 'application_form_file', $this->application_form_file])
            ->andFilterWhere(['like', 'leave_month', $this->leave_month]);

        return $dataProvider;
    }





    public function ChiefWardenSearch($params)
    {
        $query = Hostellers::find()->Where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'student_id' => $this->student_id,
            'campus_id' => $this->campus_id,
            'hostel_id' => $this->hostel_id,
            'joining_date' => $this->joining_date,
            'bill_date' => $this->bill_date,
            'next_bill_date' => $this->next_bill_date,
            'sty_type' => $this->sty_type,
            'advance_payment' => $this->advance_payment,
            'fees' => $this->fees,
            'room_id' => $this->room_id,
            'leave_of_date' => $this->leave_of_date,
            'is_all_items_checked' => $this->is_all_items_checked,
            'is_balance_amount_paid' => $this->is_balance_amount_paid,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'aadhar_number', $this->aadhar_number])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'aadhar_front', $this->aadhar_front])
            ->andFilterWhere(['like', 'aadhar_back', $this->aadhar_back])
            ->andFilterWhere(['like', 'application_form_file', $this->application_form_file])
            ->andFilterWhere(['like', 'leave_month', $this->leave_month]);

        return $dataProvider;
    }
    public function SubAdminSearch($params)
    {

        $query = Hostellers::find()->Where(['campus_id' => (new User)->getCampusesByUser(Yii::$app->user->identity->id)]);;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'student_id' => $this->student_id,
            'campus_id' => $this->campus_id,
            'hostel_id' => $this->hostel_id,
            'joining_date' => $this->joining_date,
            'bill_date' => $this->bill_date,
            'next_bill_date' => $this->next_bill_date,
            'sty_type' => $this->sty_type,
            'advance_payment' => $this->advance_payment,
            'fees' => $this->fees,
            'room_id' => $this->room_id,
            'leave_of_date' => $this->leave_of_date,
            'is_all_items_checked' => $this->is_all_items_checked,
            'is_balance_amount_paid' => $this->is_balance_amount_paid,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'aadhar_number', $this->aadhar_number])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'aadhar_front', $this->aadhar_front])
            ->andFilterWhere(['like', 'aadhar_back', $this->aadhar_back])
            ->andFilterWhere(['like', 'application_form_file', $this->application_form_file])
            ->andFilterWhere(['like', 'leave_month', $this->leave_month]);

        return $dataProvider;
    }



    public function AccountantSearch($params)
    {
        $query = Hostellers::find()->where(['status' => Hostellers::STATUS_ACTIVE])->orWhere(['status' => Hostellers::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'student_id' => $this->student_id,
            'campus_id' => $this->campus_id,
            'hostel_id' => $this->hostel_id,
            'joining_date' => $this->joining_date,
            'bill_date' => $this->bill_date,
            'next_bill_date' => $this->next_bill_date,
            'sty_type' => $this->sty_type,
            'advance_payment' => $this->advance_payment,
            'fees' => $this->fees,
            'room_id' => $this->room_id,
            'leave_of_date' => $this->leave_of_date,
            'is_all_items_checked' => $this->is_all_items_checked,
            'is_balance_amount_paid' => $this->is_balance_amount_paid,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'aadhar_number', $this->aadhar_number])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'aadhar_front', $this->aadhar_front])
            ->andFilterWhere(['like', 'aadhar_back', $this->aadhar_back])
            ->andFilterWhere(['like', 'application_form_file', $this->application_form_file])
            ->andFilterWhere(['like', 'leave_month', $this->leave_month]);

        return $dataProvider;
    }




    public function DocumentationDepartmentSearch($params)
    {
        $query = Hostellers::find()->where(['status' => Hostellers::STATUS_ACTIVE])->orWhere(['status' => Hostellers::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'student_id' => $this->student_id,
            'campus_id' => $this->campus_id,
            'hostel_id' => $this->hostel_id,
            'joining_date' => $this->joining_date,
            'bill_date' => $this->bill_date,
            'next_bill_date' => $this->next_bill_date,
            'sty_type' => $this->sty_type,
            'advance_payment' => $this->advance_payment,
            'fees' => $this->fees,
            'room_id' => $this->room_id,
            'leave_of_date' => $this->leave_of_date,
            'is_all_items_checked' => $this->is_all_items_checked,
            'is_balance_amount_paid' => $this->is_balance_amount_paid,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'aadhar_number', $this->aadhar_number])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'aadhar_front', $this->aadhar_front])
            ->andFilterWhere(['like', 'aadhar_back', $this->aadhar_back])
            ->andFilterWhere(['like', 'application_form_file', $this->application_form_file])
            ->andFilterWhere(['like', 'leave_month', $this->leave_month]);

        return $dataProvider;
    }



    public function LmLeadsManagementSearch($params)
    {
        $query = Hostellers::find()->where(['status' => Hostellers::STATUS_ACTIVE])->orWhere(['status' => Hostellers::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'student_id' => $this->student_id,
            'campus_id' => $this->campus_id,
            'hostel_id' => $this->hostel_id,
            'joining_date' => $this->joining_date,
            'bill_date' => $this->bill_date,
            'next_bill_date' => $this->next_bill_date,
            'sty_type' => $this->sty_type,
            'advance_payment' => $this->advance_payment,
            'fees' => $this->fees,
            'room_id' => $this->room_id,
            'leave_of_date' => $this->leave_of_date,
            'is_all_items_checked' => $this->is_all_items_checked,
            'is_balance_amount_paid' => $this->is_balance_amount_paid,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'aadhar_number', $this->aadhar_number])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'aadhar_front', $this->aadhar_front])
            ->andFilterWhere(['like', 'aadhar_back', $this->aadhar_back])
            ->andFilterWhere(['like', 'application_form_file', $this->application_form_file])
            ->andFilterWhere(['like', 'leave_month', $this->leave_month]);

        return $dataProvider;
    }



    public function DeliveryAdminSearch($params)
    {
        $query = Hostellers::find()->where(['status' => Hostellers::STATUS_ACTIVE])->orWhere(['status' => Hostellers::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'student_id' => $this->student_id,
            'campus_id' => $this->campus_id,
            'hostel_id' => $this->hostel_id,
            'joining_date' => $this->joining_date,
            'bill_date' => $this->bill_date,
            'next_bill_date' => $this->next_bill_date,
            'sty_type' => $this->sty_type,
            'advance_payment' => $this->advance_payment,
            'fees' => $this->fees,
            'room_id' => $this->room_id,
            'leave_of_date' => $this->leave_of_date,
            'is_all_items_checked' => $this->is_all_items_checked,
            'is_balance_amount_paid' => $this->is_balance_amount_paid,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'aadhar_number', $this->aadhar_number])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'aadhar_front', $this->aadhar_front])
            ->andFilterWhere(['like', 'aadhar_back', $this->aadhar_back])
            ->andFilterWhere(['like', 'application_form_file', $this->application_form_file])
            ->andFilterWhere(['like', 'leave_month', $this->leave_month]);

        return $dataProvider;
    }
}
