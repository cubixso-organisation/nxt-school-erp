<?php

namespace app\modules\admin\models\search;

use app\modules\admin\models\base\TeacherDetails as BaseTeacherDetails;
use app\modules\admin\models\Campus;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TeacherDetails;
use app\modules\admin\models\User;

/**
 * app\modules\admin\models\search\TeacherDetailsSearch represents the model behind the search form about `app\modules\admin\models\TeacherDetails`.
 */
 class TeacherDetailsSearch extends TeacherDetails
{
    /**
     * @inheritdoc 
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'campus_id', 'class_id', 'section_id', 'academic_year_id', 'gender', 'blood_group_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['name', 'profile_image', 'id_number','status', 'date_of_birth', 'father_name', 'contact_number', 'email', 'address', 'created_on', 'updated_on'], 'safe'],
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
        $query = TeacherDetails::find();

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
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'date_of_birth' => $this->date_of_birth,
            'academic_year_id' => $this->academic_year_id,
            'gender' => $this->gender,
            'blood_group_id' => $this->blood_group_id,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'status' => $this->status,

            
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'profile_image', $this->profile_image])
            ->andFilterWhere(['like', 'id_number', $this->id_number])
            ->andFilterWhere(['like', 'father_name', $this->father_name])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
    public function camoussubsearch($params)
    {
        $query = TeacherDetails::find()->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'date_of_birth' => $this->date_of_birth,
            'academic_year_id' => $this->academic_year_id,
            'gender' => $this->gender,
            'blood_group_id' => $this->blood_group_id,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'status' => $this->status,

            
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'profile_image', $this->profile_image])
            ->andFilterWhere(['like', 'id_number', $this->id_number])
            ->andFilterWhere(['like', 'father_name', $this->father_name])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }



    public function CampusSearch($params)
    {
        $query = TeacherDetails::find();
        $query->where(['campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'user_id' => $this->user_id,
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'date_of_birth' => $this->date_of_birth,
            'academic_year_id' => $this->academic_year_id,
            'gender' => $this->gender,
            'blood_group_id' => $this->blood_group_id,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'status' => $this->status,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'profile_image', $this->profile_image])
            ->andFilterWhere(['like', 'id_number', $this->id_number])
            ->andFilterWhere(['like', 'father_name', $this->father_name])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }

    public function searchNotMarkedTeachers($params)
    {
        $currentDate = date('Y-m-d'); // Get the current date
    
        // Fetch teachers who have no attendance record for the current date
        $query = TeacherDetails::find()
            ->alias('td')
            ->leftJoin('teacher_attenddence ta', 'ta.teacher_details_id = td.id AND ta.date = :currentDate', [':currentDate' => $currentDate])
            ->where(['td.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)])
            ->andWhere(['ta.teacher_details_id' => null])
            ->andWhere(['td.status' => TeacherDetails::STATUS_ACTIVE]); // Teachers without attendance record
    
        // Create DataProvider with sorting and pagination
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
        ]);
    
        // Load search parameters
        $this->load($params);
    
        if (!$this->validate()) {
            // If validation fails, return the dataProvider
            return $dataProvider;
        }
    
        // Apply additional filters based on user input
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'date_of_birth' => $this->date_of_birth,
            'academic_year_id' => $this->academic_year_id,
            'gender' => $this->gender,
            'blood_group_id' => $this->blood_group_id,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'status' => $this->status,
        ]);
    
        // Apply additional 'like' filters for string fields
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'profile_image', $this->profile_image])
            ->andFilterWhere(['like', 'id_number', $this->id_number])
            ->andFilterWhere(['like', 'father_name', $this->father_name])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address]);
    
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
        $query = TeacherDetails::find()
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
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'date_of_birth' => $this->date_of_birth,
            'academic_year_id' => $this->academic_year_id,
            'gender' => $this->gender,
            'blood_group_id' => $this->blood_group_id,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'status' => $this->status,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'profile_image', $this->profile_image])
            ->andFilterWhere(['like', 'id_number', $this->id_number])
            ->andFilterWhere(['like', 'father_name', $this->father_name])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address]);

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
