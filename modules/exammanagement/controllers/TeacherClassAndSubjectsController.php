<?php

namespace app\modules\exammanagement\controllers;

use app\components\Toast;
use Yii;
use app\models\User;
use app\modules\admin\models\base\Subjects;
use app\modules\admin\models\SubjectGroupsClassSections;
use app\modules\admin\models\SubjectGroupSubjects;
use app\modules\admin\models\TeacherDetails;
use app\modules\exammanagement\models\TeacherClassAndSubjects;
use app\modules\exammanagement\models\search\TeacherClassAndSubjectsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * TeacherClassAndSubjectsController implements the CRUD actions for TeacherClassAndSubjects model.
 */
class TeacherClassAndSubjectsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'get-subjects'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'get-subjects'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
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
     * Lists all TeacherClassAndSubjects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeacherClassAndSubjectsSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TeacherClassAndSubjects model.
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
     * Creates a new TeacherClassAndSubjects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TeacherClassAndSubjects();

        if ($model->loadAll(Yii::$app->request->post())) {
            $teacherDetail = TeacherDetails::find()->where(['id' => $model["teacher_detail_id"]])->andWhere(['campus_id' => $model['campus_id']])->one();
            if (empty($teacherDetail)) {
                Toast::error('Invalid Teacher ID. Or teacher not belongs to this campus.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $successCount = 0; // Count successful saves
            foreach ($model['subject_id'] as $subject) {
                $newModel = new TeacherClassAndSubjects(); // Create a new instance for each subject
                $newModel->attributes = $model->attributes; // Assign attributes from the original model
                $newModel->teacher_user_id = $teacherDetail->user_id;
                $newModel->subject_id = $subject;
                if ($newModel->save(false)) {
                    $successCount++; // Increment count for successful save
                }
            }

            if ($successCount == count($model['subject_id'])) {
                Toast::success('Data saved successfully.');

                return $this->redirect(['index']);
            } else {
                Toast::error('Some data could not be saved.');
                return $this->redirect(Yii::$app->request->referrer);
            }

        } else {
            $out = [];
            return $this->render('create', [
                'model' => $model,
                'out'=>$out
            ]);
        }
    }


    public function actionGetSubjects()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $out = ['output' => [], 'selected' => ''];

    if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        if ($parents != null) {
            $type = $parents[0];

            // Call your getSubjects function to get data
            $subjects = (new TeacherClassAndSubjects)->getSubjects($type);

            $out['output'] = $subjects['output'];
        }
    }

    return $out; // JSON format is returned
}

    /**
     * Updates an existing TeacherClassAndSubjects model.
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
     * Deletes an existing TeacherClassAndSubjects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = TeacherClassAndSubjects::STATUS_DELETE;
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
            $model = TeacherClassAndSubjects::find()->where([
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


    /**
     * Finds the TeacherClassAndSubjects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TeacherClassAndSubjects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TeacherClassAndSubjects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
