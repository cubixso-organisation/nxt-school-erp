<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\SubjectGroups;
use app\modules\admin\models\search\SubjectGroupsSearch;
use app\modules\admin\models\SubjectGroupsClassSections;
use app\modules\admin\models\SubjectGroupSubjects;
use app\modules\admin\models\Subjects;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/** 
 * SubjectGroupsController implements the CRUD actions for SubjectGroups model.
 */
class SubjectGroupsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-subject-group-subjects', 'add-subject-groups-class-sections', 'save-subjects-and-groups', 'update-subjects-and-groups', 'subject-group-delete'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-subject-group-subjects', 'add-subject-groups-class-sections', 'save-subjects-and-groups', 'update-subjects-and-groups', 'subject-group-delete'],
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
     * Lists all SubjectGroups models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubjectGroupsSearch();
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
     * Displays a single SubjectGroups model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerSubjectGroupSubjects = new \yii\data\ArrayDataProvider([
            'allModels' => $model->subjectGroupSubjects,
        ]);
        $providerSubjectGroupsClassSections = new \yii\data\ArrayDataProvider([
            'allModels' => $model->subjectGroupsClassSections,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerSubjectGroupSubjects' => $providerSubjectGroupSubjects,
            'providerSubjectGroupsClassSections' => $providerSubjectGroupsClassSections,
        ]);
    }

    /**
     * Creates a new SubjectGroups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SubjectGroups();

        $searchModel = new SubjectGroupsSearch();

        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }




        if ($model->loadAll(Yii::$app->request->post())) {
            $model->campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
            $model->status  = SubjectGroups::STATUS_ACTIVE;

            $model->saveAll();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Updates an existing SubjectGroups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        $searchModel = new SubjectGroupsSearch();

        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        }




        if ($model->loadAll(Yii::$app->request->post())) {
            $model->campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
            $model->status  = SubjectGroups::STATUS_ACTIVE;

            $model->save();

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing SubjectGroups model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = SubjectGroups::STATUS_DELETE;
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
            $model = SubjectGroups::find()->where([
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

    public function actionSaveSubjectsAndGroups()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        try {


            $campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
            $academic_year_id = $post['SubjectGroups']['academic_year_id'];
            $subject_group_name = $post['SubjectGroups']['subject_group_name'];
            $class_sections_id = $post['SubjectGroups']['class_sections_id'];
            $subject_id = $post['SubjectGroups']['subject_id'];



            $description = $post['SubjectGroups']['description'];

            if (!empty($academic_year_id) && !empty($subject_group_name) && !empty($class_sections_id) && !empty($subject_id)) {


                $SubjectGroupsClassSectionsCheck = SubjectGroupsClassSections::find()->innerJoinWith('subjectGroup as sg')
                    ->where(['class_sections_id' => $class_sections_id])
                    ->andWhere(['sg.academic_year_id' => $academic_year_id])
                    ->one();



                if (empty($SubjectGroupsClassSectionsCheck)) {

                    $subject_groups = new  SubjectGroups();
                    $subject_groups->campus_id  = $campus_id;
                    $subject_groups->subject_group_name  = $subject_group_name;
                    $subject_groups->description  = $description;
                    $subject_groups->academic_year_id   = $academic_year_id;
                    $subject_groups->status   = SubjectGroups::STATUS_ACTIVE;
                    if ($subject_groups->save(false)) {

                        $subject_groups_class_sections = new  SubjectGroupsClassSections();
                        $subject_groups_class_sections->subject_group_id  = $subject_groups->id;
                        $subject_groups_class_sections->class_sections_id   = $class_sections_id;
                        $subject_groups_class_sections->status  = SubjectGroupsClassSections::STATUS_ACTIVE;
                        if ($subject_groups_class_sections->save(false)) {

                            foreach ($subject_id as $subject_id_data) {
                                $subject_group_subjects = new SubjectGroupSubjects();
                                $subject_group_subjects->subject_group_id  = $subject_groups->id;
                                $subject_group_subjects->subject_id   = $subject_id_data;
                                $subject_group_subjects->academic_year_id   = $academic_year_id;
                                $subject_group_subjects->status   = SubjectGroupSubjects::STATUS_ACTIVE;
                                $subject_group_subjects->save(false);
                            }
                        }

                        $data['success'] = 1;
                        $data['message'] = "data update successfully";
                    }
                } else if (!empty($SubjectGroupsClassSectionsCheck) && $SubjectGroupsClassSectionsCheck->status == SubjectGroupsClassSections::STATUS_DELETE) {
                    $subject_groups = SubjectGroups::find()->where(['id' => $SubjectGroupsClassSectionsCheck->subject_group_id])->one();
                    $subject_groups_class_sections = SubjectGroupsClassSections::find()->where(['subject_group_id' => $SubjectGroupsClassSectionsCheck->subject_group_id])->one();
                    $subject_groups_class_sections->status = SubjectGroupsClassSections::STATUS_ACTIVE;
                    $subject_groups_class_sections->save(false);
                    if (!empty($subject_groups)) {
                        $subject_groups->status = SubjectGroups::STATUS_ACTIVE;
                        if ($subject_groups->save(false)) {
                            $subject_group_subjects = SubjectGroupSubjects::find()->where(['subject_group_id' => $subject_groups->id])->all();
                            if (!empty($subject_group_subjects)) {
                                foreach ($subject_group_subjects as $subject_group_subjects_data) {
                                    $subject_group_subjects_data->status = SubjectGroupSubjects::STATUS_ACTIVE;
                                    $subject_group_subjects_data->save(false);
                                }
                            }
                        }
                    }



                    $data['success'] = 1;
                    $data['message'] = "data update successfully";
                } else {
                    $data['error'] = 1;
                    $data['message'] = "this combination already exists";
                }
            } else {
                $data['error'] = 1;
                $data['message'] = "params are missing";
            }
        } catch (Exception $e) {
            $data['error'] = 1;
            $data['message'] = $e->getMessage();
        }



        return $data;
    }




    public function actionUpdateSubjectsAndGroups()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        try {
            $campus_id  = User::getCampusesByUser(Yii::$app->user->identity->id);
            $academic_year_id = $post['SubjectGroups']['academic_year_id'];
            $subject_group_name = $post['SubjectGroups']['subject_group_name'];
            $class_sections_id = $post['SubjectGroups']['class_sections_id'];
            $subject_id = $post['SubjectGroups']['subject_id'];
            $description = $post['SubjectGroups']['description'];

            if (!empty($academic_year_id) && !empty($subject_group_name) && !empty($class_sections_id) && !empty($subject_id)) {


                $SubjectGroupsClassSectionsCheck = SubjectGroupsClassSections::find()->innerJoinWith('subjectGroup as sg')
                    ->where(['class_sections_id' => $class_sections_id])
                    ->andWhere(['sg.academic_year_id' => $academic_year_id])
                    ->one();




                if (!empty($SubjectGroupsClassSectionsCheck)) {
                    $subject_group_id  = $SubjectGroupsClassSectionsCheck->subject_group_id;
                    $SubjectGroupsClassSectionsId = $SubjectGroupsClassSectionsCheck->id;

                    $subject_groups =   SubjectGroups::find()->where(['id' => $subject_group_id])->one();
                    $subject_groups->campus_id  = $campus_id;
                    $subject_groups->subject_group_name  = $subject_group_name;
                    $subject_groups->description  = $description;
                    $subject_groups->academic_year_id   = $academic_year_id;
                    $subject_groups->status   = SubjectGroups::STATUS_ACTIVE;
                    if ($subject_groups->save(false)) {


                        $subject_groups_class_sections =   SubjectGroupsClassSections::find()->where(['id' => $SubjectGroupsClassSectionsId])->one();
                        $subject_groups_class_sections->subject_group_id  = $subject_groups->id;
                        $subject_groups_class_sections->class_sections_id   = $class_sections_id;
                        $subject_groups_class_sections->status  = SubjectGroupsClassSections::STATUS_ACTIVE;
                        if ($subject_groups_class_sections->save(false)) {

                            $subjects = Subjects::find()->where(['status' => Subjects::STATUS_ACTIVE])->all();
                            if (!empty($subjects)) {
                                foreach ($subjects as $subjectsData) {
                                    $subject_id_in = $subjectsData->id;
                                    $subject_group_subjects_insert = SubjectGroupSubjects::find()->where(['subject_id' => $subject_id_in])->andWhere(['subject_group_id' => $subject_group_id])->one();
                                    if (empty($subject_group_subjects_insert)) {
                                        $subject_group_subjects_add =  new SubjectGroupSubjects();
                                        $subject_group_subjects_add->subject_group_id  = $subject_groups->id;
                                        $subject_group_subjects_add->subject_id   = $subject_id_in;
                                        $subject_group_subjects_add->academic_year_id   = $academic_year_id;
                                        $subject_group_subjects_add->status   = SubjectGroupSubjects::STATUS_INACTIVE;
                                        $subject_group_subjects_add->save(false);
                                    } else {
                                        $subject_group_subjects_add =   SubjectGroupSubjects::find()->where(['id' => $subject_group_subjects_insert->id])->one();
                                        $subject_group_subjects_add->subject_group_id  = $subject_groups->id;
                                        $subject_group_subjects_add->subject_id   = $subject_id_in;
                                        $subject_group_subjects_add->academic_year_id   = $academic_year_id;
                                        $subject_group_subjects_add->status   = SubjectGroupSubjects::STATUS_INACTIVE;
                                        $subject_group_subjects_add->save(false);
                                    }
                                }
                            }



                            foreach ($subject_id as $subject_id_data) {



                                $subject_group_subjects_exist = SubjectGroupSubjects::find()->where(['subject_group_id' => $subject_group_id])->andWhere(['subject_id' => $subject_id_data])->one();
                                if (!empty($subject_group_subjects_exist)) {


                                    $subject_group_subjects = SubjectGroupSubjects::find()->where(['id' => $subject_group_subjects_exist->id])->one();
                                } else {
                                    $subject_group_subjects = new SubjectGroupSubjects();
                                }

                                $subject_group_subjects->subject_group_id  = $subject_groups->id;
                                $subject_group_subjects->subject_id   = $subject_id_data;
                                $subject_group_subjects->academic_year_id   = $academic_year_id;
                                $subject_group_subjects->status   = SubjectGroupSubjects::STATUS_ACTIVE;


                                $subject_group_subjects->save(false);
                            }
                        }




                        $data['success'] = 1;
                        $data['message'] = "data update successfully";
                    }
                } else {
                    $data['error'] = 1;
                    $data['message'] = "this combination not exists";
                }
            } else {
                $data['error'] = 1;
                $data['message'] = "params are missing";
            }
        } catch (Exception $e) {
            $data['error'] = 1;
            $data['message'] = $e->getMessage();
        }



        return $data;
    }





    /**
     * Finds the SubjectGroups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubjectGroups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubjectGroups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for SubjectGroupSubjects
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddSubjectGroupSubjects()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('SubjectGroupSubjects');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formSubjectGroupSubjects', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for SubjectGroupsClassSections
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddSubjectGroupsClassSections()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('SubjectGroupsClassSections');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formSubjectGroupsClassSections', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionSubjectGroupDelete()
    {
        $data = [];
        $post = Yii::$app->request->post();
        try {
            $subject_group_id = $post['id'];
            $subject_groups = SubjectGroups::find()->where(['id' => $subject_group_id])->one();
            $subject_groups_class_sections = SubjectGroupsClassSections::find()->where(['subject_group_id' => $subject_group_id])->one();
            $subject_groups_class_sections->status = SubjectGroupsClassSections::STATUS_DELETE;
            $subject_groups_class_sections->save(false);
            if (!empty($subject_groups)) {
                $subject_groups->status = SubjectGroups::STATUS_DELETE;
                if ($subject_groups->save(false)) {
                    $subject_group_subjects = SubjectGroupSubjects::find()->where(['subject_group_id' => $subject_groups->id])->all();
                    if (!empty($subject_group_subjects)) {
                        foreach ($subject_group_subjects as $subject_group_subjects_data) {
                            $subject_group_subjects_data->status = SubjectGroupSubjects::STATUS_DELETE;
                            $subject_group_subjects_data->save(false);
                        }
                    }
                }

                $data['status'] = 'ok';
                $data['details'] = "data updated successfully";
            } else {
                $data['status'] = 'nok';
                $data['error'] = "subject group data not found";
            }
        } catch (Exception $e) {
            $data['status'] = 'nok';
            $data['error'] = $e->getMessage();
        }

        return json_encode($data);
    }
}
