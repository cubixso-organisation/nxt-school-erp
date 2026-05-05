<?php

namespace app\modules\inventory\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\inventory\models\AddItemStock;

/**
 * app\modules\inventory\models\search\AddItemStockSearch represents the model behind the search form about `app\modules\inventory\models\AddItemStock`.
 */
class AddItemStockSearch extends AddItemStock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_category_id', 'item_supplier_list_id', 'item_store_id', 'inventory_items_id', 'quantity', 'status', 'created_user_id', 'updated_user_id'], 'integer'],
            [['purchase_price'], 'number'],
            [['date', 'attach_document', 'description', 'created_on', 'updated_on'], 'safe'],
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
        $query = AddItemStock::find();

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
            'item_category_id' => $this->item_category_id,
            'item_supplier_list_id' => $this->item_supplier_list_id,
            'item_store_id' => $this->item_store_id,
            'inventory_items_id' => $this->inventory_items_id,
            'quantity' => $this->quantity,
            'purchase_price' => $this->purchase_price,
            'date' => $this->date,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'attach_document', $this->attach_document])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = AddItemStock::find()
            ->joinWith('itemCategory') // Assuming 'itemCategory' is the relation name in the AddItemStock model
            ->where(['item_category.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'item_category_id' => $this->item_category_id,
            'item_supplier_list_id' => $this->item_supplier_list_id,
            'item_store_id' => $this->item_store_id,
            'inventory_items_id' => $this->inventory_items_id,
            'quantity' => $this->quantity,
            'purchase_price' => $this->purchase_price,
            'date' => $this->date,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'attach_document', $this->attach_document])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }




    public function institutesSearch($params)
    {
        $query = AddItemStock::find();

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
            'item_category_id' => $this->item_category_id,
            'item_supplier_list_id' => $this->item_supplier_list_id,
            'item_store_id' => $this->item_store_id,
            'inventory_items_id' => $this->inventory_items_id,
            'quantity' => $this->quantity,
            'purchase_price' => $this->purchase_price,
            'date' => $this->date,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'attach_document', $this->attach_document])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = AddItemStock::find();

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
            'item_category_id' => $this->item_category_id,
            'item_supplier_list_id' => $this->item_supplier_list_id,
            'item_store_id' => $this->item_store_id,
            'inventory_items_id' => $this->inventory_items_id,
            'quantity' => $this->quantity,
            'purchase_price' => $this->purchase_price,
            'date' => $this->date,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'attach_document', $this->attach_document])
            ->andFilterWhere(['like', 'description', $this->description]);

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
        $query = AddItemStock::find()
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
            'item_category_id' => $this->item_category_id,
            'item_supplier_list_id' => $this->item_supplier_list_id,
            'item_store_id' => $this->item_store_id,
            'inventory_items_id' => $this->inventory_items_id,
            'quantity' => $this->quantity,
            'purchase_price' => $this->purchase_price,
            'date' => $this->date,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'attach_document', $this->attach_document])
            ->andFilterWhere(['like', 'description', $this->description]);

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
