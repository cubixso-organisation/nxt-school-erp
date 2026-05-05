<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\Campus;
use app\modules\admin\models\ClassSections;
use app\modules\admin\models\ClassTeacher;
use app\modules\admin\models\search\ClassTeacherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClassTeacherController implements the CRUD actions for ClassTeacher model.
 */
class ClassTeacherController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'sections-by-class'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'sections-by-class'],
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
     * Lists all ClassTeacher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClassTeacherSearch();





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
     * Displays a single ClassTeacher model.
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
     * Creates a new ClassTeacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ClassTeacher();



        $post = Yii::$app->request->post();
        if (!empty($post)) {
            // var_dump($post);exit;
            $class_id = $post['ClassTeacher']['class_id'];
            $section_id = $post['ClassTeacher']['section_id'];
            $teacher_details_id = $post['ClassTeacher']['teacher_details_id'];
            $classId = $post['ClassTeacher']['class_id'];
            $sectionId = $post['ClassTeacher']['section_id'];
            $academic_year_id = isset($post['ClassTeacher']['academic_year_id']) ? $post['ClassTeacher']['academic_year_id'] : (new Campus())->getCurrentSession((new Campus())->getCampusId());
            $class_teacher =   ClassTeacher::find()->where(['class_id' => $class_id])
                ->andWhere(['section_id' => $section_id])->andWhere(['teacher_details_id' => $teacher_details_id])
                ->andWhere(['academic_year_id' => $academic_year_id])
                ->one();
        }




        if (empty($class_teacher)) {



            if ($model->loadAll(Yii::$app->request->post())) {





                foreach ($model['teacher_details_id'] as $classTeacher) {
                    $model = new ClassTeacher();
                    $model->class_id = $classId;
                    $model->section_id = $sectionId;
                    $model->academic_year_id = $academic_year_id;
                    $model->status = ClassTeacher::STATUS_ACTIVE;
                    $model->teacher_details_id = $classTeacher;
                    $model->save(false);
                }

                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {

            $model->addError('class_id', 'Already Exist');
            $model->addError('section_id', 'Already Exist');
            $model->addError('teacher_details_id', 'Already Exist');
            $model->addError('academic_year_id', 'Already Exist');

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionSectionsByClass($classId)
    {
        $sections = \app\modules\admin\models\ClassSections::find()
            ->where(['student_class_id' => $classId, 'status' => ClassSections::STATUS_ACTIVE])
            ->asArray()
            ->all();

        return json_encode(['sections' => $sections]);
    }
    /**
     * Updates an existing ClassTeacher model.
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
     * Deletes an existing ClassTeacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = ClassTeacher::STATUS_DELETE;
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
            $model = ClassTeacher::find()->where([
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
     * Finds the ClassTeacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClassTeacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClassTeacher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
