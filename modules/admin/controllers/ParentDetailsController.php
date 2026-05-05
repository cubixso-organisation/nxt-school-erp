<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\Auth;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use app\modules\admin\models\ParentDetails;
use app\modules\admin\models\ParentHasCampus;
use app\modules\admin\models\search\ParentDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ParentDetailsController implements the CRUD actions for ParentDetails model.
 */
class ParentDetailsController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-parent-has-campus', 'add-student-details'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'add-parent-has-campus', 'add-student-details'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isCampusSubAdmin();
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
     * Lists all ParentDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParentDetailsSearch();


        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::getCampusesByUser(Yii::$app->user->identity->id));
        } elseif (Yii::$app->user->identity->user_role == User::role_campus_sub_admin) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }



        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ParentDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerParentHasCampus = new \yii\data\ArrayDataProvider([
            'allModels' => $model->parentHasCampuses,
        ]);
        $providerStudentDetails = new \yii\data\ArrayDataProvider([
            'allModels' => $model->studentDetails,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerParentHasCampus' => $providerParentHasCampus,
            'providerStudentDetails' => $providerStudentDetails,
        ]);
    }

    /**
     * Creates a new ParentDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ParentDetails();


        if ($model->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post();
            $contact_number = $post['ParentDetails']['contact_number'];
            $name_of_the_father = $post['ParentDetails']['name_of_the_father'];
            $user_role = User::ROLE_PARENT;
            // echo '<pre>';

            //create parent user
            $user_check = User::find()->where(['contact_no' => $contact_number])->andWhere(['user_role' => $user_role])->one();
            if (empty($user_check)) {

                $profile_image = \yii\web\UploadedFile::getInstance($model, 'profile_image');
                if (!empty($profile_image)) {
                    $image = Yii::$app->notification->imageKitUpload($profile_image, 'profile_image/parent');
                    $model->profile_image = $image['url'];
                }


                if ($model->save(false)) {



                    $user = new User();
                    $user->first_name = $name_of_the_father;
                    $user->contact_no = $contact_number;
                    $user->username = $contact_number . '@' . $user_role . '.com';
                    $user->user_role = $user_role;
                    $user->status = User::STATUS_ACTIVE;
                    $user->save(false);
                    $model->user_id = $user->id;
                    $model->save();

                    //assign parent to relevant campus
                    $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
                    $campus = Campus::find()->where(['id' => $campus_id])->one();
                    $Institutes  = Institutes::find()->where(['id' => $campus->institute_id])->one();
                    if ($Institutes->subscription_type == Institutes::subscription_type_group_of_institutions) {
                        $Institutes_id = $Institutes->id;
                        $campus_all = Campus::find()->where(['institute_id' => $Institutes_id])->all();
                        foreach ($campus_all  as $campus_data) {
                            $campus_id_data[] = $campus_data->id;
                        }
                    } else {
                        $campus_id_data[] = User::getCampusesByUser(Yii::$app->user->identity->id);
                    }

                    if (!empty($campus_id_data)) {
                        foreach ($campus_id_data as $campus_id_data_all) {
                            $parent_has_campus = new  ParentHasCampus();
                            $parent_has_campus->patient_id = $model->id;
                            $parent_has_campus->campus_id  = $campus_id_data_all;
                            $parent_has_campus->status =   ParentHasCampus::STATUS_ACTIVE;
                            $parent_has_campus->save(false);
                        }
                    }


                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {

                $model->addError('contact_number', 'User Exist');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ParentDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $profile_photo_old = $model->profile_image;

        if ($model->loadAll(Yii::$app->request->post())) {
            $profile_image = \yii\web\UploadedFile::getInstance($model, 'profile_image');
            if (!empty($profile_image)) {
                $image = Yii::$app->notification->imageKitUpload($profile_image, 'profile_image/parent');
                $model->profile_image = $image['url'];
            } else {
                $model->profile_image = $profile_photo_old;
            }

            // Save the updated parent information
            $model->save(false);

            // Update or assign the parent to the relevant campus
            $campus_id = User::getCampusesByUser(Yii::$app->user->identity->id);
            $campus = Campus::findOne(['id' => $campus_id]);
            $Institutes = Institutes::findOne(['id' => $campus->institute_id]);

            // Determine the campus IDs based on subscription type
            if ($Institutes->subscription_type == Institutes::subscription_type_group_of_institutions) {
                $campus_all = Campus::findAll(['institute_id' => $Institutes->id]);
                foreach ($campus_all as $campus_data) {
                    $campus_id_data[] = $campus_data->id;
                }
            } else {
                $campus_id_data[] = $campus_id;
            }

            // Update the ParentHasCampus records
            if (!empty($campus_id_data)) {
                foreach ($campus_id_data as $campus_id_data_all) {
                    $parent_has_campus = ParentHasCampus::findOne(['patient_id' => $model->id, 'campus_id' => $campus_id_data_all]);
                    if (!$parent_has_campus) {
                        $parent_has_campus = new ParentHasCampus();
                    }
                    $parent_has_campus->patient_id = $model->id;
                    $parent_has_campus->campus_id = $campus_id_data_all;
                    $parent_has_campus->status = ParentHasCampus::STATUS_ACTIVE;
                    $parent_has_campus->save(false);
                }
            }

            // Update the related User and Auth records
            $user = User::findOne(['id' => $model->user_id]);
            if ($user) {
                // Update contact number and username for User table
                $user->contact_no = $model->contact_number;
                $user->username = $model->contact_number . '@parent.com';
                $user->save(false);

                // Update Auth table's source_id with the new contact number
                $auth = Auth::findOne(['user_id' => $user->id]);
                if ($auth) {
                    $auth->source_id = $model->contact_number;
                    $auth->save(false);
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing ParentDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = ParentDetails::STATUS_DELETE;
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
            $model = ParentDetails::find()->where([
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
     * Finds the ParentDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ParentDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ParentDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for ParentHasCampus
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddParentHasCampus()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('ParentHasCampus');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formParentHasCampus', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for StudentDetails
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddStudentDetails()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('StudentDetails');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add') {
                $row[] = [];
            }
            return $this->renderAjax('_formStudentDetails', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
