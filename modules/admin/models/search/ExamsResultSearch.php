<?php

namespace app\modules\admin\models\search;

use app\models\User;
use app\modules\admin\models\base\StudentDetails;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\ExamsResult;

/**
 * app\modules\admin\models\search\ExamsResultSearch represents the model behind the search form about `app\modules\admin\models\ExamsResult`.
 */
class ExamsResultSearch extends ExamsResult
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exams_result_id', 'campus_id', 'exam_id', 'academic_year_id', 'student_id', 'class_id', 'section_id', 'status', 'create_user_id', 'update_user_id', 'subject_id'], 'integer'],
            [['marks_sheet', 'created_on', 'updated_on', 'marks_type', 'percentage_or_gpa'], 'safe'],
            [['percentage_or_gpa'], 'number'],
            [['section_id'], 'required'],
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
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);

        $query = ExamsResult::find()->where(['campus_id' => $campus_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'percentage_or_gpa' => SORT_DESC,
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
            'exams_result_id' => $this->exams_result_id,
            'campus_id' => $this->campus_id,
            'exam_id' => $this->exam_id,
            'academic_year_id' => $this->academic_year_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,

            'status' => $this->status,
            'marks_type' => $this->marks_type,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'marks_sheet', $this->marks_sheet]);

        return $dataProvider;
    }



    public function campusAdminSearch($params, $student_id = "")
    {



        $query = ExamsResult::find();

        $query->andWhere(['exams_result.campus_id' => User::getCampusId()])->joinWith(['student as stu'])->andWhere(['stu.status' => StudentDetails::STATUS_ACTIVE]);

        if (!empty($student_id)) {
            $query->andWhere(['student_id' => $student_id]);
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'exams_result.exams_result_id' => $this->exams_result_id,
            'exams_result.campus_id' => $this->campus_id,
            'exams_result.exam_id' => $this->exam_id,
            'exams_result.academic_year_id' => $this->academic_year_id,
            'exams_result.student_id' => $this->student_id,
            'exams_result.class_id' => $this->class_id,
            'exams_result.section_id' => $this->section_id,
            'exams_result.subject_id' => $this->subject_id,
            'exams_result.status' => $this->status,
            'marks_type' => $this->marks_type,
            'exams_result.created_on' => $this->created_on,
            'exams_result.updated_on' => $this->updated_on,
            'exams_result.create_user_id' => $this->create_user_id,
            'exams_result.update_user_id' => $this->update_user_id,
        ]);






        if (isset($this->percentage_or_gpa) &&  $this->percentage_or_gpa != '') {
            $min = 1;
            $max = $this->percentage_or_gpa;

            $query->andWhere(['between', 'exams_result.percentage_or_gpa', $min, $max]);
        }


        $query->andFilterWhere(['like', 'marks_sheet', $this->marks_sheet]);

        // var_dump($query->createCommand()->getRawSql());exit;

        return $dataProvider;
    }




    public function institutesSearch($params)
    {
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);

        $query = ExamsResult::find()->where(['campus_id' => $campus_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'percentage_or_gpa' => SORT_DESC,
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
            'exams_result_id' => $this->exams_result_id,
            'campus_id' => $this->campus_id,
            'exam_id' => $this->exam_id,
            'academic_year_id' => $this->academic_year_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'percentage_or_gpa' => $this->percentage_or_gpa,
            'status' => $this->status,
            'marks_type' => $this->marks_type,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'marks_sheet', $this->marks_sheet]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = ExamsResult::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'percentage_or_gpa' => SORT_DESC,
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
            'exams_result_id' => $this->exams_result_id,
            'campus_id' => $this->campus_id,
            'exam_id' => $this->exam_id,
            'academic_year_id' => $this->academic_year_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'percentage_or_gpa' => $this->percentage_or_gpa,
            'status' => $this->status,
            'marks_type' => $this->marks_type,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'marks_sheet', $this->marks_sheet]);


        if (isset($this->percentage_or_gpa) &&  $this->percentage_or_gpa != '') {
            $min = 1;
            $max = $this->percentage_or_gpa;

            $query->andWhere(['between', 'exams_result.percentage_or_gpa', $min, $max]);
        }


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
        $query = ExamsResult::find()
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

            return $dataProvider;
        }

        $query->andFilterWhere([
            'exams_result_id' => $this->exams_result_id,
            'campus_id' => $this->campus_id,
            'exam_id' => $this->exam_id,
            'academic_year_id' => $this->academic_year_id,
            'student_id' => $this->student_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'percentage_or_gpa' => $this->percentage_or_gpa,
            'status' => $this->status,
            'marks_type' => $this->marks_type,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'marks_sheet', $this->marks_sheet]);

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
