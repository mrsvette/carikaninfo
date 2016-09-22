<?php

class ClientsController extends EController
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
				'actions'=>array('view','manage','login'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('create','createGroup'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('update','group','updateGroup'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete','deleteGroup'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
			),
			array('allow',
				'actions'=>array('getcity','confirmEmail'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

		/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$criteria = new CDbCriteria;
		$criteria->compare('client_id',$model->id);
		$del1 = ModClientOrder::model()->deleteAll($criteria);
		$del2 = ModInvoice::model()->deleteAll($criteria);
		Yii::import('application.modules.email.models.*');
		$del3 = ModActivityClientEmail::model()->deleteAll($criteria);
		$del4 = ExtensionMeta::model()->deleteAll($criteria);
		$del5 = $model->delete();
		
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
		$criteria->order='id DESC';
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModClient'])){
			foreach($_GET['ModClient'] as $attr1=>$val1){
				if(!empty($val1))
					$criteria->compare($attr1,$val1,true);
			}
		}

		$dataProvider=new CActiveDataProvider('ModClient',array('criteria'=>$criteria));

		$criteria2=new CDbCriteria;
		$criteria2->order='id DESC';
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModClientGroup'])){
			foreach($_GET['ModClientGroup'] as $attr=>$val){
				if(!empty($val))
					$criteria2->compare($attr,$val,true);
			}
		}
		$groupProvider=new CActiveDataProvider('ModClientGroup',array('criteria'=>$criteria2));

		$this->render('view',array(
			'dataProvider'=>$dataProvider,
			'groupProvider'=>$groupProvider,
		));
	}

	public function actionManage($id)
	{
		$model = ModClient::model()->findByPk($id);
		if(!$model instanceof ModClient)
			throw new CHttpException(404,'The requested page does not exist.');

		$criteria = new CDbCriteria;
		$criteria->compare('client_id',$model->id);
		$criteria->order = 'id DESC';

		$orderProvider = new CActiveDataProvider('ModClientOrder',array('criteria'=>$criteria));

		$invoiceProvider = new CActiveDataProvider('ModInvoice',array('criteria'=>$criteria));

		$this->render('manage',array(
			'model'=>$model,
			'orderProvider'=>$orderProvider,
			'invoiceProvider'=>$invoiceProvider
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
			$model=new ModClient;
			$model->attributes=$_POST['ModClient'];
			$model->salt=md5($model->generateSalt());
			$model->ip=$_SERVER['REMOTE_ADDR'];
			$model->date_entry=date(c);
			if($model->save()){
				$model->password=$model->hashPassword($model->password,$model->salt);
				$model->update(array('password'));
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_client',array('model'=>$model),true,true),
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
			$model=ModClient::model()->findByPk($id);
			$model->attributes=$_POST['ModClient'];
			$model->date_update=date(c);
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_client',array('model'=>$model),true,true),
				));
			}
			exit;
		}
	}

	public function actionLogin($id)
	{
		$model= $this->loadModel($id);
		
		var_dump($model);exit;
	}

	public function actionCreateGroup()
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model=new ModClientGroup;
			$model->attributes=$_POST['ModClientGroup'];
			$model->date_entry=date(c);
			$model->user_entry=Yii::app()->user->id;
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_group',array('model'=>$model),true,true),
				));
			}
			exit;
		}
	}

	public function actionUpdateGroup($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model=ModClientGroup::model()->findByPk($id);
			$model->attributes=$_POST['ModClientGroup'];
			$model->date_update=date(c);
			$model->user_update=Yii::app()->user->id;
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_group',array('model'=>$model),true,true),
				));
			}
			exit;
		}
	}

	public function actionDeleteGroup($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model=ModClientGroup::model()->findByPk($id);
			$model->delete();
		}
	}

	public function actionGroup($id)
	{
		$model = ModClientGroup::model()->findByPk($id);
		if(!$model instanceof ModClientGroup)
			throw new CHttpException(404,'The requested page does not exist.');
		$this->render('group',array('model'=>$model));
	}

	public function actionGetcity()
	{
		if(Yii::app()->request->isAjaxRequest){
			if(isset($_POST['province_id'])){
				// Stop jQuery from re-initialization
				Yii::app()->clientScript->scriptMap['jquery.js'] = false;
				$model=new ModClient;
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>CHtml::activeDropDownList($model,'city',ModCity::items($_POST['province_id']),array('class'=>'form-control')),
				));
				exit;
			}
		}
	}

	public function actionConfirmEmail($hash)
	{
		$this->layout = 'column1';

		$criteria = new CDbCriteria;
		$criteria->compare('meta_key','confirm_email');
		$criteria->compare('meta_value',$hash);

		$model = ExtensionMeta::model()->find($criteria);
		if($model instanceof ExtensionMeta){
			$client = $this->loadModel($model->client_id);
			$client->status = ModClient::ACTIVE;
			$client->date_update = date(c);
			if($client->save()){
				Yii::app()->user->setFlash('confirm',Yii::t('EcommerceModule.client','Your email is succesfully confirmed.'));
				$this->render('confirm_email');
			}
		}else
			throw new CHttpException(404,'The requested page does not exist.');
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
		$model=ModClient::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
