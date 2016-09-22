<?php

class InvoicesController extends EController
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
				'actions'=>array('create','markAsPaid','refund'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('update','manage','addItems'),
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

	/**
	 * Manages all models.
	 */
	public function actionView()
	{
		$criteria=new CDbCriteria;
		if(isset($_GET['status']))
			$criteria->compare('t.status',$_GET['status']);
		$criteria->order='t.id ASC';
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModInvoice'])){
			$rel_count = 0;
			foreach($_GET['ModInvoice'] as $attr1=>$val1){
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

		$dataProvider=new CActiveDataProvider('ModInvoice',array('criteria'=>$criteria));
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModInvoice'])){
			$dataProvider->model->attributes = $_GET['ModInvoice'];
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
			if(isset($_GET['order_id']))
				$invoice = ModInvoice::createFromOrder(array('id'=>$_GET['order_id']));
			else{
				$model = new ModInvoice;
				$client = ModClient::model()->findByPk($_POST['client_id']);
				$model->client_id = $client->id;
				$model->serie = Extension::getConfigByModule('ecommerce','invoice_series');
				$model->currency = $client->currency;
				$model->currency_rate = 1;
				$model->credit = 0;
				$model->base_refund = 0;
				$model->refund = 0;
				$model->notes = '';
				$model->status = ModInvoice::STATUS_UNPAID;
				$model->seller_company = Extension::getConfigByModule('ecommerce','invoice_company_name');
				$model->seller_company_vat = Extension::getConfigByModule('ecommerce','invoice_company_vat');
				$model->seller_company_number = Extension::getConfigByModule('ecommerce','invoice_company_number');
				$model->seller_address = Extension::getConfigByModule('ecommerce','invoice_company_address');
				$model->seller_phone = Extension::getConfigByModule('ecommerce','invoice_company_phone');
				$model->seller_email = Extension::getConfigByModule('ecommerce','invoice_company_email');
				$model->buyer_first_name = $client->first_name;
				$model->buyer_last_name = $client->last_name;
				$model->buyer_company = $client->company;
				$model->buyer_company_vat = $client->company_vat;
				$model->buyer_company_number = $client->company_number;
				$model->buyer_address = $client->address_1;
				$model->buyer_city = $client->city;
				$model->buyer_state = $client->state;
				$model->buyer_country = $client->country;
				$model->buyer_zip = $client->postcode;
				$model->buyer_phone = $client->phone;
				$model->buyer_phone_cc = $client->phone_cc;
				$model->buyer_email = $client->email;
				$model->gateway_id = 1;
				$model->approved = 1;
				$model->taxname = '';
				$model->taxrate = '';
				$model->due_at = date(c); //perlu tambahan config due
				$model->reminded_at = null;
				$model->paid_at = null;
				$model->date_entry = date(c);
				if($model->save()){
					//update the invoice
					$model->nr = $model->id;
					$model->hash = md5($model->id);
					$model->base_income = $model2->price * $model2->quantity;
					$model->update(array('nr','hash','base_income'));
				}else{
					echo CJSON::encode(array(
						'status'=>'failed',
						'div'=>$this->renderPartial('_form_invoice',array('model'=>$model),true,true),
					));
					exit;
				} 
				$invoice = $model;
			}
			if($invoice instanceof ModInvoice){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
					'href'=>Yii::app()->createUrl('/ecommerce/invoices/manage',array('id'=>$invoice->id)),
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
			$model->attributes=$_POST['ModInvoice'];
			$model->date_update=date(c);
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
					'refresh'=>true,
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
		if(isset($_GET['status']))
			$criteria->compare('t.status',$_GET['status']);
		$criteria->order='t.id ASC';

		$dataProvider=new CActiveDataProvider('ModInvoice',array('criteria'=>$criteria));

		$this->render('manage',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionMarkAsPaid($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = $this->loadModel($id);
			$execute = $model->markAsPaid();
			if($execute){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
					'refresh'=>true,
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed'
				));
			}
			exit;
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if($this->loadModel($id)->delete()){
			//delete the invoice items
			$delete_items = ModInvoiceItem::model()->deleteAllByAttributes(array('invoice_id'=>$id));
			//update all the order related to this invoice
			$criteria = new CDbCriteria;
			$criteria->compare('unpaid_invoice_id',$id);
			$count = ModClientOrder::model()->count($criteria);
			if($count>0){
				$orders = ModClientOrder::model()->findAll($criteria);
				foreach($orders as $order){
					$order->unpaid_invoice_id = null;
					$order->date_update = date(c);
					$order->user_update = Yii::app()->user->id;
					$order->update(array('unpaid_invoice_id','date_update','user_update'));
				}
			}
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view'));
	}

	public function actionRefund($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = $this->loadModel($id);
			$invoice = $model->refund();
			if($invoice instanceof ModInvoice){
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
					'refresh'=>false,
					'href'=>Yii::app()->createUrl('/ecommerce/invoices/manage',array('id'=>$invoice->id)),
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed'
				));
			}
			exit;
		}
	}

	public function actionAddItems($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = $this->loadModel($id);
			if(is_array($_POST['title'])){
				$base_income = 0;
				foreach($_POST['title'] as $index=>$title){
					if(!empty($title)){
						$model2 = new ModInvoiceItem;
						$model2->invoice_id = $model->id;
						$model2->type = 'custom';
						$model2->status = ModInvoiceItem::STATUS_PENDING_PAYMENT;
						$model2->rel_id = null;
						$model2->task = ModInvoiceItem::TASK_VOID;
						$model2->title = $title;
						$model2->period = null;
						$model2->quantity = ($_POST['quantity'][$index]>0)? $_POST['quantity'][$index] : 1;
						$model2->unit = null;
						$model2->price = $_POST['price'][$index];
						$model2->date_entry = date(c);
						if($model2->save())
							$base_income += $model2->quantity*$model2->price;
					}
				}
				//update the invoice
				$model->base_income = $base_income;
				$model->date_update = date(c);
				$model->update(array('base_income','date_update'));
				echo CJSON::encode(array(
						'status'=>'success',
						'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
						'status'=>'failed',
				));
			}
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
		$model=ModInvoice::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
