<?php

namespace app\modules\exammanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\exammanagement\models\ExamSchedules;

/**
 * app\modules\exammanagement\models\search\ExamSchedulesSearch represents the model behind the search form about `app\modules\exammanagement\models\ExamSchedules`.
 */
 class ExamSchedulesSearch extends ExamSchedules
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'session_id', 'campus_id', 'exam_id', 'class_id', 'section_id', 'subject_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['max_marks', 'min_marks'], 'number'],
            [['exam_date', 'exam_duration', 'created_on', 'updated_on'], 'safe'],
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
        $query = ExamSchedules::find()->where(['status'=>ExamSchedules::STATUS_ACTIVE]);

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
            'session_id' => $this->session_id,
            'campus_id' => $this->campus_id,
            'exam_id' => $this->exam_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'max_marks' => $this->max_marks,
            'min_marks' => $this->min_marks,
            'exam_date' => $this->exam_date,
            'exam_duration' => $this->exam_duration,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = ExamSchedules::find()->where(['campus_id' => (new User())->getCampusId()]);

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
            'session_id' => $this->session_id,
            'campus_id' => $this->campus_id,
            'exam_id' => $this->exam_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'max_marks' => $this->max_marks,
            'min_marks' => $this->min_marks,
            'exam_date' => $this->exam_date,
            'exam_duration' => $this->exam_duration,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }



    
    public function institutesSearch($params)
    {
        $query = ExamSchedules::find()->where(['status'=>ExamSchedules::STATUS_ACTIVE]);;

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
            'session_id' => $this->session_id,
            'campus_id' => $this->campus_id,
            'exam_id' => $this->exam_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'max_marks' => $this->max_marks,
            'min_marks' => $this->min_marks,
            'exam_date' => $this->exam_date,
            'exam_duration' => $this->exam_duration,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = ExamSchedules::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])->andWhere(['status'=>ExamSchedules::STATUS_ACTIVE]);;

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
            'session_id' => $this->session_id,
            'campus_id' => $this->campus_id,
            'exam_id' => $this->exam_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'max_marks' => $this->max_marks,
            'min_marks' => $this->min_marks,
            'exam_date' => $this->exam_date,
            'exam_duration' => $this->exam_duration,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

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
        $query = ExamSchedules::find()
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
            'session_id' => $this->session_id,
            'campus_id' => $this->campus_id,
            'exam_id' => $this->exam_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'max_marks' => $this->max_marks,
            'min_marks' => $this->min_marks,
            'exam_date' => $this->exam_date,
            'exam_duration' => $this->exam_duration,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

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
