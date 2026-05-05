<?php

namespace app\modules\admin\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TeacherAttenddence;

/**
 * app\modules\admin\models\search\TeacherAttenddenceSearch represents the model behind the search form about `app\modules\admin\models\TeacherAttenddence`.
 */
 class TeacherAttenddenceSearch extends TeacherAttenddence
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'teacher_details_id', 'status', 'create_user_id', 'updated_user_id'], 'integer'],
            [['teacher_present_date_and_time', 'date', 'created_on', 'updated_on'], 'safe'],
            [['lat', 'lng'], 'number'],
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
    public function search($params, $teacher_id='')
    {
        $query = TeacherAttenddence::find();
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
        if(!empty($campus_id)){
            $query->where(['campus_id'=>$campus_id]);
        }
        if(!empty($teacher_id)){
            $query->andWhere(['$teacher_id'=>$teacher_id]);
        }
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
            'teacher_details_id' => $this->teacher_details_id,
            // 'teacher_present_date_and_time' => $this->teacher_present_date_and_time,
            'date' => $this->date,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'checkout_date_time' => $this->checkout_date_time,
            'checkout_lat' => $this->checkout_lat,
            'checkout_lng' => $this->checkout_lat,
            'status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'updated_user_id' => $this->updated_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
        ]);
        if (!empty($this->teacher_present_date_and_time)) {
            list($startDate, $endDate) = explode(' - ', $this->teacher_present_date_and_time);
            $startDate = \DateTime::createFromFormat('Y-m-d', $startDate)->format('Y-m-d 00:00:00');
            $endDate = \DateTime::createFromFormat('Y-m-d', $endDate)->format('Y-m-d 23:59:59');
            $query->andFilterWhere(['between', 'teacher_present_date_and_time', $startDate, $endDate]);
        }
        return $dataProvider;
    }



    public function campusAdminSearch($params, $teacher_id = '')
    {
        $query = TeacherAttenddence::find()->innerJoinWith('teacherDetails as td');
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
        $query->where(['td.campus_id' => $campus_id]);
    
        if (!empty($teacher_id)) {
            $query->andWhere(['teacher_details_id' => $teacher_id]);
        }
    
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
            'teacher_details_id' => $this->teacher_details_id,
            // 'teacher_present_date_and_time' => $this->teacher_present_date_and_time,

            'date' => $this->date,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'updated_user_id' => $this->updated_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
        ]);
    
       
        if (!empty($this->teacher_present_date_and_time)) {
            list($startDate, $endDate) = explode(' - ', $this->teacher_present_date_and_time);
            $startDate = \DateTime::createFromFormat('Y-m-d', $startDate)->format('Y-m-d 00:00:00');
            $endDate = \DateTime::createFromFormat('Y-m-d', $endDate)->format('Y-m-d 23:59:59');
            $query->andFilterWhere(['between', 'teacher_present_date_and_time', $startDate, $endDate]);
        }
        return $dataProvider;
    }
    public function oldIndexSearch($params, $teacher_id = '')
    {
        // Join with the teacherDetails relation
        $query = TeacherAttenddence::find()->innerJoinWith('teacherDetails as td');
        
        // Get the campus ID of the logged-in user
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
        
        // Filter by campus ID
        $query->where(['td.campus_id' => $campus_id]);
    
        // If a teacher ID is provided, filter by it
        if (!empty($teacher_id)) {
            $query->andWhere(['teacher_attenddence.teacher_details_id' => $teacher_id]);
        }
    
        // Create ActiveDataProvider for pagination and sorting
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
        ]);
    
        // Load the search parameters
        $this->load($params);
    
        // Validate input
        if (!$this->validate()) {
            return $dataProvider;
        }
    
        // Add basic filtering conditions
        $query->andFilterWhere([
            'teacher_attenddence.id' => $this->id,
            'teacher_attenddence.teacher_details_id' => $this->teacher_details_id,
            'teacher_attenddence.date' => $this->date, // Filter where the date is today
            'teacher_attenddence.lat' => $this->lat,
            'teacher_attenddence.lng' => $this->lng,
            'teacher_attenddence.status' => $this->status, // Assuming status 0 is inactive in teacher_attenddence table
            'teacher_attenddence.create_user_id' => $this->create_user_id,
            'teacher_attenddence.updated_user_id' => $this->updated_user_id,
            'teacher_attenddence.created_on' => $this->created_on,
            'teacher_attenddence.updated_on' => $this->updated_on,
        ]);
    
        // Add date range filtering for teacher_present_date_and_time if provided
        if (!empty($this->teacher_present_date_and_time)) {
            list($startDate, $endDate) = explode(' - ', $this->teacher_present_date_and_time);
            
            // Convert date to 'Y-m-d H:i:s' format
            $startDate = \DateTime::createFromFormat('Y-m-d', $startDate)->format('Y-m-d 00:00:00');
            $endDate = \DateTime::createFromFormat('Y-m-d', $endDate)->format('Y-m-d 23:59:59');
            
            // Filter between the start and end date
            $query->andFilterWhere(['between', 'teacher_attenddence.teacher_present_date_and_time', $startDate, $endDate]);
        }
    
        return $dataProvider;
    }
    

    



    
    public function institutesSearch($params)
    {
        $query = TeacherAttenddence::find();

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
            'teacher_details_id' => $this->teacher_details_id,
            // 'teacher_present_date_and_time' => $this->teacher_present_date_and_time,
            'date' => $this->date,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'updated_user_id' => $this->updated_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
        ]);
    if (!$this->validate()) {
        return $dataProvider;
    }
        if (!empty($this->teacher_present_date_and_time)) {
            list($startDate, $endDate) = explode(' - ', $this->teacher_present_date_and_time);
            $startDate = \DateTime::createFromFormat('Y-m-d', $startDate)->format('Y-m-d 00:00:00');
            $endDate = \DateTime::createFromFormat('Y-m-d', $endDate)->format('Y-m-d 23:59:59');
            $query->andFilterWhere(['between', 'teacher_present_date_and_time', $startDate, $endDate]);
        }
        
        
        return $dataProvider;
    }




    public function campusSubAdminSearch($params,$teacher_id = '')
{
    
    $query = TeacherAttenddence::find()->innerJoinWith('teacherDetails as td');
    $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
    $query->where(['td.campus_id' => $campus_id]);

    if (!empty($teacher_id)) {
        $query->andWhere(['teacher_details_id' => $teacher_id]);
    }

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
        // Uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
        return $dataProvider;
    }

    // Apply other filters
    $query->andFilterWhere([
        'id' => $this->id,
        'teacher_details_id' => $this->teacher_details_id,
        'date' => $this->date,
        'lat' => $this->lat,
        'lng' => $this->lng,
        'status' => $this->status,
        'create_user_id' => $this->create_user_id,
        'updated_user_id' => $this->updated_user_id,
        'created_on' => $this->created_on,
        'updated_on' => $this->updated_on,
    ]);
    if (!$this->validate()) {
        return $dataProvider;
    }
    // Ensure the date range filter is correctly applied
    
    if (!empty($this->teacher_present_date_and_time)) {
        list($startDate, $endDate) = explode(' - ', $this->teacher_present_date_and_time);
        $startDate = \DateTime::createFromFormat('Y-m-d', $startDate)->format('Y-m-d 00:00:00');
        $endDate = \DateTime::createFromFormat('Y-m-d', $endDate)->format('Y-m-d 23:59:59');
        $query->andFilterWhere(['between', 'teacher_present_date_and_time', $startDate, $endDate]);
    }
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
        $query = TeacherAttenddence::find()
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
            'teacher_details_id' => $this->teacher_details_id,
            'teacher_present_date_and_time' => $this->teacher_present_date_and_time,
            'date' => $this->date,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'updated_user_id' => $this->updated_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
        ]);

        if(isset ($this->teacher_present_date_and_time)&&$this->teacher_present_date_and_time!=''){ 
           
            //you dont need the if function if yourse sure you have a not null date
             $date_explode=explode(" - ",$this->teacher_present_date_and_time);
          //   var_dump($date_explode);exit;
             $date1=trim($date_explode[0]);
            $date2=trim($date_explode[1]);
            $query->andFilterWhere(['between','teacher_present_date_and_time',$date1,$date2]);
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
