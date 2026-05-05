<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TutorixCoupon;

/**
 * app\modules\admin\models\search\TutorixCouponSearch represents the model behind the search form about `app\modules\admin\models\TutorixCoupon`.
 */
 class TutorixCouponSearch extends TutorixCoupon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'coupon_type', 'min_cart_item', 'max_cart_item', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['code', 'created_on', 'updated_on'], 'safe'],
            [['coupon_discount', 'max_discount', 'min_cart_value'], 'number'],
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
        $query = TutorixCoupon::find()->where(['status'=>TutorixCoupon::STATUS_ACTIVE]);
    
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
    
        // Exact matches for other attributes
        $query->andFilterWhere([
            'id' => $this->id,
            'coupon_type' => $this->coupon_type,
            'coupon_discount' => $this->coupon_discount,
            'max_discount' => $this->max_discount,
            'min_cart_item' => $this->min_cart_item,
            'max_cart_item' => $this->max_cart_item,
            'min_cart_value' => $this->min_cart_value,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);
    
        // Filtering logic for start_date and end_date
        if ($this->start_date && $this->end_date) {
            // Filter for an exact range when both dates are selected
            $query->andFilterWhere(['>=', 'start_date', $this->start_date])
                  ->andFilterWhere(['<=', 'end_date', $this->end_date]);
        } elseif ($this->start_date) {
            // Filter only by start_date if only start_date is selected
            $query->andFilterWhere(['>=', 'start_date', $this->start_date]);
        } elseif ($this->end_date) {
            // Filter only by end_date if only end_date is selected
            $query->andFilterWhere(['<=', 'end_date', $this->end_date]);
        }
    
        // Filter for 'code' attribute using 'like' condition
        $query->andFilterWhere(['like', 'code', $this->code]);
    
        return $dataProvider;
    }
    




    public function campusAdminSearch($params)
    {
        $query = TutorixCoupon::find()->where(['status'=>TutorixCoupon::STATUS_ACTIVE]);

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
            'coupon_type' => $this->coupon_type,
            'coupon_discount' => $this->coupon_discount,
            'max_discount' => $this->max_discount,
            'min_cart_item' => $this->min_cart_item,
            'max_cart_item' => $this->max_cart_item,
            'min_cart_value' => $this->min_cart_value,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }



    
    public function institutesSearch($params)
    {
        $query = TutorixCoupon::find()->where(['status'=>TutorixCoupon::STATUS_ACTIVE]);

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
            'coupon_type' => $this->coupon_type,
            'coupon_discount' => $this->coupon_discount,
            'max_discount' => $this->max_discount,
            'min_cart_item' => $this->min_cart_item,
            'max_cart_item' => $this->max_cart_item,
            'min_cart_value' => $this->min_cart_value,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = TutorixCoupon::find()->where(['status'=>TutorixCoupon::STATUS_ACTIVE]);

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
            'coupon_type' => $this->coupon_type,
            'coupon_discount' => $this->coupon_discount,
            'max_discount' => $this->max_discount,
            'min_cart_item' => $this->min_cart_item,
            'max_cart_item' => $this->max_cart_item,
            'min_cart_value' => $this->min_cart_value,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

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
        $query = TutorixCoupon::find()
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
            'coupon_type' => $this->coupon_type,
            'coupon_discount' => $this->coupon_discount,
            'max_discount' => $this->max_discount,
            'min_cart_item' => $this->min_cart_item,
            'max_cart_item' => $this->max_cart_item,
            'min_cart_value' => $this->min_cart_value,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

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
