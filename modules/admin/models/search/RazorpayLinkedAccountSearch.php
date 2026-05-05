<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\RazorpayLinkedAccount;

/**
 * app\modules\admin\models\search\RazorpayLinkedAccountSearch represents the model behind the search form about `app\modules\admin\models\RazorpayLinkedAccount`.
 */
 class RazorpayLinkedAccountSearch extends RazorpayLinkedAccount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['email', 'phone', 'reference_id', 'legal_business_name', 'business_type', 'contact_name', 'street1', 'street2', 'city', 'state', 'postal_code', 'country', 'pan', 'gst', 'razorpay_acc_id', 'account_status', 'account_number', 'ifsc_code', 'beneficiary_name', 'created_on', 'updated_on'], 'safe'],
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
        $query = RazorpayLinkedAccount::find();

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'reference_id', $this->reference_id])
            ->andFilterWhere(['like', 'legal_business_name', $this->legal_business_name])
            ->andFilterWhere(['like', 'business_type', $this->business_type])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'street1', $this->street1])
            ->andFilterWhere(['like', 'street2', $this->street2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'pan', $this->pan])
            ->andFilterWhere(['like', 'gst', $this->gst])
            ->andFilterWhere(['like', 'razorpay_acc_id', $this->razorpay_acc_id])
            ->andFilterWhere(['like', 'account_status', $this->account_status])
            ->andFilterWhere(['like', 'account_number', $this->account_number])
            ->andFilterWhere(['like', 'ifsc_code', $this->ifsc_code])
            ->andFilterWhere(['like', 'beneficiary_name', $this->beneficiary_name]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = RazorpayLinkedAccount::find();

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'reference_id', $this->reference_id])
            ->andFilterWhere(['like', 'legal_business_name', $this->legal_business_name])
            ->andFilterWhere(['like', 'business_type', $this->business_type])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'street1', $this->street1])
            ->andFilterWhere(['like', 'street2', $this->street2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'pan', $this->pan])
            ->andFilterWhere(['like', 'gst', $this->gst])
            ->andFilterWhere(['like', 'razorpay_acc_id', $this->razorpay_acc_id])
            ->andFilterWhere(['like', 'account_status', $this->account_status])
            ->andFilterWhere(['like', 'account_number', $this->account_number])
            ->andFilterWhere(['like', 'ifsc_code', $this->ifsc_code])
            ->andFilterWhere(['like', 'beneficiary_name', $this->beneficiary_name]);

        return $dataProvider;
    }



    
    public function institutesSearch($params)
    {
        $query = RazorpayLinkedAccount::find();

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'reference_id', $this->reference_id])
            ->andFilterWhere(['like', 'legal_business_name', $this->legal_business_name])
            ->andFilterWhere(['like', 'business_type', $this->business_type])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'street1', $this->street1])
            ->andFilterWhere(['like', 'street2', $this->street2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'pan', $this->pan])
            ->andFilterWhere(['like', 'gst', $this->gst])
            ->andFilterWhere(['like', 'razorpay_acc_id', $this->razorpay_acc_id])
            ->andFilterWhere(['like', 'account_status', $this->account_status])
            ->andFilterWhere(['like', 'account_number', $this->account_number])
            ->andFilterWhere(['like', 'ifsc_code', $this->ifsc_code])
            ->andFilterWhere(['like', 'beneficiary_name', $this->beneficiary_name]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = RazorpayLinkedAccount::find();

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
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'reference_id', $this->reference_id])
            ->andFilterWhere(['like', 'legal_business_name', $this->legal_business_name])
            ->andFilterWhere(['like', 'business_type', $this->business_type])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'street1', $this->street1])
            ->andFilterWhere(['like', 'street2', $this->street2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'pan', $this->pan])
            ->andFilterWhere(['like', 'gst', $this->gst])
            ->andFilterWhere(['like', 'razorpay_acc_id', $this->razorpay_acc_id])
            ->andFilterWhere(['like', 'account_status', $this->account_status])
            ->andFilterWhere(['like', 'account_number', $this->account_number])
            ->andFilterWhere(['like', 'ifsc_code', $this->ifsc_code])
            ->andFilterWhere(['like', 'beneficiary_name', $this->beneficiary_name]);

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
        $query = RazorpayLinkedAccount::find()
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
            'campus_id' => $this->campus_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'reference_id', $this->reference_id])
            ->andFilterWhere(['like', 'legal_business_name', $this->legal_business_name])
            ->andFilterWhere(['like', 'business_type', $this->business_type])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'street1', $this->street1])
            ->andFilterWhere(['like', 'street2', $this->street2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'pan', $this->pan])
            ->andFilterWhere(['like', 'gst', $this->gst])
            ->andFilterWhere(['like', 'razorpay_acc_id', $this->razorpay_acc_id])
            ->andFilterWhere(['like', 'account_status', $this->account_status])
            ->andFilterWhere(['like', 'account_number', $this->account_number])
            ->andFilterWhere(['like', 'ifsc_code', $this->ifsc_code])
            ->andFilterWhere(['like', 'beneficiary_name', $this->beneficiary_name]);

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
