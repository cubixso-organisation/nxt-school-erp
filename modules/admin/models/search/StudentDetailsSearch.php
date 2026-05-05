<?php

namespace app\modules\admin\models\search;

use app\models\User;
use app\modules\admin\models\base\StudentDetails as BaseStudentDetails;
use app\modules\admin\models\Campus;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\StudentDetails;

/**
 * app\modules\admin\models\search\StudentDetailsSearch represents the model behind the search form about `app\modules\admin\models\StudentDetails`.
 */
class StudentDetailsSearch extends StudentDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'user_id', 'student_class_id', 'section_id', 'hostal_is_required', 'bus_transport_required', 'blood_group_id', 'national_Identification_number', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['admission_number', 'rool_number', 'profile_photo', 'student_name', 'gender', 'date_of_birth', 'category', 'religion', 'caste', 'phone_number', 'academic_year', 'email', 'admission_date', 'student_house', 'height', 'weight', 'created_on', 'updated_on'], 'safe'],
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
    public function search($params, $campus_id = '')
    {
        $query = StudentDetails::find();

        if (!empty($campus_id)) {
            $query->where(['campus_id' => $campus_id]);
        }

        // Add descending order sorting (e.g., by 'id')
        $query->andWhere(['status' => BaseStudentDetails::STATUS_ACTIVE]);
        $query->orderBy(['student_name' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // Uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'campus_id' => $this->campus_id,
            'user_id' => $this->user_id,
            'student_class_id' => $this->student_class_id,
            'section_id' => $this->section_id,
            'hostal_is_required' => $this->hostal_is_required,
            'bus_transport_required' => $this->bus_transport_required,
            'admission_date' => $this->admission_date,
            'blood_group_id' => $this->blood_group_id,
            'national_Identification_number' => $this->national_Identification_number,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'admission_number', $this->admission_number])
            ->andFilterWhere(['like', 'rool_number', $this->rool_number])
            ->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['like', 'student_name', $this->student_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'date_of_birth', $this->date_of_birth])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'caste', $this->caste])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'academic_year', $this->academic_year])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'student_house', $this->student_house])
            ->andFilterWhere(['like', 'height', $this->height])
            ->andFilterWhere(['like', 'weight', $this->weight]);

        return $dataProvider;
    }

    public function hostelStudentSearch($params, $campus_id = '')
    {
        $query = StudentDetails::find();

        if (!empty($campus_id)) {
            $query->where(['campus_id' => $campus_id])->andWhere(['hostal_is_required' => 1]);
        }

        // Add descending order sorting (e.g., by 'id')
        $query->orderBy(['admission_number' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // Uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'campus_id' => $this->campus_id,
            'user_id' => $this->user_id,
            'student_class_id' => $this->student_class_id,
            'section_id' => $this->section_id,
            'hostal_is_required' => $this->hostal_is_required,
            'bus_transport_required' => $this->bus_transport_required,
            'admission_date' => $this->admission_date,
            'blood_group_id' => $this->blood_group_id,
            'national_Identification_number' => $this->national_Identification_number,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'admission_number', $this->admission_number])
            ->andFilterWhere(['like', 'rool_number', $this->rool_number])
            ->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['like', 'student_name', $this->student_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'date_of_birth', $this->date_of_birth])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'caste', $this->caste])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'academic_year', $this->academic_year])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'student_house', $this->student_house])
            ->andFilterWhere(['like', 'height', $this->height])
            ->andFilterWhere(['like', 'weight', $this->weight]);

        return $dataProvider;
    }
    public function searchLeftStudent($params, $campus_id = '')
    {
        $query = StudentDetails::find()->where(['status' => BaseStudentDetails::STATUS_LEAVE])->andWhere(['campus_id' => $campus_id]);

        if (!empty($campus_id)) {
            $query->where(['campus_id' => $campus_id])->andWhere(['status' => BaseStudentDetails::STATUS_LEAVE]);
        }




        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
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
            'user_id' => $this->user_id,
            'student_class_id' => $this->student_class_id,
            'section_id' => $this->section_id,
            'hostal_is_required' => $this->hostal_is_required,
            'bus_transport_required' => $this->bus_transport_required,
            'admission_date' => $this->admission_date,
            'blood_group_id' => $this->blood_group_id,
            'national_Identification_number' => $this->national_Identification_number,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'admission_number', $this->admission_number])
            ->andFilterWhere(['like', 'rool_number', $this->rool_number])
            ->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['like', 'student_name', $this->student_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'date_of_birth', $this->date_of_birth])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'caste', $this->caste])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'academic_year', $this->academic_year])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'student_house', $this->student_house])
            ->andFilterWhere(['like', 'height', $this->height])
            ->andFilterWhere(['like', 'weight', $this->weight]);

        return $dataProvider;
    }






    public function subcampussearch($params)
    {
        $query = StudentDetails::find();

        $query->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 100,
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
            'user_id' => $this->user_id,
            'student_class_id' => $this->student_class_id,
            'section_id' => $this->section_id,
            'hostal_is_required' => $this->hostal_is_required,
            'bus_transport_required' => $this->bus_transport_required,
            'admission_date' => $this->admission_date,
            'blood_group_id' => $this->blood_group_id,
            'national_Identification_number' => $this->national_Identification_number,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'admission_number', $this->admission_number])
            ->andFilterWhere(['like', 'rool_number', $this->rool_number])
            ->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['like', 'student_name', $this->student_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'date_of_birth', $this->date_of_birth])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'caste', $this->caste])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'academic_year', $this->academic_year])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'student_house', $this->student_house])
            ->andFilterWhere(['like', 'height', $this->height])
            ->andFilterWhere(['like', 'weight', $this->weight]);

        return $dataProvider;
    }














    public function StudentSearchBySidCid($params, $student_class_id = '', $class_section_id = '', $academic_year_id = '')
    {
        $query = StudentDetails::find();

        $query->where(['campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

        if (!empty($student_class_id)) {
            $query->andWhere(['student_class_id' => $student_class_id]);
        }
        if (!empty($class_section_id)) {
            $query->andWhere(['section_id' => $class_section_id]);
        }
        if (!empty($academic_year_id)) {
            $query->andWhere(['academic_year_id' => $academic_year_id]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,


                ],


            ],

            'pagination' => [
                'pageSize' => 1000,
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
            'user_id' => $this->user_id,
            'student_class_id' => $this->student_class_id,
            'section_id' => $this->section_id,
            'hostal_is_required' => $this->hostal_is_required,
            'bus_transport_required' => $this->bus_transport_required,
            'admission_date' => $this->admission_date,
            'blood_group_id' => $this->blood_group_id,
            'national_Identification_number' => $this->national_Identification_number,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'admission_number', $this->admission_number])
            ->andFilterWhere(['like', 'rool_number', $this->rool_number])
            ->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['like', 'student_name', $this->student_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'date_of_birth', $this->date_of_birth])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'caste', $this->caste])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'academic_year', $this->academic_year])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'student_house', $this->student_house])
            ->andFilterWhere(['like', 'height', $this->height])
            ->andFilterWhere(['like', 'weight', $this->weight]);

        return $dataProvider;
    }
}
