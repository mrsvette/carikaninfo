<?php

class SummaryController extends EController
{
	
	public static $_alias='Summary Report';

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('realtime','audiences','acquisition'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('graphPerMinute','onlineUser','browserPercentage','pageViewInterval'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('error'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionRealtime()
	{
		//$mobile_detect=new MobileDetect;
		//var_dump(Visitor::getBrowserPercentage());exit;
		$dataProvider=new CArrayDataProvider(Visitor::getActivePages(), array(
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		$this->render('realtime',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAudiences()
	{
		
		//var_dump(Visitor::getDailyTraffic());exit;
		$this->render('audiences',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAcquisition()
	{
		
		$this->render('acquisition',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionGraphPerMinute()
	{
		if(Yii::app()->request->isPostRequest)
		{
			echo CJSON::encode(array(
				'status'=>'success',
				'result'=>Visitor::getGraphPerMinute()
			));
		}
	}

	public function actionOnlineUser()
	{
		if(Yii::app()->request->isPostRequest)
		{
			echo CJSON::encode(array(
				'status'=>'success',
				'result'=>Visitor::getActiveVisitor()
			));
		}
	}

	public function actionBrowserPercentage()
	{
		if(Yii::app()->request->isPostRequest)
		{
			echo CJSON::encode(array(
				'status'=>'success',
				'mobile'=>Visitor::getBrowserPercentage(),
				'desktop'=>Visitor::getBrowserPercentage(0)
			));
		}
	}

	/** get the chart interval of page view */
	public function actionPageViewInterval()
	{
		if(Yii::app()->request->isPostRequest)
		{
			echo CJSON::encode(array(
				'status'=>'success',
				'result'=>Visitor::getPageViewInterval(),
			));
		}
	}

	public function actionError()
	{
		$this->layout='column1';
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Visitor::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
