<?php

namespace app\modules\admin\models\search;

use app\modules\admin\models\Campus;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\EmployeeDetails;
use app\modules\admin\models\User;

/**
 * app\modules\admin\models\search\EmployeeDetailsSearch represents the model behind the search form about `app\modules\admin\models\EmployeeDetails`.
 */
class EmployeeDetailsSearch extends EmployeeDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'campus_id', 'designation_id', 'age', 'blood_group_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['employ_name', 'profile_picture', 'employee_id', 'admissions', 'gender', 'phone_number', 'email', 'license_number', 'created_on', 'updated_on'], 'safe'],
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
        $query = EmployeeDetails::find();

        $query->andWhere(['employee_details.status' => null]);

        if (!empty($campus_id)) {
            $query->where(['campus_id' => $campus_id]);
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
            'employee_details.id' => $this->id,
            'employee_details.user_id' => $this->user_id,
            'employee_details.campus_id' => $this->campus_id,
            'designation_id' => $this->designation_id,
            'employee_details.age' => $this->age,
            'blood_group_id' => $this->blood_group_id,
            'employee_details.created_on' => $this->created_on,
            'employee_details.updated_on' => $this->updated_on,
            'employee_details.create_user_id' => $this->create_user_id,
            'employee_details.update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'employ_name', $this->employ_name])
            ->andFilterWhere(['like', 'profile_picture', $this->profile_picture])
            ->andFilterWhere(['like', 'employee_details.employee_id', $this->employee_id])
            ->andFilterWhere(['like', 'employee_details.gender', $this->gender])
            ->andFilterWhere(['like', 'employee_details.phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'employee_details.email', $this->email])
            ->andFilterWhere(['like', 'employee_details.license_number', $this->license_number]);

        return $dataProvider;
    }






    public function institutesSearch($params)
    {
        $query = EmployeeDetails::find();
        $campus_id = (new Campus())->getInstituteHasCampusIds();
        // print_r($campus_id);

        if (!empty($campus_id)) {
            $query->where(['in', 'campus_id', $campus_id]);
        }
        $query->andWhere(['employee_details.status' => null]);



        // $query->joinWith('user');
        // $query->andWhere(['user.user_role'=>User::ROLE_BUS_COORDINATOR]);

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
            'employee_details.user_id' => $this->user_id,
            'campus_id' => $this->campus_id,
            'designation_id' => $this->designation_id,
            'employee_details.age' => $this->age,
            'blood_group_id' => $this->blood_group_id,
            'employee_details.created_on' => $this->created_on,
            'employee_details.updated_on' => $this->updated_on,
            'employee_details.create_user_id' => $this->create_user_id,
            'employee_details.update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'employ_name', $this->employ_name])
            ->andFilterWhere(['like', 'profile_picture', $this->profile_picture])
            ->andFilterWhere(['like', 'employee_id', $this->employee_id])
            ->andFilterWhere(['like', 'employee_details.gender', $this->gender])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'license_number', $this->license_number]);

        return $dataProvider;
    }





    public function campusSearch($params, $role = '')
    {
        $query = EmployeeDetails::find();

        $query->where(['employee_details.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);
        if (!empty($role)) {
            $query->joinWith(['user']);
            $query->andWhere(['user.user_role' => $role]);
        }

        $query->andWhere(['employee_details.status' => null]);
        // $query->andWhere(['!=', 'employee_details.status',EmployeeDetails::STATUS_DELETE]);


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
            'employee_details.id' => $this->id,
            'employee_details.user_id' => $this->user_id,
            'employee_details.campus_id' => $this->campus_id,
            'designation_id' => $this->designation_id,
            'age' => $this->age,
            'blood_group_id' => $this->blood_group_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'employee_details.create_user_id' => $this->create_user_id,
            'employee_details.update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'employ_name', $this->employ_name])
            ->andFilterWhere(['like', 'profile_picture', $this->profile_picture])
            ->andFilterWhere(['like', 'employee_id', $this->employee_id])
            ->andFilterWhere(['like', 'employee_details.gender', $this->gender])
            ->andFilterWhere(['like', 'employee_details.phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'employee_details.email', $this->email])
            ->andFilterWhere(['like', 'employee_details.license_number', $this->license_number]);

        return $dataProvider;
    }








    public function campusSubAdminSearch($params, $role = '')
    {
        $query = EmployeeDetails::find();

        $query->where(['employee_details.campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)]);
        if (!empty($role)) {
            $query->joinWith(['user']);
            $query->andWhere(['user.user_role' => $role]);
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
            'employee_details.id' => $this->id,
            'employee_details.user_id' => $this->user_id,
            'employee_details.campus_id' => $this->campus_id,
            'designation_id' => $this->designation_id,
            'age' => $this->age,
            'blood_group_id' => $this->blood_group_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'employee_details.create_user_id' => $this->create_user_id,
            'employee_details.update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'employ_name', $this->employ_name])
            ->andFilterWhere(['like', 'profile_picture', $this->profile_picture])
            ->andFilterWhere(['like', 'employee_id', $this->employee_id])
            ->andFilterWhere(['like', 'employee_details.gender', $this->gender])
            ->andFilterWhere(['like', 'employee_details.phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'employee_details.email', $this->email])
            ->andFilterWhere(['like', 'employee_details.license_number', $this->license_number]);


        // echo $query->createCommand()->getRawSql();


        return $dataProvider;
    }
}
