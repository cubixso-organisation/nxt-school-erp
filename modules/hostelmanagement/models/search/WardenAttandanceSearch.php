<?php

namespace app\modules\hostelmanagement\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hostelmanagement\models\WardenAttandance;

/**
 * app\modules\hostelmanagement\models\search\WardenAttandanceSearch represents the model behind the search form about `app\modules\hostelmanagement\models\WardenAttandance`.
 */
class WardenAttandanceSearch extends WardenAttandance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campus_id', 'hostel_id', 'warden_id', 'attandance', 'attandance_by', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['date', 'created_on', 'updated_on'], 'safe'],
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
        $campusId = (new User())->getCampusId();
        $campusIdByUser = (new User())->getUserCampusId();
        $query = WardenAttandance::find()->where(['or', ['campus_id' => $campusId], ['campus_id' => $campusIdByUser]]);

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
            'hostel_id' => $this->hostel_id,
            'warden_id' => $this->warden_id,
            'attandance' => $this->attandance,
            'date' => $this->date,
            'attandance_by' => $this->attandance_by,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }



    public function campusAdminSearch($params, $day = '')
    {
        $campusId = (new User())->getCampusId();
        $campusIdByUser = (new User())->getUserCampusId();

        // Set the timezone to 'Asia/Kolkata'
        date_default_timezone_set('Asia/Kolkata');

        if (!empty($day)) {
            $todayDate = date('Y-m-d');

            $query = WardenAttandance::find()
                ->where(['or', ['campus_id' => $campusId], ['campus_id' => $campusIdByUser]])
                ->andWhere(['DATE(CONVERT_TZ(`date`, "+00:00", "+05:30"))' => $todayDate]);
        } else {
            $query = WardenAttandance::find()->where(['or', ['campus_id' => $campusId], ['campus_id' => $campusIdByUser]]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        // Add the date filter if it is set
        if (!empty($params["WardenAttandanceSearch"]["date"])) {
            $formattedDate = date('Y-m-d', strtotime($params["WardenAttandanceSearch"]["date"]));
            $query->andWhere(['DATE(CONVERT_TZ(`date`, "+00:00", "+05:30"))' => $formattedDate]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'campus_id' => $this->campus_id,
            'hostel_id' => $this->hostel_id,
            'warden_id' => $this->warden_id,
            'attandance' => $this->attandance,
            'attandance_by' => $this->attandance_by,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);
        // print_r($query->createCommand()->getRawSql());
        // exit;
        return $dataProvider;
    }




    public function institutesSearch($params)
    {
        $campusId = (new User())->getCampusId();
        $campusIdByUser = (new User())->getUserCampusId();
        $query = WardenAttandance::find()->where(['or', ['campus_id' => $campusId], ['campus_id' => $campusIdByUser]]);


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
            'hostel_id' => $this->hostel_id,
            'warden_id' => $this->warden_id,
            'attandance' => $this->attandance,
            'date' => $this->date,
            'attandance_by' => $this->attandance_by,
            'status' => $this->status,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $campusId = (new User())->getCampusId();
        $campusIdByUser = (new User())->getUserCampusId();
        $query = WardenAttandance::find()->where(['or', ['campus_id' => $campusId], ['campus_id' => $campusIdByUser]]);


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
            'hostel_id' => $this->hostel_id,
            'warden_id' => $this->warden_id,
            'attandance' => $this->attandance,
            'date' => $this->date,
            'attandance_by' => $this->attandance_by,
            'status' => $this->status,
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
        $query = WardenAttandance::find()
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
            'campus_id' => $this->campus_id,
            'hostel_id' => $this->hostel_id,
            'warden_id' => $this->warden_id,
            'attandance' => $this->attandance,
            'date' => $this->date,
            'attandance_by' => $this->attandance_by,
            'status' => $this->status,
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


    public function actionStatusChange()
    {
        $post = \Yii::$app->request->post();

        if (!empty($post['id'])) {

            $hostellerAttandence = WardenAttandance::find()->where(['id' => $post['id']])->one();
            if (!empty($hostellerAttandence)) {
                $hostellerAttandence->attandance = (int)$post['val'];
                if ($hostellerAttandence->save(false)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}
