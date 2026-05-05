<?php

namespace app\modules\admin\models\search;

use app\modules\admin\models\base\StudentDetails;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\TutorixSubscriptionItems;

/**
 * app\modules\admin\models\search\TutorixSubscriptionItemsSearch represents the model behind the search form about `app\modules\admin\models\TutorixSubscriptionItems`.
 */
 class TutorixSubscriptionItemsSearch extends TutorixSubscriptionItems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'subscription_id', 'student_id', 'class_id', 'parent_id', 'class_type', 'is_free_trail', 'payment_status', 'year_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['item_price'], 'number'],
            [['start_date', 'expiry_date', 'tutorix_user_access_token', 'unique_id', 'created_on', 'updated_on'], 'safe'],
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
        $query = TutorixSubscriptionItems::find()
            ->alias('tsi')
            ->joinWith(['student sd', 'student.campus c']) // Ensure relations exist
            ->select([
                'tsi.*', // All columns from TutorixSubscriptionItems table
                'c.name_of_the_educational_Institution AS campus_name', // Campus name alias
            ]);
    
        // Create the data provider with pagination and sorting
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'campus_name' => [
                        'asc' => ['c.name_of_the_educational_Institution' => SORT_ASC],
                        'desc' => ['c.name_of_the_educational_Institution' => SORT_DESC],
                    ],
                    'created_on',
                ],
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 30, // Set your desired page size
            ],
        ]);
    
        // Load the search parameters
        $this->load($params);
    
        if (!$this->validate()) {
            return [$dataProvider, 0]; // Return the dataProvider and zero if validation fails
        }
    
        // Apply filters to the query
        $query->andFilterWhere([
            'tsi.id' => $this->id,
            'tsi.subscription_id' => $this->subscription_id,
            'tsi.student_id' => $this->student_id,
            'tsi.class_id' => $this->class_id,
            'tsi.parent_id' => $this->parent_id,
            'tsi.class_type' => $this->class_type,
            'tsi.start_date' => $this->start_date,
            'tsi.expiry_date' => $this->expiry_date,
            'tsi.is_free_trail' => $this->is_free_trail,
            'tsi.payment_status' => $this->payment_status,
            'tsi.year_id' => $this->year_id,
            'tsi.status' => $this->status,
            'tsi.created_on' => $this->created_on,
            'tsi.updated_on' => $this->updated_on,
            'tsi.create_user_id' => $this->create_user_id,
            'tsi.update_user_id' => $this->update_user_id,
        ]);
    
        $query->andFilterWhere(['like', 'tsi.tutorix_user_access_token', $this->tutorix_user_access_token])
            ->andFilterWhere(['like', 'tsi.unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'c.name_of_the_educational_Institution', $this->campus_name]);
    
        // Execute the paginated query and calculate the sum for the current page only
        $itemsForCurrentPage = $dataProvider->getModels();
        $totalItemPriceForCurrentPage = array_sum(array_column($itemsForCurrentPage, 'item_price'));
    
        return [$dataProvider, $totalItemPriceForCurrentPage]; // Return both the dataProvider and total item price for the current page
    }
    

    public function activesearch($params)
    {
        $query = TutorixSubscriptionItems::find()
            ->alias('tsi')
            ->joinWith(['student sd', 'student.campus c']) // Ensure relations exist
            ->select([
                'tsi.*', // All columns from TutorixSubscriptionItems table
                'c.name_of_the_educational_Institution AS campus_name', // Campus name alias
            ])
            ->where(['tsi.status' => TutorixSubscriptionItems::STATUS_ACTIVE])
            ->andWhere(['tsi.payment_status' => TutorixSubscriptionItems::PAYMENT_PAID]);
    
        // Create the data provider with pagination and sorting
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'campus_name' => [
                        'asc' => ['c.name_of_the_educational_Institution' => SORT_ASC],
                        'desc' => ['c.name_of_the_educational_Institution' => SORT_DESC],
                    ],
                    'created_on',
                ],
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 30, // Set your desired page size
            ],
        ]);
    
        // Load the search parameters
        $this->load($params);
    
        if (!$this->validate()) {
            return [$dataProvider, 0]; // Return the dataProvider and zero if validation fails
        }
    
        // Apply filters to the query
        $query->andFilterWhere([
            'tsi.id' => $this->id,
            'tsi.subscription_id' => $this->subscription_id,
            'tsi.student_id' => $this->student_id,
            'tsi.class_id' => $this->class_id,
            'tsi.parent_id' => $this->parent_id,
            'tsi.class_type' => $this->class_type,
            'tsi.start_date' => $this->start_date,
            'tsi.expiry_date' => $this->expiry_date,
            'tsi.is_free_trail' => $this->is_free_trail,
            'tsi.payment_status' => $this->payment_status,
            'tsi.year_id' => $this->year_id,
            'tsi.status' => $this->status,
            'tsi.created_on' => $this->created_on,
            'tsi.updated_on' => $this->updated_on,
            'tsi.create_user_id' => $this->create_user_id,
            'tsi.update_user_id' => $this->update_user_id,
        ]);
    
        $query->andFilterWhere(['like', 'tsi.tutorix_user_access_token', $this->tutorix_user_access_token])
            ->andFilterWhere(['like', 'tsi.unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'c.name_of_the_educational_Institution', $this->campus_name]);
    
        // Execute the paginated query and calculate the sum for the current page only
        $itemsForCurrentPage = $dataProvider->getModels();
        $totalItemPriceForCurrentPage = array_sum(array_column($itemsForCurrentPage, 'item_price'));
    
        return [$dataProvider, $totalItemPriceForCurrentPage]; // Return both the dataProvider and total item price for the current page
    }
    
    public function freesearch($params)
{
    $query = TutorixSubscriptionItems::find()
        ->alias('tsi')
        ->joinWith(['student sd', 'student.campus c']) // Ensure relations exist
        ->select([
            'tsi.*', // All columns from TutorixSubscriptionItems table
            'c.name_of_the_educational_Institution AS campus_name', // Campus name alias
        ])
        ->where(['tsi.is_free_trail' => TutorixSubscriptionItems::IS_FREE_TRAIL]);

    // Create the data provider with pagination and sorting
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'attributes' => [
                'campus_name' => [
                    'asc' => ['c.name_of_the_educational_Institution' => SORT_ASC],
                    'desc' => ['c.name_of_the_educational_Institution' => SORT_DESC],
                ],
                'created_on',
            ],
            'defaultOrder' => [
                'created_on' => SORT_DESC,
            ],
        ],
        'pagination' => [
            'pageSize' => 30, // Set your desired page size
        ],
    ]);

    // Load the search parameters
    $this->load($params);

    if (!$this->validate()) {
        return [$dataProvider, 0]; // Return the dataProvider and zero if validation fails
    }

    // Apply filters to the query
    $query->andFilterWhere([
        'tsi.id' => $this->id,
        'tsi.subscription_id' => $this->subscription_id,
        'tsi.student_id' => $this->student_id,
        'tsi.class_id' => $this->class_id,
        'tsi.parent_id' => $this->parent_id,
        'tsi.class_type' => $this->class_type,
        'tsi.start_date' => $this->start_date,
        'tsi.expiry_date' => $this->expiry_date,
        'tsi.is_free_trail' => $this->is_free_trail,
        'tsi.payment_status' => $this->payment_status,
        'tsi.year_id' => $this->year_id,
        'tsi.status' => $this->status,
        'tsi.created_on' => $this->created_on,
        'tsi.updated_on' => $this->updated_on,
        'tsi.create_user_id' => $this->create_user_id,
        'tsi.update_user_id' => $this->update_user_id,
    ]);

    $query->andFilterWhere(['like', 'tsi.tutorix_user_access_token', $this->tutorix_user_access_token])
        ->andFilterWhere(['like', 'tsi.unique_id', $this->unique_id])
        ->andFilterWhere(['like', 'c.name_of_the_educational_Institution', $this->campus_name]);

    // Execute the paginated query and calculate the sum for the current page only
    $itemsForCurrentPage = $dataProvider->getModels();
    $totalItemPriceForCurrentPage = array_sum(array_column($itemsForCurrentPage, 'item_price'));

    return [$dataProvider, $totalItemPriceForCurrentPage]; // Return both the dataProvider and total item price for the current page
}

    public function paidsearch($params)
{
    // Base query with joins
    $query = TutorixSubscriptionItems::find()
        ->alias('tsi')
        ->joinWith(['student sd', 'student.campus c']) // Ensure relations exist
        ->select([
            'tsi.*', // All columns from TutorixSubscriptionItems table
            'c.name_of_the_educational_Institution AS campus_name', // Campus name alias
        ])
        ->where([
            'tsi.is_free_trail' => TutorixSubscriptionItems::IS_ACTIVATION,
            'tsi.payment_status' => TutorixSubscriptionItems::PAYMENT_PAID,
        ]);

    // Create data provider
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'attributes' => [
                'campus_name' => [
                    'asc' => ['c.name_of_the_educational_Institution' => SORT_ASC],
                    'desc' => ['c.name_of_the_educational_Institution' => SORT_DESC],
                ],
                'created_on',
            ],
            'defaultOrder' => ['created_on' => SORT_DESC],
        ],
        'pagination' => [
            'pageSize' => 30,
        ],
    ]);

    // Load and validate parameters
    $this->load($params);

    if (!$this->validate()) {
        $query->where('0=1'); // No records if validation fails
        return [$dataProvider, 0];
    }

    // Apply filters
    $query->andFilterWhere([
        'tsi.id' => $this->id,
        'tsi.subscription_id' => $this->subscription_id,
        'tsi.student_id' => $this->student_id,
    ]);

    $query->andFilterWhere(['like', 'c.name_of_the_educational_Institution', $this->campus_name]);

    // Calculate the total item price for the current page
    $itemsForCurrentPage = $dataProvider->getModels();
    $totalItemPriceForCurrentPage = array_sum(array_column($itemsForCurrentPage, 'item_price'));

    return [$dataProvider, $totalItemPriceForCurrentPage];
}

    

