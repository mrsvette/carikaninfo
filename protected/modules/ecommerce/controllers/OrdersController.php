<?php

class OrdersController extends EController
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
				'actions'=>array('view'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('create','activate'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('update','manage','suspend','unsuspend'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
			),
			array('allow',
				'actions'=>array('getListProductItems'),
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
		if(isset($_GET['status']))
			$criteria->compare('t.status',$_GET['status']);
		$criteria->order='t.id ASC';
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModClientOrder'])){
			$rel_count = 0;
			foreach($_GET['ModClientOrder'] as $attr1=>$val1){
				if(!empty($val1)){
					$client_attr = array(
						'company_search'=>array('company'),
						'client_search'=>array('first_name','last_name')
					);
					$product_attr = array(
						'product_search'=>array('title'),
					);
					if(in_array($attr1,array_keys($client_attr))){
						$criteria->compare('client_rel.'.$client_attr[$attr1][0],$val1,true);
						if(count($client_attr[$attr1])>1)
							$criteria->compare('client_rel.'.$client_attr[$attr1][1],$val1,true,'OR');
						$rel_count = $rel_count+1;
					}elseif(in_array($attr1,array_keys($product_attr))){
						$criteria->compare('product_rel.'.$product_attr[$attr1][0],$val1,true);
						$rel_count = $rel_count+1;
					}else{
						$criteria->compare('t.'.$attr1,$val1,true);
					}
				}
			}
			if($rel_count>0){
				$criteria->with=array('client_rel','product_rel');
				$criteria->together=true;
			}
		}

		$dataProvider=new CActiveDataProvider('ModClientOrder',array('criteria'=>$criteria));
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModClientOrder'])){
			$dataProvider->model->attributes = $_GET['ModClientOrder'];
		}

		$this->render('view',array(
			'dataProvider'=>$dataProvider,
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
			$model=new ModClientOrder;
			$model->attributes = $_POST['ModClientOrder'];
			$model->client_id = $_POST['client_id'];
			$product = ModProduct::model()->findByPk($model->product_id);
			$model->price = (is_object($product->pricing))? $product->pricing->{$model->period} : $product->pricing;
			$model->service_type = $product->type;
			$model->title = ucfirst(strtolower($model->service_type.' '.$product->title));
			$model->status = ModClientOrder::PENDING_SETUP;
			$model->date_entry=date(c);
			//var_dump($model->attributes);exit;
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_order',array('model'=>$model),true,true),
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
			$model->attributes=$_POST['ModClientOrder'];
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
					'div'=>$this->renderPartial('_form_manage',array('model'=>$model),true,true),
				));
			}
			exit;
		}
	}

	public function actionManage($id)
	{
		$model = $this->loadModel($id);

		$criteria=new CDbCriteria;
		$criteria->compare('item_rel.rel_id',$id);
		if(isset($_GET['status']))
			$criteria->compare('t.status',$_GET['status']);
		$criteria->order='t.id ASC';
		$criteria->with = array('item_rel');
		$criteria->together = true;

		$dataProvider=new CActiveDataProvider('ModInvoice',array('criteria'=>$criteria));

		$this->render('manage',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionActivate($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = $this->loadModel($id);
			if($model->activate()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}
			exit;
		}
	}

	public function actionSuspend($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = $this->loadModel($id);
			$model->status = ModClientOrder::SUSPENDED;
			$model->suspended_at = date(c);
			$model->date_update = date(c);
			$model->user_update = Yii::app()->user->id;
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}
			exit;
		}
	}

	public function actionUnsuspend($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = $this->loadModel($id);
			$model->status = ModClientOrder::PENDING_SETUP;
			$model->unsuspended_at = date(c);
			$model->date_update = date(c);
			$model->user_update = Yii::app()->user->id;
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}
			exit;
		}
	}

	public function actionGetListProductItems()
	{
		if(Yii::app()->request->isPostRequest){
			$model = new ModClientOrder;
			$items = ModProduct::items($_POST['product_category_id']);
			echo CJSON::encode(array(
					'status'=>'success',
					'div'=>CHtml::activeDropDownList($model,'product_id',$items,array('class'=>'form-control')),
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
		$model=ModClientOrder::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
