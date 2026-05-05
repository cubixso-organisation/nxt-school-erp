<?php

namespace app\modules\librarymanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\librarymanagement\models\IssueBooks;
use app\modules\librarymanagement\models\LibraryMembers;

/**
 * app\modules\librarymanagement\models\search\IssueBooksSearch represents the model behind the search form about `app\modules\librarymanagement\models\IssueBooks`.
 */
class IssueBooksSearch extends IssueBooks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'book_id', 'status', 'author', 'subject_code', 'serial_no', 'created_user_id', 'updated_user_id'], 'integer'],
            [['library_id', 'due_date', 'note', 'created_on', 'updated_on','library_member_id'], 'safe'],
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



        $query = IssueBooks::find();



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
            'book_id' => $this->book_id,
            'status' => $this->status,
            'author' => $this->author,
            'subject_code' => $this->subject_code,
            'serial_no' => $this->serial_no,
            
            'due_date' => $this->due_date,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
            
        ]);
        if (isset($this->library_member_id) && !empty($this->library_member_id)) {
            $query->andFilterWhere(['library_member_id' => $this->libraryMember->name]);
        }
        $query->andFilterWhere(['like', 'library_id', $this->library_id])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $libraryMembers = LibraryMembers::find()->where(['campus_id' => $campusId])->all();

        // Collect library member IDs
        $libraryMemberIds = array_map(function ($libraryMember) {
            return $libraryMember->id;
        }, $libraryMembers);

        // Find issued books for the collected library member IDs
        $query = IssueBooks::find()->where(['library_member_id' => $libraryMemberIds]);
        // $query->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'book_id' => $this->book_id,
            'status' => $this->status,
            'author' => $this->author,
            'subject_code' => $this->subject_code,
            'serial_no' => $this->serial_no,
            
            'due_date' => $this->due_date,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);
        if (isset($this->library_member_id) && !empty($this->library_member_id)) {
            $query->andFilterWhere(['library_member_id' => $this->libraryMember->name]);
        }
        $query->andFilterWhere(['like', 'library_id', $this->library_id])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

        return $dataProvider;
    }




    public function institutesSearch($params)
    {
        $query = IssueBooks::find();

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
            'book_id' => $this->book_id,
            'status' => $this->status,
            'author' => $this->author,
            'subject_code' => $this->subject_code,
            'serial_no' => $this->serial_no,
            
            'due_date' => $this->due_date,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);
        if (isset($this->library_member_id) && !empty($this->library_member_id)) {
            $query->andFilterWhere(['library_member_id' => $this->libraryMember->name]);
        }
        $query->andFilterWhere(['like', 'library_id', $this->library_id])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $libraryMembers = LibraryMembers::find()->where(['campus_id' => $campusId])->all();

        // Collect library member IDs
        $libraryMemberIds = array_map(function ($libraryMember) {
            return $libraryMember->id;
        }, $libraryMembers);

        // Find issued books for the collected library member IDs
        $query = IssueBooks::find()->where(['library_member_id' => $libraryMemberIds]);
        // $query->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'book_id' => $this->book_id,
            'status' => $this->status,
            'author' => $this->author,
            'subject_code' => $this->subject_code,
            'serial_no' => $this->serial_no,
            
            'due_date' => $this->due_date,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);
        if (isset($this->library_member_id) && !empty($this->library_member_id)) {
            $query->andFilterWhere(['library_member_id' => $this->libraryMember->name]);
        }
        $query->andFilterWhere(['like', 'library_id', $this->library_id])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

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
        $query = IssueBooks::find()
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
            'book_id' => $this->book_id,
            'status' => $this->status,
            'author' => $this->author,
            'subject_code' => $this->subject_code,
            'serial_no' => $this->serial_no,
            'library_member_id' => $this->libraryMember->name,
            'due_date' => $this->due_date,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'library_id', $this->library_id])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

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
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $libraryMembers = LibraryMembers::find()->where(['campus_id' => $campusId])->all();

        // Collect library member IDs
        $libraryMemberIds = array_map(function ($libraryMember) {
            return $libraryMember->id;
        }, $libraryMembers);

        // Find issued books for the collected library member IDs
        $query = IssueBooks::find()->where(['library_member_id' => $libraryMemberIds]);
        // $query->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'book_id' => $this->book_id,
            'status' => $this->status,
            'author' => $this->author,
            'subject_code' => $this->subject_code,
            'serial_no' => $this->serial_no,
            'library_member_id' => $this->libraryMember->name,
            'due_date' => $this->due_date,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'library_id', $this->library_id])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

        return $dataProvider;
    }
}
