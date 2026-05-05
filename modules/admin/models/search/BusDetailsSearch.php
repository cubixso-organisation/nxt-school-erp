<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\BusDetails;

/**
 * app\modules\admin\models\search\BusDetailsSearch represents the model behind the search form about `app\modules\admin\models\BusDetails`.
 */
 class BusDetailsSearch extends BusDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'route_no', 'type', 'status', 'status_direction', 'current_stop', 'next_stop', 'current_status', 'create_user_id', 'update_user_id'], 'integer'],
            [['title', 'vehicle_number', 'start_point', 'end_point', 'start_point_coordinates', 'end_point_coordinates', 'session_key', 'route_details', 'created_on', 'updated_on'], 'safe'],
            [['start_point_lat', 'start_point_lng', 'end_point_lat', 'end_point_lng'], 'number'],
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
    public function search($params,$campus_id='')
    {
        $query = BusDetails::find();


        
        if(!empty($campus_id)){
            $query->where(['campus_id'=>$campus_id]);
        }

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
            'route_no' => $this->route_no,
            'start_point_lat' => $this->start_point_lat,
            'start_point_lng' => $this->start_point_lng,
            'end_point_lat' => $this->end_point_lat,
            'end_point_lng' => $this->end_point_lng,
            'type' => $this->type,
            'status' => $this->status,
            'status_direction' => $this->status_direction,
            'current_stop' => $this->current_stop,
            'next_stop' => $this->next_stop,
            'current_status' => $this->current_status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'vehicle_number', $this->vehicle_number])
            ->andFilterWhere(['like', 'start_point', $this->start_point])
            ->andFilterWhere(['like', 'end_point', $this->end_point])
            ->andFilterWhere(['like', 'start_point_coordinates', $this->start_point_coordinates])
            ->andFilterWhere(['like', 'end_point_coordinates', $this->end_point_coordinates])
            ->andFilterWhere(['like', 'session_key', $this->session_key])
            ->andFilterWhere(['like', 'route_details', $this->route_details]);

        return $dataProvider;
    }
}
