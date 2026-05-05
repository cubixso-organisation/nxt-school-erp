<?php

namespace app\modules\documentgenerator\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\documentgenerator\models\Studentcertificates;

/**
 * app\modules\documentgenerator\models\search\StudentcertificatesSearch represents the model behind the search form about `app\modules\documentgenerator\models\Studentcertificates`.
 */
 class StudentcertificatesSearch extends Studentcertificates
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'header_height', 'footer_height', 'body_height', 'body_width', 'created_user_id', 'updated_user_id'], 'integer'],
            [['certificate_name', 'header_left_text', 'header_center_text', 'header_right_text', 'body_text', 'footer_left_text', 'footer_center_text', 'footer_right_text', 'certificate_design', 'student_photo', 'background_image', 'status', 'created_on', 'updated_on'], 'safe'],
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
        $query = Studentcertificates::find();

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
            'header_height' => $this->header_height,
            'footer_height' => $this->footer_height,
            'body_height' => $this->body_height,
            'body_width' => $this->body_width,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'certificate_name', $this->certificate_name])
            ->andFilterWhere(['like', 'header_left_text', $this->header_left_text])
            ->andFilterWhere(['like', 'header_center_text', $this->header_center_text])
            ->andFilterWhere(['like', 'header_right_text', $this->header_right_text])
            ->andFilterWhere(['like', 'body_text', $this->body_text])
            ->andFilterWhere(['like', 'footer_left_text', $this->footer_left_text])
            ->andFilterWhere(['like', 'footer_center_text', $this->footer_center_text])
            ->andFilterWhere(['like', 'footer_right_text', $this->footer_right_text])
            ->andFilterWhere(['like', 'certificate_design', $this->certificate_design])
            ->andFilterWhere(['like', 'student_photo', $this->student_photo])
            ->andFilterWhere(['like', 'background_image', $this->background_image])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

        return $dataProvider;
    }



    public function campusAdminSearch($params)
    {
        $query = Studentcertificates::find()->where(['campus_id'=>(new User)->getCampusId()]);;

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
            'header_height' => $this->header_height,
            'footer_height' => $this->footer_height,
            'body_height' => $this->body_height,
            'body_width' => $this->body_width,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'certificate_name', $this->certificate_name])
            ->andFilterWhere(['like', 'header_left_text', $this->header_left_text])
            ->andFilterWhere(['like', 'header_center_text', $this->header_center_text])
            ->andFilterWhere(['like', 'header_right_text', $this->header_right_text])
            ->andFilterWhere(['like', 'body_text', $this->body_text])
            ->andFilterWhere(['like', 'footer_left_text', $this->footer_left_text])
            ->andFilterWhere(['like', 'footer_center_text', $this->footer_center_text])
            ->andFilterWhere(['like', 'footer_right_text', $this->footer_right_text])
            ->andFilterWhere(['like', 'certificate_design', $this->certificate_design])
            ->andFilterWhere(['like', 'student_photo', $this->student_photo])
            ->andFilterWhere(['like', 'background_image', $this->background_image])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

        return $dataProvider;
    }



    
    public function institutesSearch($params)
    {
        $query = Studentcertificates::find();

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
            'header_height' => $this->header_height,
            'footer_height' => $this->footer_height,
            'body_height' => $this->body_height,
            'body_width' => $this->body_width,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'certificate_name', $this->certificate_name])
            ->andFilterWhere(['like', 'header_left_text', $this->header_left_text])
            ->andFilterWhere(['like', 'header_center_text', $this->header_center_text])
            ->andFilterWhere(['like', 'header_right_text', $this->header_right_text])
            ->andFilterWhere(['like', 'body_text', $this->body_text])
            ->andFilterWhere(['like', 'footer_left_text', $this->footer_left_text])
            ->andFilterWhere(['like', 'footer_center_text', $this->footer_center_text])
            ->andFilterWhere(['like', 'footer_right_text', $this->footer_right_text])
            ->andFilterWhere(['like', 'certificate_design', $this->certificate_design])
            ->andFilterWhere(['like', 'student_photo', $this->student_photo])
            ->andFilterWhere(['like', 'background_image', $this->background_image])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

        return $dataProvider;
    }




    public function campusSubAdminSearch($params)
    {
        $query = Studentcertificates::find();

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
            'header_height' => $this->header_height,
            'footer_height' => $this->footer_height,
            'body_height' => $this->body_height,
            'body_width' => $this->body_width,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'certificate_name', $this->certificate_name])
            ->andFilterWhere(['like', 'header_left_text', $this->header_left_text])
            ->andFilterWhere(['like', 'header_center_text', $this->header_center_text])
            ->andFilterWhere(['like', 'header_right_text', $this->header_right_text])
            ->andFilterWhere(['like', 'body_text', $this->body_text])
            ->andFilterWhere(['like', 'footer_left_text', $this->footer_left_text])
            ->andFilterWhere(['like', 'footer_center_text', $this->footer_center_text])
            ->andFilterWhere(['like', 'footer_right_text', $this->footer_right_text])
            ->andFilterWhere(['like', 'certificate_design', $this->certificate_design])
            ->andFilterWhere(['like', 'student_photo', $this->student_photo])
            ->andFilterWhere(['like', 'background_image', $this->background_image])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

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
        $query = Studentcertificates::find()
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
            'header_height' => $this->header_height,
            'footer_height' => $this->footer_height,
            'body_height' => $this->body_height,
            'body_width' => $this->body_width,
            'updated_on' => $this->updated_on,
            'created_user_id' => $this->created_user_id,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'certificate_name', $this->certificate_name])
            ->andFilterWhere(['like', 'header_left_text', $this->header_left_text])
            ->andFilterWhere(['like', 'header_center_text', $this->header_center_text])
            ->andFilterWhere(['like', 'header_right_text', $this->header_right_text])
            ->andFilterWhere(['like', 'body_text', $this->body_text])
            ->andFilterWhere(['like', 'footer_left_text', $this->footer_left_text])
            ->andFilterWhere(['like', 'footer_center_text', $this->footer_center_text])
            ->andFilterWhere(['like', 'footer_right_text', $this->footer_right_text])
            ->andFilterWhere(['like', 'certificate_design', $this->certificate_design])
            ->andFilterWhere(['like', 'student_photo', $this->student_photo])
            ->andFilterWhere(['like', 'background_image', $this->background_image])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_on', $this->created_on]);

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
