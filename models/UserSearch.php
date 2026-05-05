<?php

namespace app\models;

use app\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[
				[
					'id',
					'status',
					'created_at',
					'updated_at'
				],
				'integer'
			],
			[
				[
					'username',
					'email',
					'contact_no'
				],
				'safe'
			]
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
	public function search($params, $role = '', $role1 = '', $flag = false)
	{


		if ($flag) {
			$query = User::find()->where(['create_user_id' => \Yii::$app->user->id]);
		} else if (!empty($role) || !empty($role1)) {
			$query = User::find()->where(['or', ['user_role' => $role], ['user_role' => $role1]]);
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
			->andFilterWhere(['like', 'contact_no', $this->contact_no]);

		return $dataProvider;
	}
	




	
	
	public function warden($params, $role = '', $flag = false)
	{


		if ($flag) {
			$query = User::find()->where(['create_user_id' => \Yii::$app->user->id]);
		} else if (!empty($role)) {
			$query = User::find()->where(['or', ['user_role' => User::ROLE_CHEF_WARDEN], ['user_role' => User::ROLE_WARDEN]])->andWhere(['campus_id' => (new User())->getCampusId()]);
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
			->andFilterWhere(['like', 'contact_no', $this->contact_no]);

		return $dataProvider;
	}



	public function librarian($params, $role = '', $flag = false)
	{


		if ($flag) {
			$query = User::find()->where(['campus_id' => $this->getCampusId()])->andWhere(['user_role' => User::ROLE_LIBRARIAN]);
		} else if (!empty($role)) {
			$query = User::find()->where(['user_role' => $role])->andWhere(['campus_id' => $this->getCampusId()]);
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
			->andFilterWhere(['like', 'contact_no', $this->contact_no]);

		return $dataProvider;
	}
}
