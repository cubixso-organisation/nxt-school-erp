<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\StudentDetailsAgentLead;

/**
 * app\modules\admin\models\search\StudentDetailsAgentLeadSearch represents the model behind the search form about `app\modules\admin\models\StudentDetailsAgentLead`.
 */
class StudentDetailsAgentLeadSearch extends StudentDetailsAgentLead
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'agent_id', 'student_class_id', 'special_courses_id', 'section_id', 'hostal_is_required', 'bus_transport_required', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['profile_photo', 'student_name','admissions' ,'gender', 'date_of_birth', 'name_of_the_parent', 'phone_number', 'verified_phone', 'previous_school_name', 'previous_school_address', 'academic_year','payment_status','created_on', 'updated_on'], 'safe'],
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
    public function search($params, $campus_id='', $status='')
    {
        $query = StudentDetailsAgentLead::find();

        $query->joinWith('agentStudentJoins');

        if (!empty($campus_id)) {
            $query->where(['student_details_agent_lead.campus_id'=>$campus_id]);
        }
        if (!empty($status)) {
            $query->andWhere(['student_details_agent_lead.status'=>$status]);
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
            'student_details_agent_lead.campus_id' => $this->campus_id,
            'student_details_agent_lead.agent_id' => $this->agent_id,
            'student_class_id' => $this->student_class_id,
            'special_courses_id' => $this->special_courses_id,
            'section_id' => $this->section_id,
            'hostal_is_required' => $this->hostal_is_required,
             'bus_transport_required' => $this->bus_transport_required,
            'student_details_agent_lead.status' => $this->status,
            'agent_student_join.status'=>$this->payment_status,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['like', 'student_name', $this->student_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'date_of_birth', $this->date_of_birth])
            ->andFilterWhere(['like', 'name_of_the_parent', $this->name_of_the_parent])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'verified_phone', $this->verified_phone])
            ->andFilterWhere(['like', 'previous_school_name', $this->previous_school_name])
            ->andFilterWhere(['like', 'previous_school_address', $this->previous_school_address])
            ->andFilterWhere(['like', 'academic_year', $this->academic_year]);


        // var_dump($query->createCommand()->getRawSql());

        if (isset($this->created_on)&&$this->created_on!='') {
            $date_explode=explode(" - ", $this->created_on);

            $date1=trim($date_explode[0]);
            $date2=trim($date_explode[1]);
            $query->andFilterWhere(['between','student_details_agent_lead.created_on',$date1,$date2]);
        }
        if (isset($this->updated_on)&&$this->updated_on!='') {
            $date_explode=explode(" - ", $this->updated_on);

            $date1=trim($date_explode[0]);
            $date2=trim($date_explode[1]);
            $query->andFilterWhere(['between','student_details_agent_lead.updated_on',$date1,$date2]);
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
        $query = StudentDetailsAgentLead::find()
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
            'student_details_agent_lead.campus_id' => $this->campus_id,
            'student_details_agent_lead.agent_id' => $this->agent_id,
            'student_class_id' => $this->student_class_id,
            'special_courses_id' => $this->special_courses_id,
            'section_id' => $this->section_id,
            'hostal_is_required' => $this->hostal_is_required,
            'bus_transport_required' => $this->bus_transport_required,
            'student_details_agent_lead.status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['like', 'student_name', $this->student_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'date_of_birth', $this->date_of_birth])
            ->andFilterWhere(['like', 'name_of_the_parent', $this->name_of_the_parent])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'verified_phone', $this->verified_phone])
            ->andFilterWhere(['like', 'previous_school_name', $this->previous_school_name])
            ->andFilterWhere(['like', 'previous_school_address', $this->previous_school_address])
            ->andFilterWhere(['like', 'academic_year', $this->academic_year]);

        if (isset($this->created_on)&&$this->created_on!='') {
            //you dont need the if function if yourse sure you have a not null date
            $date_explode=explode(" - ", $this->created_on);
            //   var_dump($date_explode);exit;
            $date1=trim($date_explode[0]);
            $date2=trim($date_explode[1]);
            $query->andFilterWhere(['between','created_on',$date1,$date2]);
            // var_dump($query->createCommand()->getRawSql());exit;
        }
        if (isset($this->updated_on)&&$this->updated_on!='') {
            //you dont need the if function if yourse sure you have a not null date
            $date_explode=explode(" - ", $this->updated_on);
            //   var_dump($date_explode);exit;
            $date1=trim($date_explode[0]);
            $date2=trim($date_explode[1]);
            $query->andFilterWhere(['between','updated_on',$date1,$date2]);
            //  var_dump($query->createCommand()->getRawSql());exit;
        }

        return $dataProvider;
    }
}
