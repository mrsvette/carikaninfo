<?php

class ChangePasswordController extends EController
{
	public $layout='column2';
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'expression'=>'Rbac::ruleAccess()',
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('captcha'),
				'expression'=>'!Yii::app()->user->isGuest',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionIndex()
	{
		$model=new PasswordForm('change');

		if(isset($_POST['ajax']) && $_POST['ajax']==='changepass-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		if(isset($_POST['PasswordForm']))
		{
			$model->attributes=$_POST['PasswordForm'];
			
			if($model->validate()){
				$agt=User::model()->findByPk(Yii::app()->user->id);
				$agt->password=md5($agt->salt.$model->passwordbr);
				if($agt->update('password')){
					Yii::app()->user->setFlash('changepass','Your password was successfully changed.');
					$this->refresh();
				}
			}
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}

}
