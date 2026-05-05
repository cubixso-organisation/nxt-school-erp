<?php


namespace app\modules\childassessment\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\childassessment\models\MeritsAssignedToClass;

/**
 * app\modules\childassessment\models\search\MeritsAssignedToClassSearch represents the model behind the search form about `app\modules\childassessment\models\MeritsAssignedToClass`.
 */
class MeritsAssignedToClassSearch extends MeritsAssignedToClass
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'academic_year_id', 'campus_id', 'class_id', 'section_id', 'merit_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
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
        $query = MeritsAssignedToClass::find()->where(['status' => MeritsAssignedToClass::STATUS_ACTIVE])->orWhere(['status' => MeritsAssignedToClass::STATUS_INACTIVE])->where(['campus_id' => User::getCampusId(\Yii::$app->user->identity->user_role)]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'academic_year_id' => $this->academic_year_id,
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'merit_id' => $this->merit_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }




    public function SubAdminSearch($params)
    {
        $query = MeritsAssignedToClass::find()->where(['status' => MeritsAssignedToClass::STATUS_ACTIVE])->orWhere(['status' => MeritsAssignedToClass::STATUS_INACTIVE])->where(['campus_id' => User::getCampusId(\Yii::$app->user->identity->user_role)]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'academic_year_id' => $this->academic_year_id,
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'merit_id' => $this->merit_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }



    public function AccountantSearch($params)
    {
        $query = MeritsAssignedToClass::find()->where(['status' => MeritsAssignedToClass::STATUS_ACTIVE])->andWhere(['campus_id' => User::getCampusId(\Yii::$app->user->identity->user_role)])->orWhere(['status' => MeritsAssignedToClass::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'academic_year_id' => $this->academic_year_id,
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'merit_id' => $this->merit_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }




    public function DocumentationDepartmentSearch($params)
    {
        $query = MeritsAssignedToClass::find()->where(['status' => MeritsAssignedToClass::STATUS_ACTIVE])->andWhere(['campus_id' => User::getCampusId(\Yii::$app->user->identity->user_role)])->orWhere(['status' => MeritsAssignedToClass::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'academic_year_id' => $this->academic_year_id,
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'merit_id' => $this->merit_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }



    public function LmLeadsManagementSearch($params)
    {
        $query = MeritsAssignedToClass::find()->where(['status' => MeritsAssignedToClass::STATUS_ACTIVE])->orWhere(['status' => MeritsAssignedToClass::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'academic_year_id' => $this->academic_year_id,
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'merit_id' => $this->merit_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }



    public function DeliveryAdminSearch($params)
    {
        $query = MeritsAssignedToClass::find()->where(['status' => MeritsAssignedToClass::STATUS_ACTIVE])->orWhere(['status' => MeritsAssignedToClass::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
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
            'academic_year_id' => $this->academic_year_id,
            'campus_id' => $this->campus_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'merit_id' => $this->merit_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }
}
