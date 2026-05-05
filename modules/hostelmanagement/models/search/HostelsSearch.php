<?php


namespace app\modules\hostelmanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hostelmanagement\models\Hostels;

/**
 * app\modules\hostelmanagement\models\search\HostelsSearch represents the model behind the search form about `app\modules\hostelmanagement\models\Hostels`.
 */
class HostelsSearch extends Hostels
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id',   'status', 'create_user_id', 'update_user_id',], 'integer'],
            [['type_id', 'name', 'email', 'name_of_the_hostel', 'area', 'pincode', 'address', 'coordinates', 'created_on', 'updated_on'], 'safe'],
            [['lat', 'lng'], 'number'],
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

        $query = Hostels::find()->where(['status' => Hostels::STATUS_ACTIVE])->orWhere(['status' => Hostels::STATUS_INACTIVE])->andWhere(['campus_id' => $campusId]);

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
            'type_id' => $this->type_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'image_file', $this->image_file])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name_of_the_hostel', $this->name_of_the_hostel])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'address', $this->address])


            ->andFilterWhere(['like', 'coordinates', $this->coordinates]);

        return $dataProvider;
    }




    public function ChiefWardenSearch($params)
    {
        $query = Hostels::find()
            ->where(['status' => Hostels::STATUS_ACTIVE])
            ->orWhere(['status' => Hostels::STATUS_INACTIVE])
            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'type_id' => $this->type_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'image_file', $this->image_file])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name_of_the_hostel', $this->name_of_the_hostel])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'address', $this->address])

            ->andFilterWhere(['like', 'coordinates', $this->coordinates]);

        return $dataProvider;
    }
    public function SubAdminSearch($params)
    {
        $query = Hostels::find()
            ->where(['status' => Hostels::STATUS_ACTIVE])
            ->orWhere(['status' => Hostels::STATUS_INACTIVE])
            ->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'type_id' => $this->type_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'image_file', $this->image_file])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name_of_the_hostel', $this->name_of_the_hostel])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'address', $this->address])

            ->andFilterWhere(['like', 'coordinates', $this->coordinates]);

        return $dataProvider;
    }



    public function AccountantSearch($params)
    {
        $query = Hostels::find()->where(['status' => Hostels::STATUS_ACTIVE])->orWhere(['status' => Hostels::STATUS_INACTIVE]);

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
            'type_id' => $this->type_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'image_file', $this->image_file])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name_of_the_hostel', $this->name_of_the_hostel])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'coordinates', $this->coordinates]);

        return $dataProvider;
    }




    public function DocumentationDepartmentSearch($params)
    {
        $query = Hostels::find()->where(['status' => Hostels::STATUS_ACTIVE])->orWhere(['status' => Hostels::STATUS_INACTIVE]);

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
            'type_id' => $this->type_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'image_file', $this->image_file])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name_of_the_hostel', $this->name_of_the_hostel])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'coordinates', $this->coordinates]);

        return $dataProvider;
    }



    public function LmLeadsManagementSearch($params)
    {
        $query = Hostels::find()->where(['status' => Hostels::STATUS_ACTIVE])->orWhere(['status' => Hostels::STATUS_INACTIVE]);

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
            'type_id' => $this->type_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'image_file', $this->image_file])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name_of_the_hostel', $this->name_of_the_hostel])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'coordinates', $this->coordinates]);

        return $dataProvider;
    }



    public function DeliveryAdminSearch($params)
    {
        $query = Hostels::find()->where(['status' => Hostels::STATUS_ACTIVE])->orWhere(['status' => Hostels::STATUS_INACTIVE]);

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
            'type_id' => $this->type_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'image_file', $this->image_file])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name_of_the_hostel', $this->name_of_the_hostel])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'coordinates', $this->coordinates]);

        return $dataProvider;
    }
}
