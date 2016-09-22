<?php

class DefaultController extends EController
{
	public $layout='//layouts/column1';

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
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('index','logout','plot'),
				'users'=>array('@'),
			),
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('error','login'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$this->layout='column1';
		//var_dump(Visitor::getAverageDuration());exit;
		$this->render('index');
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout='column1';

		$model=new AdminLoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['AdminLoginForm']))
		{
			$model->attributes=$_POST['AdminLoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('default/index'));
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

	public function actionPlot()
	{
	    if(Yii::app()->request->isAjaxRequest)
	    {
	    	echo CJSON::encode(array(
				'status'=>'success',
				'visitor'=>json_encode(PcounterUsers::getStatistikMonthly('visitor')),
				'page_viewed'=>json_encode(PcounterUsers::getStatistikMonthly('page-viewed'))
			));
	    }
	}
}
