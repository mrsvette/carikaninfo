<?php

class GDefaultController extends EController
{
	public static $_alias='Manage Gallery';

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
				'actions'=>array('index','view','manage'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('create','setting','createCategory'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('update'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete','deleteCategory'),
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
		$model = $this->loadModel($id);
		//delete the image
		if($model->delete()){
			if(file_exists($model->src.$model->image))
				unlink($model->src.$model->image);
			if(file_exists($model->thumb.$model->image))
				unlink($model->thumb.$model->image);
		}
		
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
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModGallery'])){
			foreach($_GET['ModGallery'] as $attr1=>$val1){
				if(!empty($val1))
					$criteria->compare($attr1,$val1,true);
			}
		}

		$dataProvider=new CActiveDataProvider('ModGallery',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>5)));

		$criteria2 = new CDbCriteria;
		$criteria2->compare('name',Yii::app()->controller->module->id);
		$setting = Extension::model()->find($criteria2);

		$criteria3 = new CDbCriteria;
		$criteria3->order='id DESC';

		$categoryProvider=new CActiveDataProvider('ModGalleryCategory',array('criteria'=>$criteria3));

		$this->render('view',array(
			'dataProvider'=>$dataProvider,
			'setting'=>$setting,
			'categoryProvider'=>$categoryProvider,
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
			$model = new ModGallery('create');
			$model->attributes = $_POST['ModGallery'];
			//default path uploads/images/gallery
			$path = Extension::getConfigByModule('gallery','gallery_upload_path');
			$model->src = $path.'/';
			//default path uploads/images/gallery/_thumbs
			$path_thumbs = $path.'/_thumbs';
			$model->thumb = $path_thumbs.'/';
			$model->date_entry = date(c);
			$model->user_entry = Yii::app()->user->id;
			if($model->save()){
				$file = CUploadedFile::getInstance($model,'image');
				$extension = pathinfo($file->name, PATHINFO_EXTENSION);
				if(!empty($file)){
					$exp = explode('protected',Yii::app()->basePath);
					$basePath = $exp[0];
					if(!is_dir($basePath.$path))
						Yii::app()->file->createDir($permissions=0755, $path);
					if(!is_dir($basePath.$path_thumbs))
						Yii::app()->file->createDir($permissions=0755, $path_thumbs);
					$fname = Tools::slug($model->name).'-'.time().'.'.$extension;
					$model->image = $fname;
					if($file->saveAs($path.'/'.$fname)){ //upload image
						//create thumb
						$thumb = Yii::app()->phpThumb->create($path.'/'.$fname);
						$thumb->resize(250,250);
						$thumb->save($path_thumbs.'/'.$fname);

						$model->save();
					}
				}
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
					'image'=>(!empty($model->image))? CHtml::image(Yii::app()->request->baseUrl.'/'.$model->src.$model->image) : false,
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_gallery',array('model'=>$model),true,true),
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
			$old_image = $model->image;

			$model->attributes = $_POST['ModGallery'];
			//default path uploads/images/gallery
			$path = Extension::getConfigByModule('gallery','gallery_upload_path');
			$model->src = $path.'/';
			//default path uploads/images/gallery/_thumbs
			$path_thumbs = $path.'/_thumbs';
			$model->thumb = $path_thumbs.'/';
			$model->date_update = date(c);
			$model->user_update = Yii::app()->user->id;
			if($model->update(array('name','description','date_update','user_update'))){
				$file = CUploadedFile::getInstance($model,'image');
				$extension = pathinfo($file->name, PATHINFO_EXTENSION);
				if(!empty($file)){
					$exp = explode('protected',Yii::app()->basePath);
					$basePath = $exp[0];
					if(!is_dir($basePath.$path))
						Yii::app()->file->createDir($permissions=0755, $path);
					if(!is_dir($basePath.$path_thumbs))
						Yii::app()->file->createDir($permissions=0755, $path_thumbs);
					$fname = Tools::slug($model->name).'-'.time().'.'.$extension;
					$model->image = $fname;
					if($file->saveAs($path.'/'.$fname)){ //upload image
						//create thumb
						$thumb = Yii::app()->phpThumb->create($path.'/'.$fname);
						$thumb->resize(250,250);
						$thumb->save($path_thumbs.'/'.$fname);
						//delete the old image
						if($model->image != $old_image){
							if(file_exists($model->src.$old_image))
								unlink($model->src.$old_image);
							if(file_exists($model->thumb.$old_image))
								unlink($model->thumb.$old_image);
						}

						$model->update('image');
					}
				}

				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_gallery',array('model'=>$model),true,true),
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

	public function actionCreateCategory()
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = new ModGalleryCategory;
			$model->attributes = $_POST['ModGalleryCategory'];
			$model->slug = Tools::slug($model->title);
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
					'div'=>$this->renderPartial('_form_category',array('model'=>$model),true,true),
				));
			}
			exit;
		}
	}

	public function actionDeleteCategory($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = ModGalleryCategory::model()->findByPk($id);
			if(!$model instanceof ModGalleryCategory)
				throw new CHttpException(404,'The requested page does not exist.');

			return $model->delete();
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
		$model=ModGallery::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
