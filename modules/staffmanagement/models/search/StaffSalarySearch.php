<?php

namespace app\modules\staffmanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\StaffSalary;

/**
 * app\modules\staffmanagement\models\search\StaffSalarySearch represents the model behind the search form about `app\modules\staffmanagement\models\StaffSalary`.
 */
class StaffSalarySearch extends StaffSalary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'staff_id', 'basic_salary_type', 'salary_group_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['ctc', 'basic_salary_value', 'ctc_monthly', 'ctc_yearly', 'total_deduction_monthly', 'total_deduction_yearly'], 'number'],
            [['earnings', 'created_on', 'updated_on'], 'safe'],
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
        $query = StaffSalary::find();

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
            'staff_id' => $this->staff_id,
            'ctc' => $this->ctc,
            'basic_salary_type' => $this->basic_salary_type,
            'basic_salary_value' => $this->basic_salary_value,
            'ctc_monthly' => $this->ctc_monthly,
            'ctc_yearly' => $this->ctc_yearly,
            'total_deduction_monthly' => $this->total_deduction_monthly,
            'total_deduction_yearly' => $this->total_deduction_yearly,
            'salary_group_id' => $this->salary_group_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'earnings', $this->earnings]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = StaffSalary::find()->where(['campus_id' => (new User())->getCampusId()]);

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
            'staff_id' => $this->staff_id,
            'ctc' => $this->ctc,
            'basic_salary_type' => $this->basic_salary_type,
            'basic_salary_value' => $this->basic_salary_value,
            'ctc_monthly' => $this->ctc_monthly,
            'ctc_yearly' => $this->ctc_yearly,
            'total_deduction_monthly' => $this->total_deduction_monthly,
            'total_deduction_yearly' => $this->total_deduction_yearly,
            'salary_group_id' => $this->salary_group_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'earnings', $this->earnings]);

        return $dataProvider;
    }




    public function institutesSearch($params)
    {
        $query = StaffSalary::find();

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
            'staff_id' => $this->staff_id,
            'ctc' => $this->ctc,
            'basic_salary_type' => $this->basic_salary_type,
            'basic_salary_value' => $this->basic_salary_value,
            'ctc_monthly' => $this->ctc_monthly,
            'ctc_yearly' => $this->ctc_yearly,
            'total_deduction_monthly' => $this->total_deduction_monthly,
            'total_deduction_yearly' => $this->total_deduction_yearly,
            'salary_group_id' => $this->salary_group_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'earnings', $this->earnings]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = StaffSalary::find();

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
            'staff_id' => $this->staff_id,
            'ctc' => $this->ctc,
            'basic_salary_type' => $this->basic_salary_type,
            'basic_salary_value' => $this->basic_salary_value,
            'ctc_monthly' => $this->ctc_monthly,
            'ctc_yearly' => $this->ctc_yearly,
            'total_deduction_monthly' => $this->total_deduction_monthly,
            'total_deduction_yearly' => $this->total_deduction_yearly,
            'salary_group_id' => $this->salary_group_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'earnings', $this->earnings]);

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
        $query = StaffSalary::find()
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
            'campus_id' => $this->campus_id,
            'staff_id' => $this->staff_id,
            'ctc' => $this->ctc,
            'basic_salary_type' => $this->basic_salary_type,
            'basic_salary_value' => $this->basic_salary_value,
            'ctc_monthly' => $this->ctc_monthly,
            'ctc_yearly' => $this->ctc_yearly,
            'total_deduction_monthly' => $this->total_deduction_monthly,
            'total_deduction_yearly' => $this->total_deduction_yearly,
            'salary_group_id' => $this->salary_group_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'earnings', $this->earnings]);

        if (isset($this->created_on) && $this->created_on != '') {

            //you dont need the if function if yourse sure you have a not null date
            $date_explode = explode(" - ", $this->created_on);
            //   var_dump($date_explode);exit;
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['between', 'created_on', $date1, $date2]);
            // var_dump($query->createCommand()->getRawSql());exit;
        }
        if (isset($this->updated_on) && $this->updated_on != '') {

            //you dont need the if function if yourse sure you have a not null date
            $date_explode = explode(" - ", $this->updated_on);
            //   var_dump($date_explode);exit;
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['between', 'updated_on', $date1, $date2]);
            //  var_dump($query->createCommand()->getRawSql());exit;
        }

        return $dataProvider;
    }
}
