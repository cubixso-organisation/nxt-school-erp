<?php

namespace app\modules\leavemanagement\controllers;

use Yii;
use app\models\User;
use app\modules\admin\models\base\SubjectTimetable;
use app\modules\admin\models\base\TeacherDetails;
use app\modules\admin\models\base\TemporaryAssignTeacher;
use app\modules\leavemanagement\models\StaffLeaveApplied;
use app\modules\leavemanagement\models\search\StaffLeaveAppliedSearch;
use DateTime;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StaffLeaveAppliedController implements the CRUD actions for StaffLeaveApplied model.
 */
class StaffLeaveAppliedController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'approve', 'reject', 'leave-approve', 'leave-rejected', 'replace', 'replaced-teacher'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin() || User::isCampusAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'leave-approve', 'replace', 'replaced-teacher'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin()  || User::isCampusAdmin();
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
     * Lists all StaffLeaveApplied models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaffLeaveAppliedSearch();
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
     * Displays a single StaffLeaveApplied model.
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
     * Creates a new StaffLeaveApplied model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StaffLeaveApplied();

        if ($model->loadAll(Yii::$app->request->post())) {

            $model->campus_id = (new User())->getCampusId();
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StaffLeaveApplied model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            if ($model->status == StaffLeaveApplied::STATUS_APPROVED) {
                // Call the actionLeaveApprove method
                $this->actionLeaveApprove($id);
            } else if ($model->status == StaffLeaveApplied::STATUS_REJECTED) {
                $title = "Leave Rejected";
                $message = "Oops !! Your Leave Has Been Rejected";

                // Send notification for rejected leave
                $sendNoti = Yii::$app->notification->UserNotification('', $model->user_id, $title, $message);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing StaffLeaveApplied model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionDelete($id)
    // {

    //     $model = $this->findModel($id);
    //     if (!empty($model)) {
    //         $model->status = StaffLeaveApplied::STATUS_DELETE;
    //         $model->save(false);
    //     }

    //     return $this->redirect(['index']);
    // }

    public function actionUpdateStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';
        if (!empty($post['id'])) {
            $model = StaffLeaveApplied::find()->where([
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


    public function actionApprove($id)
    {
        $model = $this->findModel($id);

        if ($model) {
            $model->status = StaffLeaveApplied::STATUS_APPROVED;

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Leave Approved successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Error approving leave.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Leave application not found.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionReject($id)
    {
        $model = $this->findModel($id);

        if ($model) {
            $model->status = StaffLeaveApplied::STATUS_REJECTED;
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Leave Rejected successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Error rejecting leave.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Leave application not found.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the StaffLeaveApplied model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaffLeaveApplied the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaffLeaveApplied::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionLeaveApprove($id)
    {
        $model = $this->findModel($id);

        if ($model) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                // Update status in StaffLeaveApplied
                $model->status = StaffLeaveApplied::STATUS_APPROVED;
                $currentDay = date('l');

                // Save StaffLeaveApplied model
                if ($model->save(false)) {
                    // Fetch TeacherDetails model based on user_id
                    $teacherDetails = TeacherDetails::findOne(['user_id' => $model->user_id]);

                    if (!empty($teacherDetails)) {
                        // Fetch all subject timetables for the teacher
                        $subjectTimetables = SubjectTimetable::find()->where(['teacher_details_id' => $teacherDetails->id])->all();

                        if (empty($subjectTimetables)) {
                            Yii::info('Subject timetables not found for the teacher. Approving leave without assigning temporary teacher.');
                        } else {
                            // Iterate over each date between from_date and to_date
                            $fromDate = $model->from_date;
                            $toDate = $model->to_date;

                            // Create DateTime objects for each date
                            $startDate = new DateTime($fromDate);
                            $endDate = new DateTime($toDate);

                            // Calculate the difference
                            $interval = $startDate->diff($endDate);

                            // Get the difference in days
                            $dateDiffInDays = $interval->days;

                            for ($i = 0; $i <= $dateDiffInDays; $i++) {
                                $fromDate = strtotime($model->from_date);
                                $leaveDate = strtotime('+' . $i . ' day', $fromDate);
                                $leaveDay = date('l', $leaveDate);

                                // Fetch subject timetables for the leave day
                                $subjectTimetables = SubjectTimetable::find()->where(['day_id' => $leaveDay])->andWhere(['teacher_details_id' => $teacherDetails->id])->all();

                                // If leave is applied, fetch section-wise classes from the subject timetables
                                if (!empty($subjectTimetables)) {
                                    foreach ($subjectTimetables as $subjectTimetable) {
                                        $sectionId = $subjectTimetable->section_id;

                                        // Save records for each section
                                        // Create TemporaryAssignTeacher model and save data
                                        $temporaryAssignTeacher = new TemporaryAssignTeacher();
                                        $temporaryAssignTeacher->campus_id = $subjectTimetable->campus_id;
                                        $temporaryAssignTeacher->teacher_detail_id = $subjectTimetable->teacher_details_id;
                                        $temporaryAssignTeacher->teacher_timetable_id = $subjectTimetable->id;
                                        $temporaryAssignTeacher->date = $temporaryAssignTeacher->date = date('Y-m-d', $leaveDate);  // Save date in 'date' column
                                        $temporaryAssignTeacher->day_id = $leaveDay; // Save day name in 'day' column
                                        $temporaryAssignTeacher->period = $subjectTimetable->period;

                                        // Extract time from date string and assign to time_from and time_to
                                        $temporaryAssignTeacher->time_from = $subjectTimetable->time_from;
                                        $temporaryAssignTeacher->time_to = $subjectTimetable->time_to;

                                        // Assign class and section details
                                        $temporaryAssignTeacher->class_id = $subjectTimetable->class_id;
                                        $temporaryAssignTeacher->section_id = $sectionId;
                                        $temporaryAssignTeacher->subject_id = $subjectTimetable->subject_id;

                                        // Save the record
                                        if (!$temporaryAssignTeacher->save(false)) {
                                            $transaction->rollBack();
                                            Yii::error('Error saving data in TemporaryAssignTeacher table: ' . json_encode($temporaryAssignTeacher->errors));
                                            Yii::$app->session->setFlash('error', 'Error saving data in TemporaryAssignTeacher table.');
                                            return $this->redirect(Yii::$app->request->referrer);
                                        }
                                    }
                                }
                            }
                        }

                        // Commit transaction if all leaves are successfully approved
                        $transaction->commit();

                        Yii::$app->session->setFlash('success', 'Leave Approved successfully.');
                        $title = 'Leave Approved Succesfully';
                        $body = "Your Leave Has Been Approved";
                        $type = '';
                        Yii::$app->notification->UserNotification('', $teacherDetails->user_id, $title, $body, $type,'teacher_leave');
                    } else {
                        Yii::error('Teacher details not found.');
                        Yii::$app->session->setFlash('error', 'Teacher details not found.');
                    }
                } else {
                    Yii::error('Error approving leave: ' . json_encode($model->errors));
                    Yii::$app->session->setFlash('error', 'Error approving leave.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error('An error occurred while approving leave: ' . $e->getMessage());
                Yii::error('Error trace: ' . $e->getTraceAsString()); // Log the full stack trace for debugging
                Yii::$app->session->setFlash('error', 'An error occurred while approving leave: ' . $e->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('error', 'Leave application not found.');
        }

        // Redirect to the referring page or any specific URL as needed
        return $this->redirect(Yii::$app->request->referrer);
    }











    public function actionLeaveRejected($id)
    {
        // print_r($id);exit;
        $model = $this->findModel($id);

        if ($model) {
            $model->status = StaffLeaveApplied::STATUS_REJECTED;

            if ($model->save(false)) {
                Yii::$app->session->setFlash('error', 'Leave Rejected successfully.');
                $title = 'Leave Rejected ';
                $body = "Your Leave Has Been Rejected";
                $type = '';
                Yii::$app->notification->UserNotification('', $model->user_id, $title, $body, $type);
            } else {
                Yii::$app->session->setFlash('error', 'Error approving leave.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Leave application not found.');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionReplace($id = '', $teacherId = '')
    {

        $data = [];
        $dd = [];
        $withAbsentTecher = [];
        $onLeaveTeachers = [];
        $teacherHavingClass = [];
        $getCampusId = User::getCampusesByUser(Yii::$app->user->identity->id);
        $currentDate = date('Y-m-d');
        $currentDay = date('l');


        $teacherDetail = TeacherDetails::find()->where(['id' => $teacherId])->one();

        $temporayReplacement = TemporaryAssignTeacher::find()->where(['id' => $id])->one();
        // Fetch teachers who are on leave based 
        $teacherOnLeave = StaffLeaveApplied::find()->select('user_id')
            ->where(['campus_id' => $getCampusId])
            ->andWhere(['status' => StaffLeaveApplied::STATUS_APPROVED])
            ->andWhere(['user_role' => User::role_teacher])
            ->andWhere(['<=', 'from_date', $currentDate])
            ->andWhere(['>=', 'to_date', $currentDate])
            ->all();



        foreach ($teacherOnLeave as $tol) {
            $onLeaveTeachers[] = $tol->user_id;
        }

        // Fetch teachers who have classes on the current day
        $teacherHavingClasses = SubjectTimetable::find()

            ->where(['subject_timetable.campus_id' => $getCampusId])
            ->andWhere(['subject_timetable.day_id' => $currentDay])
            ->andWhere(['subject_timetable.time_from' => $temporayReplacement->time_from])
            ->andWhere(['subject_timetable.time_to' => $temporayReplacement->time_to])->all();


        foreach ($teacherHavingClasses as $ths) {

            $teacherHavingClass[] = $ths->teacherDetails->user_id;
        }

        $teacherid[] =  $teacherDetail->user_id;
        $mergedTeachers = array_unique(array_merge($teacherHavingClass, $onLeaveTeachers, $teacherid));
        // $withAbsentTecher = array_push($mergedTeachers, $teacherId);


        $otherTeachers = TeacherDetails::find()
            // Adjust columns as needed
            ->where(['not in', 'user_id', $mergedTeachers])
            ->andWhere(['campus_id' => $getCampusId])
            ->all();
        // Render the partial view with the merged list of teachers
        foreach ($otherTeachers as $ot) {
            $data['teacher_detail_id'] = $ot->id;
            $data['name'] = $ot->name;
            $dd[] = $data;
        }

        return json_encode($dd);
    }
    public function actionReplacedTeacher($teacherId = '', $id = '')
    {
        $temporaryAssign  = TemporaryAssignTeacher::find()->where(['id' => $id])->one();


        if (!empty($temporaryAssign)) {

            $teacherDetails = TeacherDetails::find()->where(['user_id' => $teacherId])->one();
            $temporaryAssign->replaced_teacher_detail_id = $teacherId;
            // var_dump($temporaryAssign);exit;
            if ($temporaryAssign->save(false)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
