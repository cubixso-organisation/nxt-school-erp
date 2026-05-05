<?php

namespace app\modules\admin\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\ClassTeacher;
use app\modules\admin\models\User as ModelsUser;

/**
 * app\modules\admin\models\search\ClassTeacherSearch represents the model behind the search form about `app\modules\admin\models\ClassTeacher`.
 */
class ClassTeacherSearch extends ClassTeacher
{
    /** 
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'class_id', 'section_id', 'teacher_details_id', 'academic_year_id', 'status', 'create_user_id', 'update_user_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
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
        $query = ClassTeacher::find();

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
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'teacher_details_id' => $this->teacher_details_id,
            'academic_year_id' => $this->academic_year_id,
            'status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
        ]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = ClassTeacher::find()->innerJoinWith('teacherDetails as td')
            ->where(['td.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'class_teacher.class_id' => $this->class_id,
            'class_teacher.section_id' => $this->section_id,
            'teacher_details_id' => $this->teacher_details_id,
            'academic_year_id' => $this->academic_year_id,
            'class_teacher.status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
        ]);

        // Debugging the raw SQL query to ensure filters are applied correctly
        Yii::info($query->createCommand()->getRawSql(), __METHOD__);

        return $dataProvider;
    }





    public function institutesSearch($params)
    {
        $campuses = User::getCampusesByUser(Yii::$app->user->identity->id);
        $query = ClassTeacher::find()->where(['campus_id' => $campuses]);

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
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'teacher_details_id' => $this->teacher_details_id,
            'academic_year_id' => $this->academic_year_id,
            'status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
        ]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = ClassTeacher::find()->innerJoinWith('teacherDetails as td')
            ->where(['td.campus_id' => User::getCampusesByUser(Yii::$app->user->identity->id)]);

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
            'class_teacher.class_id' => $this->class_id,
            'class_teacher.section_id' => $this->section_id,
            'teacher_details_id' => $this->teacher_details_id,
            'academic_year_id' => $this->academic_year_id,
            'class_teacher.status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
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
        $query = ClassTeacher::find()
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
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'teacher_details_id' => $this->teacher_details_id,
            'academic_year_id' => $this->academic_year_id,
            'status' => $this->status,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
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
}