public function expairysearch($params)
{
    $query = TutorixSubscriptionItems::find()
        ->alias('tsi')
        ->joinWith(['studentDetails sd', 'studentDetails.campus c']) // Ensure relations exist
        ->select([
            'tsi.*', // All columns from TutorixSubscriptionItems table
            'c.name_of_the_educational_Institution AS campus_name', // Campus name alias
        ])
        ->where(['tsi.status' => TutorixSubscriptionItems::STATUS_DELETE]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'attributes' => [
                'campus_name' => [
                    'asc' => ['c.name_of_the_educational_Institution' => SORT_ASC],
                    'desc' => ['c.name_of_the_educational_Institution' => SORT_DESC],
                ],
                'created_on',
            ],
            'defaultOrder' => [
                'created_on' => SORT_DESC,
            ],
        ],
        'pagination' => [
            'pageSize' => 30,
        ],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return [$dataProvider, 0];
    }

    $query->andFilterWhere([
        'tsi.id' => $this->id,
        'tsi.subscription_id' => $this->subscription_id,
        'tsi.student_id' => $this->student_id,
        'tsi.class_id' => $this->class_id,
        'tsi.parent_id' => $this->parent_id,
        'tsi.class_type' => $this->class_type,
        'tsi.start_date' => $this->start_date,
        'tsi.expiry_date' => $this->expiry_date,
        'tsi.is_free_trail' => $this->is_free_trail,
        'tsi.payment_status' => $this->payment_status,
        'tsi.year_id' => $this->year_id,
        'tsi.status' => $this->status,
        'tsi.created_on' => $this->created_on,
        'tsi.updated_on' => $this->updated_on,
        'tsi.create_user_id' => $this->create_user_id,
        'tsi.update_user_id' => $this->update_user_id,
    ])
        ->andFilterWhere(['like', 'tsi.tutorix_user_access_token', $this->tutorix_user_access_token])
        ->andFilterWhere(['like', 'tsi.unique_id', $this->unique_id])
        ->andFilterWhere(['like', 'c.name_of_the_educational_Institution', $this->campus_name]);

    $itemsForCurrentPage = $dataProvider->getModels();
    $totalItemPriceForCurrentPage = array_sum(array_column($itemsForCurrentPage, 'item_price'));

    return [$dataProvider, $totalItemPriceForCurrentPage];
}

