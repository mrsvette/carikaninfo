<?php

class SDefaultController extends EController
{
	
	public static $_alias='Slide Show Default';

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
				'actions'=>array('create','createItem'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('index','view','preview'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('update','updateStatus','updateItem'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete','deleteItem'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
			),
			array('allow',
				'actions'=>array('assets'),
				'users'=>array('*'),
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ModSlideShow;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ModSlideShow']))
		{
			$model->attributes=$_POST['ModSlideShow'];
			$model->slug = Post::createSlug($model->name);
			$model->date_entry=date(c);
			$model->user_entry=Yii::app()->user->id;
			if($model->save())
				$this->redirect(array('view'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$model2 = new ModSlideShowItem;
		$model2->slide_show_id = $model->id;
		if(Yii::app()->request->isPostRequest)
		{
			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);

			if(isset($_POST['ModSlideShow']))
			{
				$model->attributes=$_POST['ModSlideShow'];
				$model->date_update=date(c);
				$model->user_update=Yii::app()->user->id;
				if($model->save()){
					echo CJSON::encode(array('status'=>'success','div'=>'Data berhasil disimpan'));
				}else{
					echo CJSON::encode(array('status'=>'failed','div'=>$this->renderPartial('_form',array('model'=>$model),true,true)));
				}
				exit;
			}
		}
		$this->render('update',array(
			'model'=>$model,
			'model2'=>$model2,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model=$this->loadModel($id);
			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionView()
	{
		$model=new ModSlideShow('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ModSlideShow']))
			$model->attributes=$_GET['ModSlideShow'];

		$this->render('view',array(
			'model'=>$model,
		));
	}

	public function actionUpdateStatus()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow update via POST request
			$model = $this->loadModel($_POST['id']);
			$model->status = $_POST['status'];
			$model->date_update = date(c);
			$model->user_update = Yii::app()->user->id;
			if($model->save()){
				echo CJSON::encode(array('status'=>'success'));
			}else{
				echo CJSON::encode(array('status'=>'failed','errors'=>$model->errors));
			}
			exit;
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionCreateItem()
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model=new ModSlideShowItem('create');
			$model->attributes=$_POST['ModSlideShowItem'];
			$model->order = $model->nextOrder;
			$model->date_entry=date(c);
			$model->user_entry=Yii::app()->user->id;
			if($model->save()){
				$file=CUploadedFile::getInstance($model,'image_path');
				$extension = pathinfo($file->name, PATHINFO_EXTENSION);
				if(!empty($file)){
					$path = 'uploads/images/sliders/';
					$model->image_path = $path.$model->slide_show_rel->slug.'-'.time().'.'.$extension;
					if($file->saveAs($model->image_path)){ //upload image
						$model->save();
					}
				}
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
					'image'=>(!empty($model->image_path))? CHtml::image(Yii::app()->request->baseUrl.'/'.$model->image_path) : false,
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_item',array('model'=>$model),true,true),
				));
			}
			exit;
		}
	}


	public function actionUpdateItem($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = ModSlideShowItem::model()->findByPk($id);
			if(!$model instanceof ModSlideShowItem)
				throw new CHttpException(404,'The requested page does not exist.');
			$img_lama = $model->image_path;
			if(isset($_POST['ModSlideShowItem'])){
				$model->attributes=$_POST['ModSlideShowItem'];
				$model->date_update=date(c);
				$model->user_update=Yii::app()->user->id;
				if($model->update(array('title','caption','url','date_update','user_update'))){
					$file=CUploadedFile::getInstance($model,'image_path');
					$extension = pathinfo($file->name, PATHINFO_EXTENSION);
					if(!empty($file)){
						$path = 'uploads/images/sliders/';
						if(file_exists($img_lama))
							unlink($img_lama);
						$model->image_path = $path.$model->slide_show_rel->slug.'-'.time().'.'.$extension;
						if($file->saveAs($model->image_path)){ //upload image
							$model->save();
						}
					}
					echo CJSON::encode(array(
						'status'=>'success',
						'div'=>'Data berhasil disimpan',
						'image'=>(!empty($model->image_path))? CHtml::image(Yii::app()->request->baseUrl.'/'.$model->image_path) : false,
					));
				}else{
					echo CJSON::encode(array(
						'status'=>'failed',
						'div'=>$this->renderPartial('_item',array('model'=>$model),true,true),
					));
				}
				exit;
			}
			echo CJSON::encode(array(
					'status'=>'success',
					'div'=>$this->renderPartial('_item_form',array('model'=>$model),true,true),
			));	exit;
		}
	}

	public function actionDeleteItem($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = ModSlideShowItem::model()->findByPk($id);
			if(!$model instanceof ModSlideShowItem)
				throw new CHttpException(404,'The requested page does not exist.');
			$img_path = $model->image_path;
			if($model->delete()){
				if(file_exists($img_path))
					unlink($img_path);
				echo CJSON::encode(array('status'=>'success'));	
			}
			exit;
		}
	}

	public function actionPreview($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = ModSlideShow::model()->findByPk($id);
			if(!$model instanceof ModSlideShow)
				throw new CHttpException(404,'The requested page does not exist.');
			
			echo CJSON::encode(array(
					'status'=>'success',
					'div'=>$this->renderPartial('_preview',array('model'=>$model),true,true),
			));	exit;
		}
	}


	public function actionAssets()
	{
		$assets = Yii::getPathOfAlias('application.modules.slideshow.components.assets');
		$file = $assets.'/'.$_GET['type'].'/'.$_GET['file'];
		if(file_exists($assets.'/'.$_GET['type'].'/'.$_GET['file'])){
			$result = file_get_contents($file);
			echo $result; 
			exit;
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ModSlideShow::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='slidesow-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
