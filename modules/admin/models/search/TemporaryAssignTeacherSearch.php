<?php

namespace app\modules\admin\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TemporaryAssignTeacher;

/**
 * app\modules\admin\models\search\TemporaryAssignTeacherSearch represents the model behind the search form about `app\modules\admin\models\TemporaryAssignTeacher`.
 */
class TemporaryAssignTeacherSearch extends TemporaryAssignTeacher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'teacher_detail_id', 'teacher_timetable_id', 'date', 'day_id', 'period', 'class_id', 'section_id', 'subject_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['time_from', 'time_to', 'created_on', 'updated_on'], 'safe'],
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
        $query = TemporaryAssignTeacher::find();

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
            'teacher_detail_id' => $this->teacher_detail_id,
            'teacher_timetable_id' => $this->teacher_timetable_id,
            'date' => $this->date,
            'day_id' => $this->day_id,
            'period' => $this->period,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $campuses = User::getCampusesByUser(Yii::$app->user->identity->id);
        $query = TemporaryAssignTeacher::find()->where(['campus_id'=> $campuses]);

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
            'teacher_detail_id' => $this->teacher_detail_id,
            'teacher_timetable_id' => $this->teacher_timetable_id,
            'date' => $this->date,
            'day_id' => $this->day_id,
            'period' => $this->period,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to]);

        // Additional query
        $currentDate = date('Y-m-d'); // Assuming $currentDate is the current date
        $campuses = User::getCampusesByUser(Yii::$app->user->identity->id);
        if (!empty($campuses)) {
            $query->andWhere(['in', 'campus_id', $campuses]);
        }
        $query->andWhere(['date' => $currentDate])
            ->andWhere(['OR', ['replaced_teacher_detail_id' => null], ['replaced_teacher_detail_id' => '']]);

        return $dataProvider;
    }




    public function institutesSearch($params)
    {
      
        $query = TemporaryAssignTeacher::find();

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
            'teacher_detail_id' => $this->teacher_detail_id,
            'teacher_timetable_id' => $this->teacher_timetable_id,
            'date' => $this->date,
            'day_id' => $this->day_id,
            'period' => $this->period,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $campuses = User::getCampusesByUser(Yii::$app->user->identity->id);
        $query = TemporaryAssignTeacher::find()->where(['campus_id'=> $campuses]);

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
            'teacher_detail_id' => $this->teacher_detail_id,
            'teacher_timetable_id' => $this->teacher_timetable_id,
            'date' => $this->date,
            'day_id' => $this->day_id,
            'period' => $this->period,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to]);

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
        $query = TemporaryAssignTeacher::find()
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
            'campus_id' => $this->campus_id,
            'teacher_detail_id' => $this->teacher_detail_id,
            'teacher_timetable_id' => $this->teacher_timetable_id,
            'date' => $this->date,
            'day_id' => $this->day_id,
            'period' => $this->period,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to]);

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
