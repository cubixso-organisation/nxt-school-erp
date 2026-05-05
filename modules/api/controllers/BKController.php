<?php

namespace app\modules\api\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\AccessRule;

abstract class BKController extends Controller {
	const API_OK = 'OK';
	const API_NOK = 'NOK';
	public function behaviors() {
		return [ 
				'access' => [ 
						'class' => AccessControl::className (),
						'ruleConfig' => [ 
								'class' => AccessRule::className () 
						],
						'rules' => [ 
								[ 
										'actions' => [ 
												'index' 
										],
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								],
								[ 
										'actions' => [ 
												'index' 
										],
										'allow' => true,
										'roles' => [ 
												'?',
												'@' 
										] 
								] 
						] 
				],
				
				'verbs' => [ 
						'class' => \yii\filters\VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post',
										'delete' 
								] 
						] 
				] 
		];
	}
	public $enableCsrfValidation = false;
	private $resp = [ 
			'status' => self::API_NOK 
	];
	private $user_id;
	public function beforeAction($action) {
		$this->resp ['url'] = \yii::$app->request->pathInfo;
		return parent::beforeAction ( $action );
	}
	public function sendJsonResponse($data = null) {
		if ($data != null)
			$this->resp = ArrayHelper::merge ( $this->resp, $data );
		
		return $this->resp;
	}
}
