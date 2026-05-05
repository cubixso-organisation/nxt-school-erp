<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\AuthSession;

/**
 * app\modules\admin\models\AuthSessionSearch represents the model behind the search form about `app\modules\admin\models\AuthSession`.
 */
class AuthSessionSearch extends AuthSession
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'create_user_id'], 'integer'],
            [['auth_code', 'device_token', 'created_on', 'updated_on'], 'safe'],
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
         $query = AuthSession::find()
             ->alias('as') // Alias for the AuthSession table
             ->joinWith(['createUser u']) // Join with the User model (assumes relation is defined)
             ->leftJoin('parent_details pd', 'pd.user_id = u.id') // Join with ParentDetails table
             ->leftJoin('parent_has_campus phc', 'phc.patient_id = pd.id') // Join with ParentHasCampus table
             ->leftJoin('campus c', 'c.id = phc.campus_id') // Join with Campus table
             ->leftJoin('student_details sd', 'sd.parent_id = pd.id') // Join with StudentDetails table
             ->leftJoin('student_class sc', 'sc.id = sd.student_class_id') // Join with StudentClass table
             ->select([
                 'as.*',
                 'pd.name_of_the_father AS parent_name',
                 'pd.contact_number AS contact_no',
                 'phc.campus_id AS campus_id',
                 'c.name_of_the_educational_Institution AS campus_name',
                 'GROUP_CONCAT(DISTINCT sd.student_name ORDER BY sd.student_name) AS student_names', // Concatenate unique student names
                 'GROUP_CONCAT(DISTINCT sc.title ORDER BY sc.title) AS student_classes', // Concatenate unique class titles
             ])
             ->where(['u.user_role' => 'parent']) // Filter to only include parents
             ->groupBy(['as.id']) // Group by AuthSession ID
             ->orderBy(['as.created_on' => SORT_DESC]); // Order by created_on in descending order
     
         $dataProvider = new ActiveDataProvider([
             'query' => $query,
         ]);
     
         $this->load($params);
     
         if (!$this->validate()) {
             $query->where('0=1'); // Return no data if validation fails
             return $dataProvider;
         }
     
         // Additional filtering
         $query->andFilterWhere(['like', 'pd.name_of_the_father', $this->parent_name])
             ->andFilterWhere(['like', 'pd.contact_number', $this->contact_no])
             ->andFilterWhere(['phc.campus_id' => $this->campus_id])
             ->andFilterWhere(['like', 'c.name_of_the_educational_Institution', $this->campus_name])
             ->andFilterWhere(['like', 'sd.student_name', $this->student_names]) // Filter by student name if required
             ->andFilterWhere(['like', 'sc.title', $this->student_classes]);
     
         return $dataProvider;
     }
     
}
