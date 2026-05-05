<?php


namespace app\modules\leavemanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\leavemanagement\models\StaffLeaveApplied;

/**
 * app\modules\leavemanagement\models\search\StaffLeaveAppliedSearch represents the model behind the search form about `app\modules\leavemanagement\models\StaffLeaveApplied`.
 */
class StaffLeaveAppliedSearch extends StaffLeaveApplied
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'user_id', 'leave_type_id', 'no_of_days', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['leave_reason', 'from_date', 'to_date', 'document_uploaded', 'user_role', 'created_on', 'updated_on'], 'safe'],
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
        $campusId = (new User())->getCampusId();
        $query = StaffLeaveApplied::find()
            ->where(['campus_id' => $campusId]);


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
            'campus_id' => $this->campus_id,
            'user_id' => $this->user_id,
            'leave_type_id' => $this->leave_type_id,
            'no_of_days' => $this->no_of_days,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'leave_reason', $this->leave_reason])
            ->andFilterWhere(['like', 'document_uploaded', $this->document_uploaded])
            ->andFilterWhere(['like', 'user_role', $this->user_role]);

        return $dataProvider;
    }




    public function SubAdminSearch($params)
    {
        $campusId = (new User())->getCampusId();
        $query = StaffLeaveApplied::find()
            ->where(['campus_id' => $campusId]);

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
            'campus_id' => $this->campus_id,
            'user_id' => $this->user_id,
            'leave_type_id' => $this->leave_type_id,
            'no_of_days' => $this->no_of_days,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'leave_reason', $this->leave_reason])
            ->andFilterWhere(['like', 'document_uploaded', $this->document_uploaded])
            ->andFilterWhere(['like', 'user_role', $this->user_role]);

        return $dataProvider;
    }



    public function LmLeadsManagementSearch($params)
    {
        $query = StaffLeaveApplied::find()->where(['status' => StaffLeaveApplied::STATUS_ACTIVE])->orWhere(['status' => StaffLeaveApplied::STATUS_INACTIVE]);

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
            'campus_id' => $this->campus_id,
            'user_id' => $this->user_id,
            'leave_type_id' => $this->leave_type_id,
            'no_of_days' => $this->no_of_days,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'leave_reason', $this->leave_reason])
            ->andFilterWhere(['like', 'document_uploaded', $this->document_uploaded])
            ->andFilterWhere(['like', 'user_role', $this->user_role]);

        return $dataProvider;
    }



    public function DeliveryAdminSearch($params)
    {
        $query = StaffLeaveApplied::find()->where(['status' => StaffLeaveApplied::STATUS_ACTIVE])->orWhere(['status' => StaffLeaveApplied::STATUS_INACTIVE]);

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
            'campus_id' => $this->campus_id,
            'user_id' => $this->user_id,
            'leave_type_id' => $this->leave_type_id,
            'no_of_days' => $this->no_of_days,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'leave_reason', $this->leave_reason])
            ->andFilterWhere(['like', 'document_uploaded', $this->document_uploaded])
            ->andFilterWhere(['like', 'user_role', $this->user_role]);

        return $dataProvider;
    }
}
