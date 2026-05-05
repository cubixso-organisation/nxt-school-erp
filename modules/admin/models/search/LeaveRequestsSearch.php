<?php

namespace app\modules\admin\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\LeaveRequests;

/**
 * app\modules\admin\models\search\LeaveRequestsSearch represents the model behind the search form about `app\modules\admin\models\LeaveRequests`.
 */
class LeaveRequestsSearch extends LeaveRequests
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'student_id', 'leave_type_id', 'class_teacher_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['from_date', 'to_date', 'leave_reason', 'document', 'created_on', 'updated_on'], 'safe'],
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
        $query = LeaveRequests::find();

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
            'student_id' => $this->student_id,
            'leave_type_id' => $this->leave_type_id,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'class_teacher_id' => $this->class_teacher_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'leave_reason', $this->leave_reason])
            ->andFilterWhere(['like', 'document', $this->document]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = LeaveRequests::find()->joinWith(['leaveType as lt']);
        $query->where(['lt.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'student_id' => $this->student_id,
            'leave_type_id' => $this->leave_type_id,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'class_teacher_id' => $this->class_teacher_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'leave_reason', $this->leave_reason])
            ->andFilterWhere(['like', 'document', $this->document]);

        return $dataProvider;
    }




    public function institutesSearch($params)
    {
        $query = LeaveRequests::find();

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
            'student_id' => $this->student_id,
            'leave_type_id' => $this->leave_type_id,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'class_teacher_id' => $this->class_teacher_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'leave_reason', $this->leave_reason])
            ->andFilterWhere(['like', 'document', $this->document]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = LeaveRequests::find();

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
            'student_id' => $this->student_id,
            'leave_type_id' => $this->leave_type_id,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'class_teacher_id' => $this->class_teacher_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'leave_reason', $this->leave_reason])
            ->andFilterWhere(['like', 'document', $this->document]);

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
        $query = LeaveRequests::find()
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
            'student_id' => $this->student_id,
            'leave_type_id' => $this->leave_type_id,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'class_teacher_id' => $this->class_teacher_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'leave_reason', $this->leave_reason])
            ->andFilterWhere(['like', 'document', $this->document]);

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
