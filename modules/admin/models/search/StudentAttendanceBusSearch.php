<?php

namespace app\modules\admin\models\search;

use app\models\User;
use app\modules\admin\models\Campus;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\StudentAttendanceBus;

/**
 * app\modules\admin\models\search\StudentAttendanceBusSearch represents the model behind the search form about `app\modules\admin\models\StudentAttendanceBus`.
 */
 class StudentAttendanceBusSearch extends StudentAttendanceBus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bus_route_id', 'student_id', 'student_has_bus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['unique_key', 'created_on', 'updated_on','student_class_id','section_id'], 'safe'],
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
        $query = StudentAttendanceBus::find();

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
            'bus_route_id' => $this->bus_route_id,
            'student_id' => $this->student_id,
            'student_has_bus_id' => $this->student_has_bus_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'unique_key', $this->unique_key]);

        return $dataProvider;
    }



    public function campusSearch($params,$bus_id='',$student_id='')
    {
        $query = StudentAttendanceBus::find();



        $query->innerJoinWith(['busRoute']);
        $query->innerJoinWith(['student.studentClass']);
        $query->innerJoinWith(['student.section']);

        $query->where(['bus_route.campus_id'=>User::getCampusesByUser(Yii::$app->user->identity->id)]);

        if(!empty($bus_id)){
            $query->andWhere(['bus_route.bus_id'=>$bus_id]);
        }

        if(!empty($student_id)){
            $query->andWhere(['student_attendance_bus.student_id'=>$student_id]);
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
            'bus_route_id' => $this->bus_route_id,
            'student_attendance_bus.student_id' => $this->student_id,
            'student_has_bus_id' => $this->student_has_bus_id,
            'status' => $this->status,
            // 'student_attendance_bus.created_on' => $this->created_on,
            'student_attendance_bus.updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'class_sections.id' => $this->section_id,
            'student_class.id' => $this->student_class_id,

        ]);

        if(isset($this->created_on)&&$this->created_on!=''){ 
           
                 $date_explode=explode(" - ",$this->created_on);
                  $date1=trim($date_explode[0]);
                  $convertedDate =   date("Y-m-d", strtotime($date1));
                  $d1 = date($convertedDate. ' 00:00:00');
                  $d2 = date($convertedDate. ' 23:59:59');
                  $date2=trim($date_explode[1]);
                  $query->andFilterWhere(['between','student_attendance_bus.created_on',$d1,$d2]);
           }
        // $query->andFilterWhere(['like', 'unique_key', $this->unique_key]);
        // echo $query->createCommand()->getRawSql();
        //    exit;
        return $dataProvider;
 
    }



    
    public function campusAdminSearch($params,$bus_id='')
    {
        $query = StudentAttendanceBus::find();


        // User::getCampusesByUser(Yii::$app->user->identity->id)

        $query->innerJoinWith(['busRoute']);
        $query->innerJoinWith(['student.studentClass']);
        $query->innerJoinWith(['student.section']);

        $query->where(['bus_route.campus_id'=>(new User())->getCampusesByUser(Yii::$app->user->identity->id)]);

        if(!empty($bus_id)){
            $query->andWhere(['bus_route.bus_id'=>$bus_id]);
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
            'bus_route_id' => $this->bus_route_id,
            'student_attendance_bus.student_id' => $this->student_id,
            'student_has_bus_id' => $this->student_has_bus_id,
            'status' => $this->status,
            // 'student_attendance_bus.created_on' => $this->created_on,
            'student_attendance_bus.updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'class_sections.id' => $this->section_id,
            'student_class.id' => $this->student_class_id,

        ]);

        if(isset($this->created_on)&&$this->created_on!=''){ 
           
                 $date_explode=explode(" - ",$this->created_on);
                  $date1=trim($date_explode[0]);
                  $convertedDate =   date("Y-m-d", strtotime($date1));
                  $d1 = date($convertedDate. ' 00:00:00');
                  $d2 = date($convertedDate. ' 23:59:59');
                  $date2=trim($date_explode[1]);
                  $query->andFilterWhere(['between','student_attendance_bus.created_on',$d1,$d2]);
           }
        // $query->andFilterWhere(['like', 'unique_key', $this->unique_key]);
        // echo $query->createCommand()->getRawSql();
        //    exit;
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
        $query = StudentAttendanceBus::find()
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
            'bus_route_id' => $this->bus_route_id,
            'student_id' => $this->student_id,
            'student_has_bus_id' => $this->student_has_bus_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'unique_key', $this->unique_key]);

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
