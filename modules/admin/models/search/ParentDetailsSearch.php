<?php

namespace app\modules\admin\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\ParentDetails;

/**
 * app\modules\admin\models\search\ParentDetailsSearch represents the model behind the search form about `app\modules\admin\models\ParentDetails`.
 */
class ParentDetailsSearch extends ParentDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['name_of_the_father', 'name_of_the_mother', 'current_address', 'permanent_address', 'contact_number', 'father_education_qualification', 'mother_education_qualification', 'father_aadhaar_number', 'mother_aadhaar_number', 'father_occupation', 'mother_occupation', 'created_on', 'updated_on'], 'safe'],
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
        $query = ParentDetails::find()
            ->innerJoinWith('parentHasCampuses as phs')
            ->where([
                'phs.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id),
                'parent_details.status' => ParentDetails::STATUS_ACTIVE, // assuming constant
            ]);

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_father', $this->name_of_the_father])
            ->andFilterWhere(['like', 'name_of_the_mother', $this->name_of_the_mother])
            ->andFilterWhere(['like', 'current_address', $this->current_address])
            ->andFilterWhere(['like', 'permanent_address', $this->permanent_address])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'father_education_qualification', $this->father_education_qualification])
            ->andFilterWhere(['like', 'mother_education_qualification', $this->mother_education_qualification])
            ->andFilterWhere(['like', 'father_aadhaar_number', $this->father_aadhaar_number])
            ->andFilterWhere(['like', 'mother_aadhaar_number', $this->mother_aadhaar_number])
            ->andFilterWhere(['like', 'father_occupation', $this->father_occupation])
            ->andFilterWhere(['like', 'mother_occupation', $this->mother_occupation]);

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
        $query = ParentDetails::find()
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

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_father', $this->name_of_the_father])
            ->andFilterWhere(['like', 'name_of_the_mother', $this->name_of_the_mother])
            ->andFilterWhere(['like', 'current_address', $this->current_address])
            ->andFilterWhere(['like', 'permanent_address', $this->permanent_address])
            ->andFilterWhere(['like', 'contact_number', $this->contact_number])
            ->andFilterWhere(['like', 'father_education_qualification', $this->father_education_qualification])
            ->andFilterWhere(['like', 'mother_education_qualification', $this->mother_education_qualification])
            ->andFilterWhere(['like', 'father_aadhaar_number', $this->father_aadhaar_number])
            ->andFilterWhere(['like', 'mother_aadhaar_number', $this->mother_aadhaar_number])
            ->andFilterWhere(['like', 'father_occupation', $this->father_occupation])
            ->andFilterWhere(['like', 'mother_occupation', $this->mother_occupation]);

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
