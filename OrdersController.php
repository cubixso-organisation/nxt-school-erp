<?php

namespace app\modules\admin\controllers;

use app\components\Payu;
use app\modules\admin\models\base\DeliveryAddress;
use app\modules\admin\models\base\Notification;
use app\modules\admin\models\base\SubscriptionCalendar;
use Yii;
use app\models\User;
use app\modules\admin\models\base\Store;
use app\modules\admin\models\base\SubscriptionOrders;
use app\modules\admin\models\DriverRequest;
use app\modules\admin\models\Orders;
use app\modules\admin\models\search\SubscriptionOrdersSearch;
use app\modules\admin\models\OrdersSearch;
use app\modules\admin\models\OrderStatus;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Porter;
use app\modules\admin\models\DriverDetails;
use app\modules\admin\models\DunzoTaskLog;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'update-status',
                            'new-order',
                            'proceed',
                            'delivered',
                            'dispatch',
                            'pdf-invoice',
                            'schedule',
                            'cancelled',
                            'update-order-status',
                            'get-delivery-boys',
                            'get-store-drivers',
                            'assign-delivery-boy',
                            'normal-transactions',
                            'subscription-transactions',
                            'get-payment-status',
                            'export-csv',
                            'create-porter-task'
                        ],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isSubAdmin();
                        }

                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'pdf',
                            'get-payment-status',
                            'update-status',
                            'new-order',
                            'proceed',
                            'delivered',
                            'dispatch',
                            'pdf-invoice',
                            'schedule',
                            'cancelled',
                            'update-order-status',
                            'get-delivery-boys',
                            'get-store-drivers',
                            'assign-delivery-boy',
                            'export-csv',
                            'create-porter-task'
                        ],
                        'matchCallback' => function () {
                            return User::isManager();
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
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->managersearch(Yii::$app->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionNewOrder()
    {
        $searchModel = new OrdersSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Orders::STATUS_NEWORDER);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->managersearch(Yii::$app->request->queryParams, Orders::STATUS_NEWORDER);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_VENDOR) {
            $dataProvider = $searchModel->vendorSearch(Yii::$app->request->queryParams, ORDERS::STATUS_NEWORDER);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionProceed()
    {
        $searchModel = new OrdersSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Orders::STATUS_ACCEPTED);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->managersearch(Yii::$app->request->queryParams, Orders::STATUS_ACCEPTED);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_VENDOR) {
            $dataProvider = $searchModel->vendorSearch(Yii::$app->request->queryParams, Orders::STATUS_ACCEPTED);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionDispatch()
    {
        $searchModel = new OrdersSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Orders::STATUS_DELIVERY_BOY_PICKED, Orders::STATUS_ASSIGNED_DELIVERY_BOY, Orders::STATUS_ONTHE_WAY);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->managersearch(Yii::$app->request->queryParams, Orders::STATUS_DELIVERY_BOY_PICKED);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_VENDOR) {
            $dataProvider = $searchModel->vendorSearch(Yii::$app->request->queryParams, Orders::STATUS_DELIVERY_BOY_PICKED);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelivered()
    {
        $searchModel = new OrdersSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Orders::STATUS_DELIVERED);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->managersearch(Yii::$app->request->queryParams, Orders::STATUS_DELIVERED);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_VENDOR) {
            $dataProvider = $searchModel->vendorSearch(Yii::$app->request->queryParams, Orders::STATUS_DELIVERED);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCancelled()
    {
        $searchModel = new OrdersSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Orders::STATUS_CANCELLED_BY_OWNER);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->managersearch(Yii::$app->request->queryParams, Orders::STATUS_DELIVERED);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_VENDOR) {
            $dataProvider = $searchModel->vendorSearch(Yii::$app->request->queryParams, Orders::STATUS_DELIVERED);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSchedule()
    {
        $searchModel = new OrdersSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->scheduleSearch(Yii::$app->request->queryParams, Orders::ORDER_TYPE_SCHEDULE);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->scheduleSearch(Yii::$app->request->queryParams, Orders::ORDER_TYPE_SCHEDULE);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_VENDOR) {
            $dataProvider = $searchModel->scheduleSearch(Yii::$app->request->queryParams, Orders::ORDER_TYPE_SCHEDULE);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Orders model.
     * 
     * @return mixed
     */

    public function actionView($id)
    {
        $model = Orders::find()->where(['id' => $id])->one();
        $store = Store::find()->Where(['owner_id' => \Yii::$app->user->identity->id])->one();

        $providerOrderDetails = new \yii\data\ArrayDataProvider([
            'allModels' => $model->orderDetail,
        ]);
        // Mark the notification as read
        $notification = Notification::findOne(['order_id' => $id, 'mark_read' => 0]);
        if ($notification) {
            $notification->mark_read = 1;
            $notification->save(false); // Save without validation
        }
        return $this->render('invoiceView', [
            'model' => $model,
            'store' => $store,
            'providerOrderDetails' => $providerOrderDetails
        ]);
    }





    // public function actionView($id)
    // {

    //     if (\Yii::$app->user->identity->user_role == User::isVendor()) {
    //         $store = Store::find()->Where(['owner_id' => \Yii::$app->user->identity->id])
    //                      ->one();
    //             // var_dump($store);exit;
    //         $model = Orders::find()->Where(['store_id' => $store->id])
    //             ->andWhere(['id' => $id])->one();
    //     } else {
    //         $model =Orders::find()
    //         ->where(['id' => $id])->one();
    //         // var_dump($id);exit;
    //     }

    //     // echo $model->createCommand()->getRawSql(); exit;
    //     if (!empty($model)) {

    //         return $this->render('invoiceView', [
    //             'model' => $this->findModel($id),
    //                  ]);
    //     } else {
    //         throw new NotFoundHttpException(Yii::t('app', 'This order id doesnt exist for your store'));
    //     }
    // }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    private function pushToFirebase($orderId)
    {
        try {
            $serviceAccountPath = Yii::getAlias('alladin-ice-app.firebaseapp.com'); // Adjust this path as needed

            $factory = (new Factory)->withServiceAccount($serviceAccountPath);
            $database = $factory->createDatabase();

            $newNotification = [
                'order_id' => $orderId,
                'read' => false,
                'timestamp' => time(),
            ];

            $database->getReference('notifications')->push($newNotification);
        } catch (\Exception $e) {
            Yii::error("Firebase push failed: " . $e->getMessage(), __METHOD__);
        }
    }



    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * 
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view',]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if (!empty($model)) {
            $model->status = Orders::STATUS_DELETE;
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
            $model = Orders::find()->where([
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
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne([])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }




    public function actionPdfInvoice($id)
    {
        // var_dump($id);exit;
        $model = Orders::find()->where(['id' => $id])->one();
        // var_dump($model);exit;

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'content' => $this->renderPartial('pdf_invoice', ['model' => $model]),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

            'methods' => [
                // '    
                // 'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                // 'SetHeader' => ['Alladin Ice Invoice||Generated On: ' . date("r")],

                // 'SetFooter' => ['|Page {PAGENO}|'],
                'SetTitle' => 'Order Invoice - Alladin',

                'SetAuthor' => 'Alladin',
            ],
            'marginTop' => 5,
            'marginBottom' => 5,
            'marginLeft' => 5,
            'marginRight' => 5,

        ]);
        return $pdf->render();
    }

    // Update Order Status
    public function actionUpdateOrderStatus()
    {
        $data = [];
        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = 'json';

        if (!empty($post['id'])) {
            $model = Orders::find()->where(['id' => $post['id']])->one();

            if (!empty($model)) {
                $previousStatus = $model->status; // Store the previous status
                $model->status = $post['val'];
                $model->save(false);
                $badge = $model->getOrderStatusOptionsBadges();
                // Create a new entry in the OrderStatus table
                $orderStatus = new OrderStatus();
                $orderStatus->order_id = $model->id;
                $orderStatus->driver_id = $model->driver_id ?? null; // Use driver_id if applicable
                $orderStatus->status = $model->status;
                $orderStatus->remarks = "Order status updated to {$badge} at " . date('Y-m-d H:i:s');
                $orderStatus->save(false);

                // Additional logic for specific statuses
                if ($model->status == Orders::STATUS_DELIVERED) {
                    $updateStock = (new Orders())->updateStock(Payu::order_type_subscription, $model->id);
                }

                if (
                    $model->status == Orders::STATUS_CANCELLED_BY_ADMIN || $model->status == Orders::STATUS_CANCELLED_BY_USER ||
                    $model->status == Orders::STATUS_CANCELLED_BY_OWNER || $model->status == Orders::STATUS_CANCELLED_BY_DELIVERY_BOY
                ) {
                    $title = "Order Cancelled";
                    $msg = "Your order with ID# {$model->id} has been cancelled.";
                    Yii::$app->notification->UserNotification($model->id, $model->user_id, $title, $msg, 'redirect');
                }

                $data['message'] = "Order status updated successfully.";
                $data['id'] = $model->status;
            } else {
                $data['message'] = "Order not found.";
            }
        } else {
            $data['message'] = "Invalid request.";
        }

        return $data;
    }

    // Get Delivery Boys
    // public function actionGetDeliveryBoys()
    // {
    //     \Yii::$app->response->format = 'json';
    //     $post = \Yii::$app->request->post();
    //     //Get Store id
    //     $order = Orders::find()->Where(['id' => $post['id']])

    //         ->one();
    //     $source_lat = $order->store->lat; //$post['source_lat'];
    //     $source_lng = $order->store->lng; //$post['source_lng'];

    //     if (User::isAdmin() || User::isSubAdmin()) {
    //         $nearby_drivers = User::find()
    //             ->joinWith('driverDetails as dd')
    //             ->select("*,	( 6371 * acos( cos( radians({$source_lat}) ) *
    //     cos( radians( `lat` ) ) * cos( radians( `lng` ) - radians({$source_lng}) ) +
    //     sin( radians({$source_lat}) ) * sin( radians( `lat` ) ) ) ) AS distance")->having("distance <:distance")->addParams([
    //                 ':distance' => isset($auto_dispatch_radius) ? $auto_dispatch_radius : 30, //distance kms
    //             ])->limit(isset($auto_dispatch_no_of_drivers) ? $auto_dispatch_no_of_drivers : 10)
    //             //->joinWith('alldrivers as dd')
    //             ->Where(['user_role' => User::ROLE_DRIVER])
    //             ->andWhere(['online_status' => User::ONLINE, 'user.status' => User::STATUS_ACTIVE])

    //             ->orderBy([
    //                 'distance' => SORT_ASC, //specify sort order ASC for ascending DESC for descending
    //             ])->andWhere(['dd.store_id' => $order->store_id])->all();
    //     }

    //     //    echo $nearby_drivers->createCommand()->getRawSql();exit;
    //     // var_dump($nearby_drivers);exit;
    //     if (!empty($nearby_drivers)) {
    //         $data['delivery_boy'] = $nearby_drivers;
    //         $data['order'] = $post['id'];
    //     } else {
    //         $data['error'] = "No delivery Boys";
    //         $data['order'] = $post['id'];
    //     }


    //     return $data;
    // }


    public function actionGetDeliveryBoys()
    {
        \Yii::$app->response->format = 'json';
        $post = \Yii::$app->request->post();
        
        // Validate input
        if (empty($post['id'])) {
            return [
                'status' => 'error',
                'message' => 'Order ID is required'
            ];
        }
        
        // Get Order with store information
        $order = Orders::find()
            ->with(['store'])
            ->where(['id' => $post['id']])
            ->one();
            
        if (!$order) {
            return [
                'status' => 'error',
                'message' => 'Order not found'
            ];
        }
        
        if (!$order->store) {
            return [
                'status' => 'error',
                'message' => 'Store not found for this order'
            ];
        }
        
        // Get drivers for the specific store
        $drivers = User::find()
            ->select(['user.id', 'user.username', 'user.first_name', 'user.last_name', 'user.contact_no', 'user.online_status'])
            ->joinWith('driverDetails as dd')
            ->where([
                'user.user_role' => User::ROLE_DRIVER,
                'user.online_status' => User::ONLINE,
                'dd.status' => DriverDetails::STATUS_ACTIVE,
                'dd.store_id' => $order->store_id
            ])
            ->orderBy(['user.id' => SORT_DESC])
            ->all();
        
        if (!empty($drivers)) {
            $driverData = [];
            foreach ($drivers as $driver) {
                $driverData[] = [
                    'id' => $driver->id,
                    'name' => $driver->first_name . ' ' . $driver->last_name,
                    'username' => $driver->username,
                    'contact_no' => $driver->contact_no,
                    'online_status' => $driver->online_status,
                    'store_id' => $order->store_id,
                    'store_name' => $order->store->name ?? 'Unknown Store'
                ];
            }
            
            return [
                'status' => 'success',
                'data' => [
                    'drivers' => $driverData,
                    'order_id' => $post['id'],
                    'store_id' => $order->store_id,
                    'store_name' => $order->store->name ?? 'Unknown Store',
                    'total_drivers' => count($drivers)
                ]
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'No delivery drivers available for this store',
                'data' => [
                    'order_id' => $post['id'],
                    'store_id' => $order->store_id,
                    'store_name' => $order->store->name ?? 'Unknown Store',
                    'total_drivers' => 0
                ]
            ];
        }
    }
    
    /**
     * Get all available drivers for a specific store
     * @return array
     */
    public function actionGetStoreDrivers()
    {
        \Yii::$app->response->format = 'json';
        $post = \Yii::$app->request->post();
        
        // Validate input
        if (empty($post['store_id'])) {
            return [
                'status' => 'error',
                'message' => 'Store ID is required'
            ];
        }
        
        // Get Store information
        $store = Store::find()
            ->where(['id' => $post['store_id']])
            ->one();
            
        if (!$store) {
            return [
                'status' => 'error',
                'message' => 'Store not found'
            ];
        }
        
        // Get drivers for the specific store
        $drivers = User::find()
            ->select(['user.id', 'user.username', 'user.first_name', 'user.last_name', 'user.contact_no', 'user.online_status'])
            ->joinWith('driverDetails as dd')
            ->where([
                'user.user_role' => User::ROLE_DRIVER,
                'user.online_status' => User::ONLINE,
                'dd.status' => DriverDetails::STATUS_ACTIVE,
                'dd.store_id' => $post['store_id']
            ])
            ->orderBy(['user.id' => SORT_DESC])
            ->all();
        
        if (!empty($drivers)) {
            $driverData = [];
            foreach ($drivers as $driver) {
                $driverData[] = [
                    'id' => $driver->id,
                    'name' => $driver->first_name . ' ' . $driver->last_name,
                    'username' => $driver->username,
                    'contact_no' => $driver->contact_no,
                    'online_status' => $driver->online_status,
                    'store_id' => $post['store_id'],
                    'store_name' => $store->name ?? 'Unknown Store'
                ];
            }
            
            return [
                'status' => 'success',
                'data' => [
                    'drivers' => $driverData,
                    'store_id' => $post['store_id'],
                    'store_name' => $store->name ?? 'Unknown Store',
                    'total_drivers' => count($drivers)
                ]
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'No delivery drivers available for this store',
                'data' => [
                    'store_id' => $post['store_id'],
                    'store_name' => $store->name ?? 'Unknown Store',
                    'total_drivers' => 0
                ]
            ];
        }
    }
    
    // Assign Delivery Boy

    public function actionAssignDeliveryBoy()
    {
        \Yii::$app->response->format = 'json';
        $ord = new Orders();
        // $setting = new Setting();
        $post = \Yii::$app->request->post();
        // var_dump($post);
        // exit;
        $orders = Orders::find()->where(['id' => $post['order_id']])->one();
        $orders->driver_id = $post['delivery_boy'];
        $orders->status = Orders::STATUS_ASSIGNED_DELIVERY_BOY;
        $orders->save(false);
        // $check_order = new DriverRequest();
        // $check_order->order_id = $post['order_id'];
        // $check_order->driver_id = $post['delivery_boy'];
        // $check_order->status = DriverRequest::STATUS_ACCEPTED;
        // if ($check_order->save(false)) {
        $orderStatus = new OrderStatus();
        $orderStatus->order_id = $post['order_id'];
        $orderStatus->driver_id = $post['delivery_boy'];
        $orderStatus->status = DriverRequest::STATUS_NEW_REQUEST;
        $orderStatus->remarks = "New Order of ID# " . $post['order_id'] . " assigned to Driver id# " . $post['delivery_boy'] . ' at ' . date('Y-m-d H:i:s');
        $orderStatus->save(false);
        $title = 'New order assigned manually';
        $msg = 'Hey, You got a new Order of ID# ' . $post['order_id'];
        //  var_dump($title); exit;
        $driverDetails = DriverDetails::find()->where(['id' => $post['delivery_boy']])->one();
        //  var_dump($title); exit;

        $send_noti = Yii::$app->notification->newFirebaseNotificationApi($orders->id, $driverDetails->user_id, $title, $msg, 'newOrder');
        // var_dump($send_noti);
        // exit;
        $data['status'] = 'OK';
        $data['msg'] = 'Order assigned';
        // } else {
        //     $data['status'] = 'NOK';
        //     $data['msg'] = 'Order assignment failed';
        // }
        return $data;
    }

    public function actionNormalTransactions()
    {
        $searchModel = new OrdersSearch();
        if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN || \Yii::$app->user->identity->user_role == User::ROLE_SUBADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else if (\Yii::$app->user->identity->user_role == User::ROLE_MANAGER) {
            $dataProvider = $searchModel->managersearch(Yii::$app->request->queryParams);
        }
        return $this->render('normal_transactions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }
    public function actionGetPaymentStatus($orderId)
    {
        $payAid = new Payu();
        $order = Orders::find()->where(['id' => (int)$orderId])->one();

        if (!empty($order)) {
            $data['api_key'] = '32cd3541-8ae3-47b0-a228-c501a54408b7';
            $data['order_id'] = $order->Payaid;
            $paymentStatus = $payAid->paymentStatus($data);

            if (isset($response_data['error'])) {
                $order->payment_status = Orders::PAYMENT_FAILED;
                $order->save(false);
                Yii::$app->session->setFlash('error', 'Payment was there in the transaction');
                return $this->redirect(Yii::$app->request->referrer);
            }

            if (!empty($paymentStatus)) {
                $decodeResponse = json_decode($paymentStatus);
                $paymentSuccess = false;
                if (!empty($decodeResponse->data)) {
                    foreach ($decodeResponse->data as $responseData) {
                        if ($responseData->response_message == "SUCCESS" && $responseData->authorization_staus == "captured") {
                            $order->payment_status = Orders::PAYMENT_DONE;
                            $order->save(false);
                            $paymentSuccess = true;
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Payment Was Failed Or Not Captured');
                    return $this->redirect(Yii::$app->request->referrer);
                }


                if ($paymentSuccess) {
                    Yii::$app->session->setFlash('success', 'Payment was successful');
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    Yii::$app->session->setFlash('error', 'Payment Was Failed Or Not Captured');
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
        } else {
            Yii::$app->session->setFlash('error', 'Order not found');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionExportCsv()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $orders = Orders::find()
            ->where(['in', 'DATE(created_on)', [$today, $yesterday]])
            ->all();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=today_yesterday_orders.csv');

        $fp = fopen('php://output', 'w');

        fputcsv($fp, ['Order ID', 'User Name', 'Qty', 'Total With Tax', 'Payment Status', 'Created On']);

        foreach ($orders as $order) {
            fputcsv($fp, [
                $order->id,
                $order->user->username ?? '',
                $order->qty,
                $order->total_w_tax,
                $order->payment_status,
                $order->created_on,
            ]);
        }

        fclose($fp);
        Yii::$app->end();
    }
    public function actionCreatePorterTask($order_id, $type = '')
    {
        $data = [];
        $param = [];
        $kay_arr = [];

        if (!empty($type == 1)) {
            // Subscription Order
            $orders = SubscriptionCalendar::find()->where(['id' => $order_id])->andWhere(['status' => SubscriptionCalendar::STATUS_PACKING])->one();

            if (empty($orders)) {
                Yii::$app->session->setFlash('error', 'Invalid Subscription Order or status not Packing.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $subOrd = SubscriptionOrders::findOne($orders->subscription_order_id);
            $store = Store::findOne($subOrd->store_id);
            $userAddress = DeliveryAddress::findOne($subOrd->delivery_r_id);

            // Check if delivery address is found
            if (empty($userAddress)) {
                Yii::$app->session->setFlash('error', 'No delivery address found.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $param['store_street'] = $store->street;
            $param['store_state'] = $store->state;
            $param['store_pin'] = $store->post_code;
            $param['store_lat'] = $store->lat;
            $param['store_lng'] = $store->lng;
            $param['store_phone'] = $store->store_phone;
            $param['store_name'] = $store->store_name;

            $param['drop_lat'] = $userAddress->latitude;
            $param['drop_lng'] = $userAddress->longitude;
            $param['drop_street'] = $userAddress->address;
            $param['drop_pincode'] = $userAddress->pincode;
            $param['drop_landmark'] = !empty($userAddress->land_mark) ? $userAddress->land_mark : 'na';

            $user = User::findOne($orders->user_id);
            $param['user_name'] = $user->first_name ?: $user->username;
            $param['user_contact'] = $user->contact_no;

            $param['orderAmt'] = $subOrd->total_w_tax;
        } else {
            // Normal Order
            $orders = Orders::findOne($order_id);

            if (empty($orders)) {
                Yii::$app->session->setFlash('error', 'Invalid Order.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $store = Store::findOne($orders->store_id);
            $userAddress = DeliveryAddress::findOne($orders->delivery_r_id);

            // Check if delivery address is found
            if (empty($userAddress)) {
                Yii::$app->session->setFlash('error', 'No delivery address found.');
                return $this->redirect(Yii::$app->request->referrer);
            }

            $param['store_street'] = $store->street;
            $param['store_state'] = $store->state;
            $param['store_pin'] = $store->post_code;
            $param['store_lat'] = $store->lat;
            $param['store_lng'] = $store->lng;
            $param['store_phone'] = $store->store_phone;
            $param['store_name'] = $store->store_name;

            $param['drop_lat'] = $userAddress->latitude;
            $param['drop_lng'] = $userAddress->longitude;
            $param['drop_street'] = $userAddress->address;
            $param['drop_pincode'] = $userAddress->pincode;
            $param['drop_landmark'] = !empty($userAddress->land_mark) ? $userAddress->land_mark : 'na';

            $user = User::findOne($orders->user_id);
            $param['user_name'] = $user->first_name ?: $user->username;
            $param['user_contact'] = $user->contact_no;

            $param['orderAmt'] = $orders->total_w_tax;
        }

        // Validation
        foreach ($param as $key => $value) {
            if (empty($value)) {
                $kay_arr[] = $key;
            }
        }

        if (!empty($kay_arr)) {
            Yii::$app->session->setFlash('error', 'Missing parameters: ' . implode(', ', $kay_arr));
            return $this->redirect(Yii::$app->request->referrer);
        }

        // Call Porter API
        $quote = (new Porter())->createTaskPorter($param);
        $qt = json_decode($quote);
        // var_dump($qt);
        // exit;
        if (!empty($qt) && !empty($qt->order_id)) {
            $orders->task_id = $qt->order_id;

            if (!empty($type == 1)) {
                $orders->status = SubscriptionCalendar::STATUS_ASSIGNED_DELIVERY_BOY;
                $orders->assign_type = SubscriptionCalendar::ASSIGN_BY_DUNZO;
            } else {
                $orders->status = Orders::STATUS_ASSIGNED_DELIVERY_BOY;
                $orders->assign_type = Orders::ASSIGN_BY_DUNZO;
            }

            $orders->save(false);

            // Save Log
            $dunzoTaskLog = new DunzoTaskLog();
            $dunzoTaskLog->order_id = $orders->id;
            $dunzoTaskLog->task_id = $qt->order_id;
            $dunzoTaskLog->order_type = 1;
            $dunzoTaskLog->order_status = $orders->status;
            $dunzoTaskLog->tracking_url = $qt->tracking_url;
            $dunzoTaskLog->save(false);

            Yii::$app->session->setFlash('success', 'Porter Task created successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to create Porter Task.');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
