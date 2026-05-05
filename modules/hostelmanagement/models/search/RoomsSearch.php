<?php


namespace app\modules\hostelmanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hostelmanagement\models\Rooms;

/**
 * app\modules\hostelmanagement\models\search\RoomsSearch represents the model behind the search form about `app\modules\hostelmanagement\models\Rooms`.
 */
class RoomsSearch extends Rooms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hostel_id', 'no_of_beds', 'type', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['name_of_the_room', 'created_on', 'updated_on'], 'safe'],
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
        $query = Rooms::find()->where(['status' => Rooms::STATUS_ACTIVE])->orWhere(['status' => Rooms::STATUS_INACTIVE]);

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
            'hostel_id' => $this->hostel_id,
            'no_of_beds' => $this->no_of_beds,
            'type' => $this->type,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_room', $this->name_of_the_room]);

        return $dataProvider;
    }




    public function SubAdminSearch($params)
    {
        $query = Rooms::find()
            ->joinWith('hostel') // Assuming 'hostel' is the relation name in the Rooms model
            ->where(['rooms.status' => Rooms::STATUS_ACTIVE])
            ->orWhere(['rooms.status' => Rooms::STATUS_INACTIVE])
            ->andWhere(['hostels.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);


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
            'hostel_id' => $this->hostel_id,
            'no_of_beds' => $this->no_of_beds,
            'type' => $this->type,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_room', $this->name_of_the_room]);

        return $dataProvider;
    }
    public function ChiefWardenSearch($params)
    {
        $query = Rooms::find()
            ->joinWith('hostel') // Assuming 'hostel' is the relation name in the Rooms model
            ->where(['rooms.status' => Rooms::STATUS_ACTIVE])
            ->orWhere(['rooms.status' => Rooms::STATUS_INACTIVE])
            ->andWhere(['hostels.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);


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
            'hostel_id' => $this->hostel_id,
            'no_of_beds' => $this->no_of_beds,
            'type' => $this->type,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_room', $this->name_of_the_room]);

        return $dataProvider;
    }



    public function AccountantSearch($params)
    {
        $query = Rooms::find()->where(['status' => Rooms::STATUS_ACTIVE])->orWhere(['status' => Rooms::STATUS_INACTIVE]);

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
            'hostel_id' => $this->hostel_id,
            'no_of_beds' => $this->no_of_beds,
            'type' => $this->type,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_room', $this->name_of_the_room]);

        return $dataProvider;
    }




    public function DocumentationDepartmentSearch($params)
    {
        $query = Rooms::find()->where(['status' => Rooms::STATUS_ACTIVE])->orWhere(['status' => Rooms::STATUS_INACTIVE]);

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
            'hostel_id' => $this->hostel_id,
            'no_of_beds' => $this->no_of_beds,
            'type' => $this->type,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_room', $this->name_of_the_room]);

        return $dataProvider;
    }



    public function LmLeadsManagementSearch($params)
    {
        $query = Rooms::find()->where(['status' => Rooms::STATUS_ACTIVE])->orWhere(['status' => Rooms::STATUS_INACTIVE]);

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
            'hostel_id' => $this->hostel_id,
            'no_of_beds' => $this->no_of_beds,
            'type' => $this->type,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_room', $this->name_of_the_room]);

        return $dataProvider;
    }



    public function DeliveryAdminSearch($params)
    {
        $query = Rooms::find()->where(['status' => Rooms::STATUS_ACTIVE])->orWhere(['status' => Rooms::STATUS_INACTIVE]);

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
            'hostel_id' => $this->hostel_id,
            'no_of_beds' => $this->no_of_beds,
            'type' => $this->type,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_room', $this->name_of_the_room]);

        return $dataProvider;
    }
}
