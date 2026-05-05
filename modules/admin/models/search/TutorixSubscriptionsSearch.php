<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TutorixSubscriptions;

/**
 * app\modules\admin\models\search\TutorixSubscriptionsSearch represents the model behind the search form about `app\modules\admin\models\TutorixSubscriptions`.
 */
 class TutorixSubscriptionsSearch extends TutorixSubscriptions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'student_id', 'parent_id', 'subscription_type', 'campus_id', 'total_item', 'coupon_applied_id', 'payment_status', 'payment_method', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['total_item_price', 'gst_percentage', 'gst_amount', 'other_charges', 'coupon_discount', 'total_amount'], 'number'],
            [['coupon_code', 'tutorix_user_access_token', 'unique_id', 'created_on', 'updated_on'], 'safe'],
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
        $query = TutorixSubscriptions::find();

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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'parent_id' => $this->parent_id,
            'subscription_type' => $this->subscription_type,
            'campus_id' => $this->campus_id,
            'total_item' => $this->total_item,
            'total_item_price' => $this->total_item_price,
            'gst_percentage' => $this->gst_percentage,
            'gst_amount' => $this->gst_amount,
            'other_charges' => $this->other_charges,
            'coupon_applied_id' => $this->coupon_applied_id,
            'coupon_discount' => $this->coupon_discount,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'coupon_code', $this->coupon_code])
            ->andFilterWhere(['like', 'tutorix_user_access_token', $this->tutorix_user_access_token])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = TutorixSubscriptions::find();

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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'parent_id' => $this->parent_id,
            'subscription_type' => $this->subscription_type,
            'campus_id' => $this->campus_id,
            'total_item' => $this->total_item,
            'total_item_price' => $this->total_item_price,
            'gst_percentage' => $this->gst_percentage,
            'gst_amount' => $this->gst_amount,
            'other_charges' => $this->other_charges,
            'coupon_applied_id' => $this->coupon_applied_id,
            'coupon_discount' => $this->coupon_discount,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'coupon_code', $this->coupon_code])
            ->andFilterWhere(['like', 'tutorix_user_access_token', $this->tutorix_user_access_token])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

        return $dataProvider;
    }



    
    public function institutesSearch($params)
    {
        $query = TutorixSubscriptions::find();

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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'parent_id' => $this->parent_id,
            'subscription_type' => $this->subscription_type,
            'campus_id' => $this->campus_id,
            'total_item' => $this->total_item,
            'total_item_price' => $this->total_item_price,
            'gst_percentage' => $this->gst_percentage,
            'gst_amount' => $this->gst_amount,
            'other_charges' => $this->other_charges,
            'coupon_applied_id' => $this->coupon_applied_id,
            'coupon_discount' => $this->coupon_discount,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'coupon_code', $this->coupon_code])
            ->andFilterWhere(['like', 'tutorix_user_access_token', $this->tutorix_user_access_token])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = TutorixSubscriptions::find();

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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'parent_id' => $this->parent_id,
            'subscription_type' => $this->subscription_type,
            'campus_id' => $this->campus_id,
            'total_item' => $this->total_item,
            'total_item_price' => $this->total_item_price,
            'gst_percentage' => $this->gst_percentage,
            'gst_amount' => $this->gst_amount,
            'other_charges' => $this->other_charges,
            'coupon_applied_id' => $this->coupon_applied_id,
            'coupon_discount' => $this->coupon_discount,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'coupon_code', $this->coupon_code])
            ->andFilterWhere(['like', 'tutorix_user_access_token', $this->tutorix_user_access_token])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

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
        $query = TutorixSubscriptions::find()
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
            'user_id' => $this->user_id,
            'student_id' => $this->student_id,
            'parent_id' => $this->parent_id,
            'subscription_type' => $this->subscription_type,
            'campus_id' => $this->campus_id,
            'total_item' => $this->total_item,
            'total_item_price' => $this->total_item_price,
            'gst_percentage' => $this->gst_percentage,
            'gst_amount' => $this->gst_amount,
            'other_charges' => $this->other_charges,
            'coupon_applied_id' => $this->coupon_applied_id,
            'coupon_discount' => $this->coupon_discount,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'coupon_code', $this->coupon_code])
            ->andFilterWhere(['like', 'tutorix_user_access_token', $this->tutorix_user_access_token])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

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
