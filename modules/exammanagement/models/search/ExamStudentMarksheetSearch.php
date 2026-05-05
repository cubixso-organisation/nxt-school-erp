<?php

namespace app\modules\exammanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\exammanagement\models\ExamStudentMarksheet;

/**
 * app\modules\exammanagement\models\search\ExamStudentMarksheetSearch represents the model behind the search form about `app\modules\exammanagement\models\ExamStudentMarksheet`.
 */
 class ExamStudentMarksheetSearch extends ExamStudentMarksheet
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'student_id', 'session_id', 'class_id', 'section_id', 'exam_id', 'marks_type', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['total_marks', 'total_percentage', 'total_cgpa'], 'number'],
            [['total_grade', 'marksheet_url', 'created_on', 'updated_on'], 'safe'],
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
        $query = ExamStudentMarksheet::find();

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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'session_id' => $this->session_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'exam_id' => $this->exam_id,
            'total_marks' => $this->total_marks,
            'total_percentage' => $this->total_percentage,
            'marks_type' => $this->marks_type,
            'total_cgpa' => $this->total_cgpa,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'total_grade', $this->total_grade])
            ->andFilterWhere(['like', 'marksheet_url', $this->marksheet_url]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = ExamStudentMarksheet::find()->where(['campus_id' => (new User())->getCampusId()])->andWhere(['status'=>ExamStudentMarksheet::STATUS_ACTIVE]);

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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'session_id' => $this->session_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'exam_id' => $this->exam_id,
            'total_marks' => $this->total_marks,
            'total_percentage' => $this->total_percentage,
            'marks_type' => $this->marks_type,
            'total_cgpa' => $this->total_cgpa,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'total_grade', $this->total_grade])
            ->andFilterWhere(['like', 'marksheet_url', $this->marksheet_url]);

        return $dataProvider;
    }



    
    public function institutesSearch($params)
    {
        $query = ExamStudentMarksheet::find()->andWhere(['status'=>ExamStudentMarksheet::STATUS_ACTIVE]);

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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'session_id' => $this->session_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'exam_id' => $this->exam_id,
            'total_marks' => $this->total_marks,
            'total_percentage' => $this->total_percentage,
            'marks_type' => $this->marks_type,
            'total_cgpa' => $this->total_cgpa,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'total_grade', $this->total_grade])
            ->andFilterWhere(['like', 'marksheet_url', $this->marksheet_url]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = ExamStudentMarksheet::find()->andWhere(['status'=>ExamStudentMarksheet::STATUS_ACTIVE])->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'session_id' => $this->session_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'exam_id' => $this->exam_id,
            'total_marks' => $this->total_marks,
            'total_percentage' => $this->total_percentage,
            'marks_type' => $this->marks_type,
            'total_cgpa' => $this->total_cgpa,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'total_grade', $this->total_grade])
            ->andFilterWhere(['like', 'marksheet_url', $this->marksheet_url]);

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
        $query = ExamStudentMarksheet::find()
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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'session_id' => $this->session_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'exam_id' => $this->exam_id,
            'total_marks' => $this->total_marks,
            'total_percentage' => $this->total_percentage,
            'marks_type' => $this->marks_type,
            'total_cgpa' => $this->total_cgpa,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'total_grade', $this->total_grade])
            ->andFilterWhere(['like', 'marksheet_url', $this->marksheet_url]);

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
