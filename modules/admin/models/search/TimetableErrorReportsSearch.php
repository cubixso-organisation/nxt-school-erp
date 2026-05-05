<?php

namespace app\modules\admin\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TimetableErrorReports;

/**
 * app\modules\admin\models\search\TimetableErrorReportsSearch represents the model behind the search form about `app\modules\admin\models\TimetableErrorReports`.
 */
 class TimetableErrorReportsSearch extends TimetableErrorReports
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'subject_timetable_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['class', 'room', 'section', 'subject', 'teacher', 'time_from', 'time_to', 'error_type', 'created_on', 'updated_on'], 'safe'],
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
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $query = TimetableErrorReports::find()->innerJoinWith('subjectTimetable as std')->where(['std.campus_id'=>$campusId]);

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
        
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'subject_timetable_id' => $this->subject_timetable_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'room', $this->room])
            ->andFilterWhere(['like', 'section', $this->section])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'teacher', $this->teacher])
            ->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to])
            ->andFilterWhere(['like', 'error_type', $this->error_type]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);

        $query = TimetableErrorReports::find()->innerJoinWith('subjectTimetable as std')->where(['std.campus_id'=>$campusId]);




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
            'subject_timetable_id' => $this->subject_timetable_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'room', $this->room])
            ->andFilterWhere(['like', 'section', $this->section])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'teacher', $this->teacher])
            ->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to])
            ->andFilterWhere(['like', 'error_type', $this->error_type]);

        return $dataProvider;
    }

 
 
    
    public function institutesSearch($params)
    {

        $campusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $query = TimetableErrorReports::find()->innerJoinWith('subjectTimetable as std')->where(['std.campus_id'=>$campusId]);
        
        $query = TimetableErrorReports::find()->innerJoinWith('subjectTimetable as std')->where(['campus_id'=>$campusId]);

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
            'subject_timetable_id' => $this->subject_timetable_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'room', $this->room])
            ->andFilterWhere(['like', 'section', $this->section])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'teacher', $this->teacher])
            ->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to])
            ->andFilterWhere(['like', 'error_type', $this->error_type]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = TimetableErrorReports::find();

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
            'subject_timetable_id' => $this->subject_timetable_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'room', $this->room])
            ->andFilterWhere(['like', 'section', $this->section])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'teacher', $this->teacher])
            ->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to])
            ->andFilterWhere(['like', 'error_type', $this->error_type]);

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
        $query = TimetableErrorReports::find()
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
            'subject_timetable_id' => $this->subject_timetable_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'room', $this->room])
            ->andFilterWhere(['like', 'section', $this->section])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'teacher', $this->teacher])
            ->andFilterWhere(['like', 'time_from', $this->time_from])
            ->andFilterWhere(['like', 'time_to', $this->time_to])
            ->andFilterWhere(['like', 'error_type', $this->error_type]);

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
