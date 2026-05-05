<?php

namespace app\modules\childassessment\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\ClassSections;
use app\modules\admin\models\base\StudentDetails;
use app\modules\childassessment\models\base\ChildMerit;
use app\modules\childassessment\models\base\MeritsAssignedToClass as BaseMeritsAssignedToClass;
use app\modules\childassessment\models\base\StudentMeritMarks;
use app\modules\childassessment\models\base\MeritsAssignedToClass;
use app\modules\childassessment\models\search\MeritsAssignedToClassSearch;
use app\modules\childassessment\models\StudentMeritMarks as ModelsStudentMeritMarks;
use Mpdf\Tag\Section;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * MeritsAssignedToClassController implements the CRUD actions for MeritsAssignedToClass model.
 */
class MeritsAssignedToClassController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'get-sections'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'get-sections'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all MeritsAssignedToClass models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MeritsAssignedToClassSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->SubAdminSearch(Yii::$app->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MeritsAssignedToClass model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MeritsAssignedToClass model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MeritsAssignedToClass();

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->campus_id = User::getCampusId(\Yii::$app->user->identity->id);
            $check_already_exists = MeritsAssignedToClass::find()
                ->where([
                    'campus_id' => $model->campus_id,
                    'class_id' => $model->class_id,
                    'section_id' => $model->section_id,
                    'academic_year_id' => $model->academic_year_id,
                    'merit_id' => $model->merit_id,
                ])->count();

            if ($check_already_exists > 0) {
                Yii::$app->session->setFlash('error', 'This section and class are already assigned with this merit.');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            if ($model->save(false)) {

                $student_details = StudentDetails::find()
                    ->where(['campus_id' => $model->campus_id])
                    ->andWhere(['student_class_id' => $model->class_id])
                    ->andWhere(['section_id' => $model->section_id])
                    ->andWhere(['academic_year_id' => $model->academic_year_id])
                    ->all();


                if (empty($student_details)) {
                    Yii::$app->session->setFlash('error', 'No students found.');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
                foreach ($student_details as $student_detail) {
                    $studentMeritMark = new StudentMeritMarks();
                    $studentMeritMark->academic_year_id = $model->academic_year_id;
                    $studentMeritMark->campus_id =  $model->campus_id;
                    $studentMeritMark->academic_year_id = $model->academic_year_id;
                    $studentMeritMark->student_details_id = $student_detail->id;
                    $studentMeritMark->child_merit_id = $model->merit_id;
                    $merit = ChildMerit::find()->where(['id' => $model->merit_id])->one();
                    $studentMeritMark->max_marks =  $merit->max_marks;


                    $studentMeritMark->save(false); // Save without validation

                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MeritsAssignedToClass model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MeritsAssignedToClass model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = MeritsAssignedToClass::STATUS_DELETE;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = MeritsAssignedToClass::find()->where([
                'id' => $post['id'],
            ])->one();
            if (!empty($model)) {

                $model->status = $post['val'];
            }
            if ($model->save(false)) {
                $data['message'] = "Updated";
                $data['id'] = $model->status;
            } else {
                $data['message'] = "Not Updated";
            }
        }
        return $data;
    }
    public function actionGetSections()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $class_id = $parents[0];
                // var_dump('' . $hostel_id . '');
                // exit;
                $out = (new ClassSections)->getSections($class_id);
                // var_dump($out);exit;
                // return $out; 
            }
        }
        return  Json::encode($out);
    }


    /**
     * Finds the MeritsAssignedToClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MeritsAssignedToClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MeritsAssignedToClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
