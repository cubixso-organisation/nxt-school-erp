<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;
use yii\web\NotFoundHttpException;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
	public $full_name;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'status'], 'integer'],
			[['username', 'email', 'full_name', 'contact_no', 'first_name', 'user_role',/*'registered',*/], 'safe'],
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

	public function search($params, $role = '', $user_id = null, $create_user_id = '', $campus_id = '')
	{

		$query = User::find();


		if (!empty($create_user_id)) {
			$query->where(['create_user_id' => $create_user_id]);
		}

		if (!empty($role)) {
			$query->andWhere(['IN', 'user_role', $role]);
		}




		if (!empty($campus_id)) {
			$query->andWhere(['campus_id' => $campus_id]);
		}



		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'attributes' => [
					'id',
					'status',
					'username',
					'email',
					'created_at',
					'contact_no',
					'user_role',
					//'registered',
					'full_name' => [
						'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
						'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
					],
				],
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id'         => $this->id,
			'status'     => $this->status,
			'created_at' => $this->created_at,
		]);

		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'first_name', $this->first_name])
			->andFilterWhere(['like', 'contact_no', $this->contact_no])
			->andFilterWhere(['like', 'user_role', $this->user_role])
			//->andFilterWhere(['like', 'registered', $this->registered])
			->andFilterWhere(['like', 'concat(first_name, " " , last_name) ', $this->full_name]);


		return $dataProvider;
	}





	public function campusSubAdminSearch($params, $role = '', $user_id = null, $create_user_id = '')
	{

		$query = User::find();


		$query->andWhere(['campus_id' => (new User())->getCampusesByUser(Yii::$app->user->identity->id)]);

		if (!empty($create_user_id)) {
			$query->where(['create_user_id' => $create_user_id]);
		}

		if (!empty($role)) {
			$query->andWhere(['IN', 'user_role', $role]);
		}



		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'attributes' => [
					'id',
					'status',
					'username',
					'email',
					'created_at',
					'contact_no',
					'user_role',
					//'registered',
					'full_name' => [
						'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
						'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
					],
				],
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id'         => $this->id,
			'status'     => $this->status,
			'created_at' => $this->created_at,
		]);

		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'first_name', $this->first_name])
			->andFilterWhere(['like', 'contact_no', $this->contact_no])
			->andFilterWhere(['like', 'user_role', $this->user_role])
			//->andFilterWhere(['like', 'registered', $this->registered])
			->andFilterWhere(['like', 'concat(first_name, " " , last_name) ', $this->full_name]);


		return $dataProvider;
	}






	public function usersearch($params, $user_id = null)
	{
		if ($user_id != null) {
			$query = User::find()->where(['referal_id' => $user_id]);
		} else {
			$query = User::find()->where(['!=', 'id', \Yii::$app->user->id]);
		}
		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'attributes' => [
					'id',
					'status',
					'username',
					'email',
					'created_at',
					'full_name' => [
						'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
						'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
					],
				],
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id'         => $this->id,
			'status'     => $this->status,
			'created_at' => $this->created_at,
		]);

		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'first_name', $this->first_name])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'concat(first_name, " " , last_name) ', $this->full_name]);
		//$cmd =  $query->createCommand()->getRawSql();
		//var_dump($cmd); exit;

		return $dataProvider;
	}
	public function teacherSearch($params, $role = '', $flag = false)
	{


		if ($flag) {
			$query = User::find()->where(['create_user_id' => \Yii::$app->user->id]);
		} elseif (!empty($role)) {
			$teacherDetail = TeacherDetails::find()->where(['campus_id' => (new User())->getCampusId()])->one();

			if ($teacherDetail !== null) {
				$query = User::find()
					->innerJoin('teacher_details', 'user.id = teacher_details.user_id')
					->where(['user_role' => User::role_teacher])
					->andWhere(['teacher_details.campus_id' => $teacherDetail->campus_id]);
			} else {
				// Handle the case where teacher details are not found.
				$query = User::find()->where(['id' => null]);
			}
		} else {
			$query = User::find();
		}

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'id' => SORT_DESC,
				]
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'status' => $this->status,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at
		]);

		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'first_name', $this->first_name])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'contact_no', $this->contact_no]);

		return $dataProvider;
	}
	public function parentSearch($params, $role = '', $flag = false)
	{


		if ($flag) {
			$query = User::find()->where(['create_user_id' => \Yii::$app->user->id]);
		} elseif (!empty($role)) {

			$query = User::find()
				->innerJoinWith('parentDetail')
				->innerJoinWith('parentDetail.parentHasCampuses phc')
				->where(['phc.campus_id' => (new User())->getCampusId()]);
		} else {
			$query = User::find();
		}

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'id' => SORT_DESC,
				]
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'status' => $this->status,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at
		]);

		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'first_name', $this->first_name])
			->andFilterWhere(['like', 'contact_no', $this->contact_no]);

		return $dataProvider;
	}
}