public function pendingsearch($params)
{
    $query = TutorixSubscriptionItems::find()
        ->alias('tsi')
        ->joinWith(['studentDetails sd', 'studentDetails.campus c']) // Ensure relations exist
        ->select([
            'tsi.*', // All columns from TutorixSubscriptionItems table
            'c.name_of_the_educational_Institution AS campus_name', // Campus name alias
        ])
        ->where([
            'tsi.status' => TutorixSubscriptionItems::STATUS_INACTIVE,
            'tsi.payment_status' => TutorixSubscriptionItems::PAYMENT_PENDING,
        ]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'attributes' => [
                'campus_name' => [
                    'asc' => ['c.name_of_the_educational_Institution' => SORT_ASC],
                    'desc' => ['c.name_of_the_educational_Institution' => SORT_DESC],
                ],
                'created_on',
            ],
            'defaultOrder' => [
                'created_on' => SORT_DESC,
            ],
        ],
        'pagination' => [
            'pageSize' => 30,
        ],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return [$dataProvider, 0];
    }

    $query->andFilterWhere([
        'tsi.id' => $this->id,
        'tsi.subscription_id' => $this->subscription_id,
        'tsi.student_id' => $this->student_id,
        'tsi.class_id' => $this->class_id,
        'tsi.parent_id' => $this->parent_id,
        'tsi.class_type' => $this->class_type,
        'tsi.start_date' => $this->start_date,
        'tsi.expiry_date' => $this->expiry_date,
        'tsi.is_free_trail' => $this->is_free_trail,
        'tsi.payment_status' => $this->payment_status,
        'tsi.year_id' => $this->year_id,
        'tsi.status' => $this->status,
        'tsi.created_on' => $this->created_on,
        'tsi.updated_on' => $this->updated_on,
        'tsi.create_user_id' => $this->create_user_id,
        'tsi.update_user_id' => $this->update_user_id,
    ])
        ->andFilterWhere(['like', 'tsi.tutorix_user_access_token', $this->tutorix_user_access_token])
        ->andFilterWhere(['like', 'tsi.unique_id', $this->unique_id])
        ->andFilterWhere(['like', 'c.name_of_the_educational_Institution', $this->campus_name]);

    $itemsForCurrentPage = $dataProvider->getModels();
    $totalItemPriceForCurrentPage = array_sum(array_column($itemsForCurrentPage, 'item_price'));

    return [$dataProvider, $totalItemPriceForCurrentPage];
}


    



    public function campusAdminSearch($params)
    {
        $query = TutorixSubscriptionItems::find();

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
            'subscription_id' => $this->subscription_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'parent_id' => $this->parent_id,
            'class_type' => $this->class_type,
            'item_price' => $this->item_price,
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
            'is_free_trail' => $this->is_free_trail,
            'payment_status' => $this->payment_status,
            'year_id' => $this->year_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'tutorix_user_access_token', $this->tutorix_user_access_token])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

        return $dataProvider;
    }



    
    public function institutesSearch($params)
    {
        $query = TutorixSubscriptionItems::find();

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
            'subscription_id' => $this->subscription_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'parent_id' => $this->parent_id,
            'class_type' => $this->class_type,
            'item_price' => $this->item_price,
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
            'is_free_trail' => $this->is_free_trail,
            'payment_status' => $this->payment_status,
            'year_id' => $this->year_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'tutorix_user_access_token', $this->tutorix_user_access_token])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = TutorixSubscriptionItems::find();

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
            'subscription_id' => $this->subscription_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'parent_id' => $this->parent_id,
            'class_type' => $this->class_type,
            'item_price' => $this->item_price,
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
            'is_free_trail' => $this->is_free_trail,
            'payment_status' => $this->payment_status,
            'year_id' => $this->year_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'tutorix_user_access_token', $this->tutorix_user_access_token])
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
        $query = TutorixSubscriptionItems::find()
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
            'subscription_id' => $this->subscription_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'parent_id' => $this->parent_id,
            'class_type' => $this->class_type,
            'item_price' => $this->item_price,
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
            'is_free_trail' => $this->is_free_trail,
            'payment_status' => $this->payment_status,
            'year_id' => $this->year_id,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'tutorix_user_access_token', $this->tutorix_user_access_token])
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
