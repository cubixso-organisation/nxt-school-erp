<?php

namespace app\modules\librarymanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\librarymanagement\models\LibraryBooks;

/**
 * app\modules\librarymanagement\models\search\LibraryBooksSearch represents the model behind the search form about `app\modules\librarymanagement\models\LibraryBooks`.
 */
class LibraryBooksSearch extends LibraryBooks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rack_number', 'qty', 'available', 'campus_id', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['book_title', 'description', 'book_number', 'isbn_number', 'publisher', 'author', 'subject', 'created_on', 'updated_on'], 'safe'],
            [['book_price'], 'number'],
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
        $query = LibraryBooks::find();

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
            'rack_number' => $this->rack_number,
            'qty' => $this->qty,
            'available' => $this->available,
            'book_price' => $this->book_price,
            'campus_id' => $this->campus_id,
            'status' => $this->status,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'book_title', $this->book_title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'book_number', $this->book_number])
            ->andFilterWhere(['like', 'isbn_number', $this->isbn_number])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'created_on', $this->created_on])
            ->andFilterWhere(['like', 'updated_on', $this->updated_on]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = LibraryBooks::find();
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
            'rack_number' => $this->rack_number,
            'qty' => $this->qty,
            'available' => $this->available,
            'book_price' => $this->book_price,
            'campus_id' => $this->campus_id,
            'status' => $this->status,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'book_title', $this->book_title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'book_number', $this->book_number])
            ->andFilterWhere(['like', 'isbn_number', $this->isbn_number])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'created_on', $this->created_on])
            ->andFilterWhere(['like', 'updated_on', $this->updated_on]);

        return $dataProvider;
    }




    public function institutesSearch($params)
    {
        $query = LibraryBooks::find();

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
            'rack_number' => $this->rack_number,
            'qty' => $this->qty,
            'available' => $this->available,
            'book_price' => $this->book_price,
            'campus_id' => $this->campus_id,
            'status' => $this->status,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'book_title', $this->book_title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'book_number', $this->book_number])
            ->andFilterWhere(['like', 'isbn_number', $this->isbn_number])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'created_on', $this->created_on])
            ->andFilterWhere(['like', 'updated_on', $this->updated_on]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = LibraryBooks::find();
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
            'rack_number' => $this->rack_number,
            'qty' => $this->qty,
            'available' => $this->available,
            'book_price' => $this->book_price,
            'campus_id' => $this->campus_id,
            'status' => $this->status,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'book_title', $this->book_title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'book_number', $this->book_number])
            ->andFilterWhere(['like', 'isbn_number', $this->isbn_number])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'created_on', $this->created_on])
            ->andFilterWhere(['like', 'updated_on', $this->updated_on]);

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
        $query = LibraryBooks::find()
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
            'rack_number' => $this->rack_number,
            'qty' => $this->qty,
            'available' => $this->available,
            'book_price' => $this->book_price,
            'campus_id' => $this->campus_id,
            'status' => $this->status,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'book_title', $this->book_title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'book_number', $this->book_number])
            ->andFilterWhere(['like', 'isbn_number', $this->isbn_number])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'created_on', $this->created_on])
            ->andFilterWhere(['like', 'updated_on', $this->updated_on]);

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
        $query = LibraryBooks::find();
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
            'rack_number' => $this->rack_number,
            'qty' => $this->qty,
            'available' => $this->available,
            'book_price' => $this->book_price,
            'campus_id' => $this->campus_id,
            'status' => $this->status,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'book_title', $this->book_title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'book_number', $this->book_number])
            ->andFilterWhere(['like', 'isbn_number', $this->isbn_number])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'created_on', $this->created_on])
            ->andFilterWhere(['like', 'updated_on', $this->updated_on]);

        return $dataProvider;
    }
}
