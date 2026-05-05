<?php

namespace app\modules\admin\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\StudentClassAttendance;

/**
 * app\modules\admin\models\search\StudentClassAttendanceSearch represents the model behind the search form about `app\modules\admin\models\StudentClassAttendance`.
 */
class StudentClassAttendanceSearch extends StudentClassAttendance
{

    public $section_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'student_id', 'section_id', 'teacher_id', 'subject_timetable_id', 'academic_year_id', 'subject_group_id', 'subject_id', 'status', 'mode', 'create_user_id', 'update_user_id'], 'integer'],
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
    public function search($params, $student_id = '')
    {
        $query = StudentClassAttendance::find();

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

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'student_id' => $this->student_id,
            'teacher_id' => $this->teacher_id,
            'subject_timetable_id' => $this->subject_timetable_id,
            'academic_year_id' => $this->academic_year_id,
            'subject_group_id' => $this->subject_group_id,
            'subject_id' => $this->subject_id,
            'date' => $this->date,
            'mode' => $this->mode,
            'student_class_attendance.status' => $this->status,
            'student_class_attendance.create_user_id' => $this->create_user_id,
            'student_class_attendance.update_user_id' => $this->update_user_id,
            'student_class_attendance.created_on' => $this->created_on,
            'student_class_attendance.updated_on' => $this->updated_on,
        ]);

        return $dataProvider;
    }


    public function campusAdminSearch($params, $student_id = '', $section_id = '', $timetable_id = '')
    {
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
        $query = StudentClassAttendance::find()->innerJoinWith('academicYear as acy')->where(['acy.campus_id' => $campus_id]);

        if (!empty($student_id)) {
            $query->andWhere(['student_id' => $student_id]);
        }

        if (!empty($section_id)) {
            $query->joinWith(['student as stu'])->andWhere(['stu.section_id' => $section_id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_on' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'student_id' => $this->student_id,
            'teacher_id' => $this->teacher_id,
            'subject_timetable_id' => $this->subject_timetable_id,
            'academic_year_id' => $this->academic_year_id,
            'subject_group_id' => $this->subject_group_id,
            'subject_id' => $this->subject_id,
            'mode' => $this->mode,
            'student_class_attendance.status' => $this->status,
            'student_class_attendance.create_user_id' => $this->create_user_id,
            'student_class_attendance.update_user_id' => $this->update_user_id,
            'student_class_attendance.created_on' => $this->created_on,
            'student_class_attendance.updated_on' => $this->updated_on,
        ]);

        // Handle date range filtering
        if (!empty($this->date)) {
            // Check if it's a date range (contains ' - ')
            if (strpos($this->date, ' - ') !== false) {
                // Split the date range
                $dateRange = explode(' - ', $this->date);
                if (count($dateRange) == 2) {
                    $startDate = trim($dateRange[0]);
                    $endDate = trim($dateRange[1]);

                    // Validate and format dates
                    $startDate = $this->formatDateForQuery($startDate);
                    $endDate = $this->formatDateForQuery($endDate);

                    if ($startDate && $endDate) {
                        // Apply date range filter
                        $query->andFilterWhere(['>=', 'date', $startDate])
                            ->andFilterWhere(['<=', 'date', $endDate]);
                    }
                }
            } else {
                // Single date filter (fallback for exact date matching)
                $formattedDate = $this->formatDateForQuery($this->date);
                if ($formattedDate) {
                    $query->andFilterWhere(['date' => $formattedDate]);
                }

                // $dataProvider = $formattedDate;
            }
        }
        // var_dump($query->createCommand()->getRawSql());
        // exit;
        return $dataProvider;
    }

    /**
     * Format date string for database query
     * @param string $dateString
     * @return string|false
     */
    private function formatDateForQuery($dateString)
    {
        // Try to parse various date formats
        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y'];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateString);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        // Try strtotime as fallback
        $timestamp = strtotime($dateString);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        return false;

        return $dataProvider;
    }

    public function institutesSearch($params, $student_id = '')
    {
        $query = StudentClassAttendance::find();

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
            'id' => $this->id,
            'student_id' => $this->student_id,
            'teacher_id' => $this->teacher_id,
            'subject_timetable_id' => $this->subject_timetable_id,
            'academic_year_id' => $this->academic_year_id,
            'subject_group_id' => $this->subject_group_id,
            'subject_id' => $this->subject_id,
            'date' => $this->date,
            'mode' => $this->mode,
            'student_class_attendance.status' => $this->status,
            'student_class_attendance.create_user_id' => $this->create_user_id,
            'student_class_attendance.update_user_id' => $this->update_user_id,
            'student_class_attendance.created_on' => $this->created_on,
            'student_class_attendance.updated_on' => $this->updated_on,
        ]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params, $student_id = '')
    {
        $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
        $query = StudentClassAttendance::find()->innerJoinWith('academicYear as acy')->where(['acy.campus_id' => $campus_id]);
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
            'id' => $this->id,
            'student_id' => $this->student_id,
            'teacher_id' => $this->teacher_id,
            'subject_timetable_id' => $this->subject_timetable_id,
            'academic_year_id' => $this->academic_year_id,
            'subject_group_id' => $this->subject_group_id,
            'subject_id' => $this->subject_id,
            'date' => $this->date,
            'mode' => $this->mode,

            'student_class_attendance.status' => $this->status,
            'student_class_attendance.create_user_id' => $this->create_user_id,
            'student_class_attendance.update_user_id' => $this->update_user_id,
            'student_class_attendance.created_on' => $this->created_on,
            'student_class_attendance.updated_on' => $this->updated_on,
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
        $query = StudentClassAttendance::find()
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
            'student_id' => $this->student_id,
            'teacher_id' => $this->teacher_id,
            'subject_timetable_id' => $this->subject_timetable_id,
            'academic_year_id' => $this->academic_year_id,
            'subject_group_id' => $this->subject_group_id,
            'subject_id' => $this->subject_id,
            'date' => $this->date,
            'student_class_attendance.status' => $this->status,
            'student_class_attendance.create_user_id' => $this->create_user_id,
            'student_class_attendance.update_user_id' => $this->update_user_id,
            'student_class_attendance.created_on' => $this->created_on,
            'student_class_attendance.updated_on' => $this->updated_on,
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
