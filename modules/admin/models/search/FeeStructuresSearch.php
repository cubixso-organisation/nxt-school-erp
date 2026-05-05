<?php

namespace app\modules\admin\models\search;

use app\models\User;
use app\modules\admin\models\base\FeeStructures as BaseFeeStructures;
use app\modules\admin\models\Campus;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\FeeStructures;

/**
 * app\modules\admin\models\search\FeeStructuresSearch represents the model behind the search form about `app\modules\admin\models\FeeStructures`.
 */
class FeeStructuresSearch extends FeeStructures
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'fee_type_id', 'student_class_id', 'class_section_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['fee', 'maximum_detuction'], 'number'],
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
        $query = FeeStructures::find();

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
            'fee_type_id' => $this->fee_type_id,
            'student_class_id' => $this->student_class_id,
            'class_section_id' => $this->class_section_id,
            'fee' => $this->fee,
            'maximum_detuction' => $this->maximum_detuction,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);



        return $dataProvider;
    }






    public function campusSearch($params)
    {
        $query = FeeStructures::find();

        $query->joinWith('studentClass');

        $query->where(['fee_structures.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

        $query->andWhere(['is_agent' => null]);

        $query->andWhere(['in', 'fee_structures.status', [FeeStructures::STATUS_ACTIVE, FeeStructures::STATUS_INACTIVE, null]]);


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
            'fee_structures.id' => $this->id,
            'fee_structures.campus_id' => $this->campus_id,
            'fee_structures.fee_type_id' => $this->fee_type_id,
            'fee_structures.student_class_id' => $this->student_class_id,
            'fee_structures.class_section_id' => $this->class_section_id,
            'fee_structures.fee' => $this->fee,
            'fee_structures.maximum_detuction' => $this->maximum_detuction,
            'fee_structures.created_on' => $this->created_on,
            'fee_structures.updated_on' => $this->updated_on,
            'fee_structures.create_user_id' => $this->create_user_id,
            'fee_structures.update_user_id' => $this->update_user_id,
        ]);
        return $dataProvider;
    }






    public function campusSubAdminSearch($params)
    {
        $query = FeeStructures::find();

        $query->where(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)]);
        $query->andWhere(['status' => FeeStructures::STATUS_ACTIVE]);
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
            'fee_type_id' => $this->fee_type_id,
            'student_class_id' => $this->student_class_id,
            'class_section_id' => $this->class_section_id,
            'fee' => $this->fee,
            'maximum_detuction' => $this->maximum_detuction,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);
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
        $query = FeeStructures::find()
            ->where(['city_id' => \Yii::$app->user->identity->city_id]);

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
            'fee_type_id' => $this->fee_type_id,
            'student_class_id' => $this->student_class_id,
            'class_section_id' => $this->class_section_id,
            'fee' => $this->fee,
            'maximum_detuction' => $this->maximum_detuction,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

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
