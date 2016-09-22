<?php

class TemplatesController extends EController
{
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
			'postOnly + delete', // we only allow deletion via POST request
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
				'actions'=>array('index','view','manage','preview'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('create','setting','test'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('update','group'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$this->forward('view');
	}
		/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view'));
	}

	/**
	 * Manages all models.
	 */
	public function actionView()
	{
		$criteria=new CDbCriteria;
		$criteria->order='id ASC';
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModClient'])){
			foreach($_GET['ModClient'] as $attr1=>$val1){
				if(!empty($val1))
					$criteria->compare($attr1,$val1,true);
			}
		}

		$dataProvider=new CActiveDataProvider('ModEmailTemplate',array('criteria'=>$criteria));

		$criteria2 = new CDbCriteria;
		$criteria2->compare('name',Yii::app()->controller->module->id);
		$setting = Extension::model()->find($criteria2);

		$this->render('view',array(
			'dataProvider'=>$dataProvider,
			'setting'=>$setting,
		));
	}

	public function actionManage($id)
	{
		$model = $this->loadModel($id);
		
		$this->render('manage',array(
			'model'=>$model,
		));
	}

		/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = new ModEmailTemplate;
			$model->attributes = $_POST['ModEmailTemplate'];
			$model->date_entry = date(c);
			$model->user_entry = Yii::app()->user->id;
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_email',array('model'=>$model),true,true),
				));
			}
			exit;
		}
	}

	public function actionUpdate($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = $this->loadModel($id);

			$model->attributes = $_POST['ModEmailTemplate'];
			$model->date_update = date(c);
			$model->user_update = Yii::app()->user->id;
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_email',array('model'=>$model),true,true),
				));
			}
			exit;
		}
	}

	public function actionSetting()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$criteria = new CDbCriteria;
			$criteria->compare('name',$_POST['name']);
			$model = Extension::model()->find($criteria);
			if(!$model instanceof Extension)
				throw new CHttpException(404,'The requested page does not exist.');
			if(Yii::app()->request->isPostRequest){
				$save_configs = $model->saveConfig($_POST);
				if($save_configs){
					echo CJSON::encode(array(
						'status'=>'success',
						'div'=>Yii::t('global','Your config has been successfully saved.')
					));
					exit;
				}
			}
		}
	}

	/** 
	 * http://localhost/usahamikro-1.2/email/templates/test?code=mod_client_signup&to=mrsvette@localhost.com
	*/
	public function actionTest()
	{
		$model = new ModEmailTemplate;
		if(isset($_GET['dump']) && $_GET['dump']){
			$temp = ModEmailTemplate::model()->findByPk($_GET['id']);
			$vars = ModEmailTemplate::getVars($temp);
			$preview = $model->template_preview($vars);
			echo $preview;
		}else{
			//var_dump(Yii::app()->request->getBaseUrl(true));exit;
			$params = array('id'=>3,'password'=>123456);
			var_dump(EmailHook::onAfterClientSignUp($params));exit;
			$send = $model->template_send($_GET);
			var_dump($send);exit;
		}
	}

	public function actionPreview($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = $this->loadModel($id);
			
			echo CJSON::encode(array(
				'status'=>'success',
				'div'=>'<iframe src="'.Yii::app()->createUrl('/email/templates/test',array('code'=>$model->action_code,'id'=>$model->id,'dump'=>true)).'" width="100%" height="300px"></iframe>',
			));
			exit;
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Extension the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ModEmailTemplate::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
