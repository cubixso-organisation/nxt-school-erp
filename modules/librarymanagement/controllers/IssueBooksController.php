<?php

namespace app\modules\librarymanagement\controllers;

use Yii;
use app\models\User;
use app\modules\librarymanagement\models\base\LibraryBooksLogs;
use app\modules\librarymanagement\models\base\LibraryMembers;
use app\modules\librarymanagement\models\IssueBooks;
use app\modules\librarymanagement\models\LibraryBooks;
use app\modules\librarymanagement\models\search\IssueBooksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IssueBooksController implements the CRUD actions for IssueBooks model.
 */
class IssueBooksController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'update-status', 'get-data', 'status-change', 'get-member-data'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isInstituteAdmin() || User::isCampusAdmin() || User::isLibraryManager();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'pdf', 'update-status', 'get-data', 'status-change'],
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
     * Lists all IssueBooks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IssueBooksSearch();





        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {

            $dataProvider = $searchModel->campusAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->institutesSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) {
            $dataProvider = $searchModel->campusSubAdminSearch(Yii::$app->request->queryParams);
        } elseif (Yii::$app->user->identity->user_role == User::ROLE_LIBRARIAN) {
            $dataProvider = $searchModel->librarainSearch(Yii::$app->request->queryParams);
        }





        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single IssueBooks model.
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
     * Creates a new IssueBooks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new IssueBooks();

        if ($model->load(Yii::$app->request->post())) {
            // Access the selected value from the dropdown
            $libraryMemberId = $model->library_member_id;

            if ($model->save()) {

                // Rest of your existing code...

                $book = $model->book;
                if ($book) {
                    $book->updateCounters(['available' => -1]);
                }

                $libraryBooksLog = new LibraryBooksLogs();
                $libraryBooksLog->issue_books_id = $model->id;
                $libraryBooksLog->library_book_id = $model->book_id;
                $libraryBooksLog->library_school_wise_id = $model->library_id;
                $libraryBooksLog->book_issued_date = ($model->issued_date);
                $libraryBooksLog->book_due_date = date('Y-m-d', strtotime($model->due_date));
                $libraryBooksLog->book_return_date = date('Y-m-d', strtotime($model->returned_date));
                $libraryBooksLog->created_on = date('Y-m-d H:i:s');
                $libraryBooksLog->created_user_id = Yii::$app->user->id;

                $libraryBooksLog->save(false);

                Yii::$app->session->setFlash('success', 'Issue Books created successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to create Issue Books.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }




    /**
     * Updates an existing IssueBooks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $oldStatus = $model->oldAttributes['status']; // Get the old status before updating

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            // Check if the status was updated to 'Returned'
            if ($model->status == IssueBooks::STATUS_DELETE && $oldStatus != IssueBooks::STATUS_DELETE) {
                // Increment available count in LibraryBooks model
                $book = $model->book; // Assuming `book` is the name of your relation
                if ($book) {
                    $book->updateCounters(['available' => 1]);
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
     * Deletes an existing IssueBooks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = IssueBooks::STATUS_DELETE;
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
            $model = IssueBooks::find()->where([
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


    function actionGetMemberData($library_member_id)
    {
        $data = [];
        $book = LibraryMembers::find()->where(['id' => $library_member_id])->one();
        $data = [
            'libraryCardNo' => $book->library_card_no,


        ];
        return json_encode($data);
    }
    function actionGetData($book_id)
    {
        $data = [];
        $book = LibraryBooks::find()->where(['id' => $book_id])->one();
        $data = [
            'author' => $book->author,
            'subject' => $book->subject,
            'serial_no' => $book->book_number,
        ];
        return json_encode($data);
    }
    /**
     * Finds the IssueBooks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IssueBooks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IssueBooks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    public function actionStatusChange()
    {
        $post = \Yii::$app->request->post();

        if (!empty($post['id'])) {
            $transaction = IssueBooks::find()->where(['id' => $post['id']])->one();

            if (!empty($transaction)) {
                // Save the old status for comparison
                $oldStatus = $transaction->status;

                // Update the status
                $transaction->status = $post['val'];

                // Check if the status is updated to "Returned" (status 2)
                if ($oldStatus != 2 && $transaction->status == 2) {
                    // Save the current date as the returned date
                    $transaction->returned_date = date('Y-m-d H:i:s');

                    // Increment the available column in the associated LibraryBooks model
                    $libraryBook = $transaction->book;
                    if ($libraryBook) {
                        $libraryBook->updateCounters(['available' => 1]);
                    }
                }

                // Save the transaction
                if ($transaction->update(false)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}
