<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Institutes;

/**
 * app\modules\admin\models\search\InstitutesSearch represents the model behind the search form about `app\modules\admin\models\Institutes`.
 */
 class InstitutesSearch extends Institutes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'subscription_type', 'activation_modules', 'educational_institution_type_id', 'user_id', 'country_id', 'state_id', 'district_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['name_of_the_educational_Institution', 'pincode', 'address', 'institute_code', 'registration_number', 'registration_document', 'name_of_the_authorized', 'designation_of_the_authorized', 'contact_number_of_the_authorized', 'name_of_the_contact', 'designation_of_the_contact', 'contact_number_of_the_contact', 'email_id_of_the_authorized', 'aadhaar_of_the_authorized', 'coordinates', 'school_logo', 'created_on', 'updated_on'], 'safe'],
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
    public function search($params)
    {
        $query = Institutes::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'subscription_type' => $this->subscription_type,
            'activation_modules' => $this->activation_modules,
            'educational_institution_type_id' => $this->educational_institution_type_id,
            'user_id' => $this->user_id,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'district_id' => $this->district_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'name_of_the_educational_Institution', $this->name_of_the_educational_Institution])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'institute_code', $this->institute_code])
            ->andFilterWhere(['like', 'registration_number', $this->registration_number])
            ->andFilterWhere(['like', 'registration_document', $this->registration_document])
            ->andFilterWhere(['like', 'name_of_the_authorized', $this->name_of_the_authorized])
            ->andFilterWhere(['like', 'designation_of_the_authorized', $this->designation_of_the_authorized])
            ->andFilterWhere(['like', 'contact_number_of_the_authorized', $this->contact_number_of_the_authorized])
            ->andFilterWhere(['like', 'name_of_the_contact', $this->name_of_the_contact])
            ->andFilterWhere(['like', 'designation_of_the_contact', $this->designation_of_the_contact])
            ->andFilterWhere(['like', 'contact_number_of_the_contact', $this->contact_number_of_the_contact])
            ->andFilterWhere(['like', 'email_id_of_the_authorized', $this->email_id_of_the_authorized])
            ->andFilterWhere(['like', 'aadhaar_of_the_authorized', $this->aadhaar_of_the_authorized])
            ->andFilterWhere(['like', 'coordinates', $this->coordinates])
            ->andFilterWhere(['like', 'school_logo', $this->school_logo]);

        return $dataProvider;
    }
}
