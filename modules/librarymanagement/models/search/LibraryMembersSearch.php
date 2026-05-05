<?php

namespace app\modules\librarymanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\librarymanagement\models\LibraryMembers;

/**
 * app\modules\librarymanagement\models\search\LibraryMembersSearch represents the model behind the search form about `app\modules\librarymanagement\models\LibraryMembers`.
 */
class LibraryMembersSearch extends LibraryMembers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['member_id', 'library_card_no', 'admission_no', 'name', 'member_type', 'phone', 'created_on', 'updated_on'], 'safe'],
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
        $query = LibraryMembers::find();

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'library_card_no', $this->library_card_no])
            ->andFilterWhere(['like', 'admission_no', $this->admission_no])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'member_type', $this->member_type])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = LibraryMembers::find();
        $query->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'library_card_no', $this->library_card_no])
            ->andFilterWhere(['like', 'admission_no', $this->admission_no])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'member_type', $this->member_type])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }




    public function institutesSearch($params)
    {
        $query = LibraryMembers::find();

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'library_card_no', $this->library_card_no])
            ->andFilterWhere(['like', 'admission_no', $this->admission_no])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'member_type', $this->member_type])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = LibraryMembers::find();
        $query->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'library_card_no', $this->library_card_no])
            ->andFilterWhere(['like', 'admission_no', $this->admission_no])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'member_type', $this->member_type])
            ->andFilterWhere(['like', 'phone', $this->phone]);

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
        $query = LibraryMembers::find()->where(['city_id' => \Yii::$app->user->identity->city_id]);

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'library_card_no', $this->library_card_no])
            ->andFilterWhere(['like', 'admission_no', $this->admission_no])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'member_type', $this->member_type])
            ->andFilterWhere(['like', 'phone', $this->phone]);

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
        $query = LibraryMembers::find();
        $query->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'library_card_no', $this->library_card_no])
            ->andFilterWhere(['like', 'admission_no', $this->admission_no])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'member_type', $this->member_type])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
