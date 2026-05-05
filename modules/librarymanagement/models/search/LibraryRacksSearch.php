<?php

namespace app\modules\librarymanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\librarymanagement\models\LibraryRacks;

/**
 * app\modules\librarymanagement\models\search\LibraryRacksSearch represents the model behind the search form about `app\modules\librarymanagement\models\LibraryRacks`.
 */
class LibraryRacksSearch extends LibraryRacks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_user_id', 'updated_user_id'], 'integer'],
            [['rack_number', 'rack_location', 'created_on', 'updated_on'], 'safe'],
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
        $query = LibraryRacks::find();

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
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'rack_number', $this->rack_number])
            ->andFilterWhere(['like', 'rack_location', $this->rack_location]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = LibraryRacks::find();
        $query->where(['campus_id' => (new User())->getCampusId()]);

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
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'rack_number', $this->rack_number])
            ->andFilterWhere(['like', 'rack_location', $this->rack_location]);

        return $dataProvider;
    }




    public function institutesSearch($params)
    {
        $query = LibraryRacks::find();

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
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'rack_number', $this->rack_number])
            ->andFilterWhere(['like', 'rack_location', $this->rack_location]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = LibraryRacks::find();
        $query->joinWith(['libraryBooks' => function ($q) {
            $q->andWhere(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);
        }]);
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
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'rack_number', $this->rack_number])
            ->andFilterWhere(['like', 'rack_location', $this->rack_location]);

        return $dataProvider;
    }







    /**
     * Creates data provider instance with managersearch query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function managersearch($params)
    {
        $query = LibraryRacks::find()
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
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'rack_number', $this->rack_number])
            ->andFilterWhere(['like', 'rack_location', $this->rack_location]);

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

    public function librarainSearch($params)
    {
        $query = LibraryRacks::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);
        // $query->joinWith(['libraryBooks' => function ($q) {
        //     $q->andWhere(['library_books.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);
        // }]);
        // var_dump(User::getCampusesByUser(Yii::$app->user->identity->id));
        // exit;
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
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'rack_number', $this->rack_number])
            ->andFilterWhere(['like', 'rack_location', $this->rack_location]);

        return $dataProvider;
    }
}
