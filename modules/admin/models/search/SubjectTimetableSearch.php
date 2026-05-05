<?php

namespace app\modules\admin\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\SubjectTimetable;

/**
 * app\modules\admin\models\search\SubjectTimetableSearch represents the model behind the search form about `app\modules\admin\models\SubjectTimetable`.
 */
 class SubjectTimetableSearch extends SubjectTimetable
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'class_id', 'section_id', 'subject_group_subject_id', 'teacher_details_id', 'room_id', 'academic_year_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['day_id', 'time_from', 'time_to', 'start_time', 'end_time', 'created_on', 'updated_on'], 'safe'],
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
        $query = SubjectTimetable::find();

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
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_group_subject_id' => $this->subject_group_subject_id,
            'teacher_details_id' => $this->teacher_details_id,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room_id' => $this->room_id,
            'academic_year_id' => $this->academic_year_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'day_id', $this->day_id]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = SubjectTimetable::find();

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
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_group_subject_id' => $this->subject_group_subject_id,
            'teacher_details_id' => $this->teacher_details_id,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room_id' => $this->room_id,
            'academic_year_id' => $this->academic_year_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'day_id', $this->day_id]);

        return $dataProvider;
    }



    
    public function institutesSearch($params)
    {
        $query = SubjectTimetable::find();

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
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_group_subject_id' => $this->subject_group_subject_id,
            'teacher_details_id' => $this->teacher_details_id,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room_id' => $this->room_id,
            'academic_year_id' => $this->academic_year_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'day_id', $this->day_id]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $campuses = User::getCampusesByUser(Yii::$app->user->identity->id);
        $query = SubjectTimetable::find()->where(['campus_id'=> $campuses]);

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
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_group_subject_id' => $this->subject_group_subject_id,
            'teacher_details_id' => $this->teacher_details_id,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room_id' => $this->room_id,
            'academic_year_id' => $this->academic_year_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'day_id', $this->day_id]);

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
        $query = SubjectTimetable::find()
                     ->where(['city_id' => \Yii::$app->user->identity->city_id])
        ;

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
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_group_subject_id' => $this->subject_group_subject_id,
            'teacher_details_id' => $this->teacher_details_id,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room_id' => $this->room_id,
            'academic_year_id' => $this->academic_year_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'day_id', $this->day_id]);

        if(isset ($this->created_on)&&$this->created_on!=''){ 
           
           //you dont need the if function if yourse sure you have a not null date
            $date_explode=explode(" - ",$this->created_on);
         //   var_dump($date_explode);exit;
            $date1=trim($date_explode[0]);
           $date2=trim($date_explode[1]);
           $query->andFilterWhere(['between','created_on',$date1,$date2]);
          // var_dump($query->createCommand()->getRawSql());exit;
          }
       if(isset ($this->updated_on)&&$this->updated_on!=''){ 
      
           //you dont need the if function if yourse sure you have a not null date
            $date_explode=explode(" - ",$this->updated_on);
         //   var_dump($date_explode);exit;
            $date1=trim($date_explode[0]);
           $date2=trim($date_explode[1]);
           $query->andFilterWhere(['between','updated_on',$date1,$date2]);
          //  var_dump($query->createCommand()->getRawSql());exit;
          }

        return $dataProvider;
    }
}
