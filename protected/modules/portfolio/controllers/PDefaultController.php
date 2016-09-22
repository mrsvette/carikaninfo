<?php

class PDefaultController extends EController
{
	public static $_alias='Manage Portfolio';

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
				'actions'=>array('update','uploadImage'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete','deleteCategory','deleteImage'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
			),
			array('allow',
				'actions'=>array('detail'),
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
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		//delete the image
		if($model->delete()){
			if($model->images_count>0){
				foreach($model->images_rel as $image){
					if(file_exists($image->src.$image->image))
						unlink($image->src.$image->image);
					if(file_exists($image->thumb.$image->image))
						unlink($image->thumb.$image->image);
					$image->delete();
				}
			}
			$del2 = ModPortfolioContent::model()->deleteAllByAttributes(array('portfolio_id'=>$id));
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
		$criteria = new CDbCriteria;
		$criteria->order = 't.id DESC';
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModPortfolio'])){
			$attrs = array('title_cr'=>'title','content_cr'=>'content');
			foreach($_GET['ModPortfolio'] as $attr1=>$val1){
				if(!empty($val1)){
					if(in_array($attr1,array_keys($attrs))){
						$criteria->compare('content_rel.'.$attrs[$attr1],$val1,true);
					}else
						$criteria->compare('t.'.$attr1,$val1,true);
				}
			}
			$criteria->with = array('content_rel');
			$criteria->together = true;
		}

		$dataProvider=new CActiveDataProvider('ModPortfolio',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>5)));

		$criteria2 = new CDbCriteria;
		$criteria2->compare('name',Yii::app()->controller->module->id);
		$setting = Extension::model()->find($criteria2);

		$criteria3 = new CDbCriteria;
		$criteria3->order='id DESC';
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModPortfolioCategory'])){
			foreach($_GET['ModPortfolioCategory'] as $attr1=>$val1){
				if(!empty($val1))
					$criteria3->compare($attr1,$val1,true);
			}
		}

		$categoryProvider=new CActiveDataProvider('ModPortfolioCategory',array('criteria'=>$criteria3));

		$this->render('view',array(
			'dataProvider'=>$dataProvider,
			'setting'=>$setting,
			'categoryProvider'=>$categoryProvider,
		));
	}

	public function actionManage($id)
	{
		$model = $this->loadModel($id);
		$model2 = $model->content_rel;

		$criteria = new CDbCriteria;
		$criteria->compare('t.portfolio_id',$model->id);

		$imagesProvider = new CActiveDataProvider('ModPortfolioImages',array('criteria'=>$criteria));
		
		$this->render('manage',array(
			'model'=>$model,
			'model2'=>$model2,
			'imagesProvider'=>$imagesProvider,
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
			$model = new ModPortfolio('create');
			$model2 = new ModPortfolioContent;

			$model->attributes = $_POST['ModPortfolio'];
			//default path uploads/images/gallery
			$path = Extension::getConfigByModule('portfolio','portfolio_upload_path');
			//$model->src = $path.'/';
			//default path uploads/images/gallery/_thumbs
			$path_thumbs = $path.'/_thumbs';
			//$model->thumb = $path_thumbs.'/';
			$model->start_date = (strtotime($model->start_date)>0)? date("Y-m-d",strtotime($model->start_date)) : null;
			$model->end_date = (strtotime($model->end_date)>0)? date("Y-m-d",strtotime($model->end_date)) : null;
			$model->status = (strtotime($model->end_date)>0)? 1 : 0;
			$model->date_entry = date(c);
			$model->user_entry = Yii::app()->user->id;
			if($model->save()){
				$model2->attributes = $_POST['ModPortfolioContent'];
				$slugs = array();
				if(is_array($model2->title)){
					foreach($model2->title as $lang=>$title){
						$model3 = new ModPortfolioContent;
						$model3->portfolio_id = $model->id;
						$model3->language = $lang;
						$model3->title = $model2->title[$lang];
						if(empty($model2->slug[$lang]))
							$model3->slug = Tools::slug($model3->title);
						else
							$model3->slug = $model2->slug[$lang];
						$model3->content = $model2->content[$lang];
						$model3->meta_keywords = $model2->meta_keywords[$lang];
						$model3->meta_description = $model2->meta_description[$lang];
						if($model3->save())
							$slugs[] = $model3->slug;
					}
				}
				$file = CUploadedFile::getInstance($model,'p_image');
				$extension = pathinfo($file->name, PATHINFO_EXTENSION);
				if(!empty($file)){
					list($CurWidth,$CurHeight) = getimagesize($file->tempName);
					//$exp = explode('protected',Yii::app()->basePath);
					$basePath = Yii::getPathOfAlias('webroot').'/'; //$exp[0];
					if(!is_dir($basePath.$path))
						Yii::app()->file->createDir($permissions=0755, $path);
					if(!is_dir($basePath.$path_thumbs))
						Yii::app()->file->createDir($permissions=0755, $path_thumbs);
					$fname = $slugs[0].'-'.time().'.'.$extension;
					//$model->image = $fname;
					if($file->saveAs($path.'/'.$fname)){ //upload image
						//resize image to ideal size
						$force_resize = (int)Extension::getConfigByModule('portfolio','portfolio_image_force_resize');
						if($force_resize>0){
							$ideal_width = (int)Extension::getConfigByModule('portfolio','portfolio_image_width');
							$ideal_height = (int)Extension::getConfigByModule('portfolio','portfolio_image_height');
							if(($CurWidth!=$ideal_width) || ($CurHeight!=$ideal_height)){
								$thumb2 = Yii::app()->phpThumb->create($path.'/'.$fname);
								$percentage = ($ideal_width/$CurWidth)*100;
								$thumb2->resizePercent($percentage);
								$thumb2->save($path.'/'.$fname);
								//force resize thumb
								$thumb3 = Yii::app()->phpThumb->create($path.'/'.$fname);
								$thumb3->adaptiveResize($ideal_width,$ideal_height);
								$thumb3->save($path.'/'.$fname);
							}
						}

						//create thumb
						$thumb = Yii::app()->phpThumb->create($path.'/'.$fname);
						$thumb->adaptiveResize(256,128);
						$thumb->save($path_thumbs.'/'.$fname);

						$model2 = new ModPortfolioImages;
						$model2->portfolio_id = $model->id;
						$model2->src = $path.'/';
						$model2->thumb = $path_thumbs.'/';
						$model2->image = $fname;
						$model2->date_entry = date(c);
						$model2->user_entry = Yii::app()->user->id;
						$model2->save();
					}
				}
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
					'image'=>(!empty($model->image))? CHtml::image(Yii::app()->request->baseUrl.'/'.$model->firstImage->src.$model->firstImage->image) : false,
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_portfolio',array('model'=>$model),true,true),
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
			//$old_image = $model->image;
			$model2 = new ModPortfolioContent;

			$model->attributes = $_POST['ModPortfolio'];
			//default path uploads/images/portfolio
			$path = Extension::getConfigByModule('portfolio','portfolio_upload_path');
			//$model->src = $path.'/';
			//default path uploads/images/portfolio/_thumbs
			$path_thumbs = $path.'/_thumbs';
			//$model->thumb = $path_thumbs.'/';
			$model->start_date = (strtotime($model->start_date)>0)? date("Y-m-d",strtotime($model->start_date)) : null;
			$model->end_date = (strtotime($model->end_date)>0)? date("Y-m-d",strtotime($model->end_date)) : null;
			$model->status = (strtotime($model->end_date)>0)? 1 : 0;
			$model->date_update = date(c);
			$model->user_update = Yii::app()->user->id;
			
			$attributes = array(
				'client_name',
				'category_id',
				'website',
				'start_date',
				'end_date',
				'status',
				'date_update',
				'user_update'
			);
			if($model->update($attributes)){
				$model2->attributes = $_POST['ModPortfolioContent'];
				$slugs = array();
				if(is_array($model2->title)){
					foreach($model2->title as $lang=>$title){
						$cont = $model->getContent($lang);
						if($cont->id>0)
							$model3 = $model->getContent($lang);
						else{
							$model3 = new ModPortfolioContent;
							$model3->portfolio_id = $model->id;
							$model3->language = $lang;
						}
						$model3->title = $model2->title[$lang];
						if(empty($model2->slug[$lang]))
							$model3->slug = Tools::slug($model3->title);
						else
							$model3->slug = $model2->slug[$lang];
						$model3->content = $model2->content[$lang];
						$model3->meta_keywords = $model2->meta_keywords[$lang];
						$model3->meta_description = $model2->meta_description[$lang];
						if($model3->save())
							$slugs[] = $model3->slug;
					}
				}
				/*$file = CUploadedFile::getInstance($model,'image');
				$extension = pathinfo($file->name, PATHINFO_EXTENSION);
				if(!empty($file)){
					//$exp = explode('protected',Yii::app()->basePath);
					$basePath = Yii::getPathOfAlias('webroot').'/';//$exp[0];
					if(!is_dir($basePath.$path))
						Yii::app()->file->createDir($permissions=0755, $path);
					if(!is_dir($basePath.$path_thumbs))
						Yii::app()->file->createDir($permissions=0755, $path_thumbs);
					$fname = $slugs[0].'-'.time().'.'.$extension;
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
				}*/

				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_portfolio',array('model'=>$model),true,true),
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

	public function actionDetail($slug)
	{
		//$this->setLayoutPath(Yii::getPathOfAlias('application.modules.appadmin').'/views/layouts');
		//$this->module->setLayoutPath(Yii::getPathOfAlias('webroot.themes.stone.views.layouts'));
		//$this->layout = 'column1';

		$criteria = new CDbCriteria;
		$criteria->compare('slug',$slug);
		$content = ModPortfolioContent::model()->find($criteria);

		if(!$content instanceof ModPortfolioContent)
			throw new CHttpException(404,'The requested page does not exist.');

		$model = $content->portfolio_rel;
		
		$this->render('detail',array('model'=>$model));
	}

	public function actionCreateCategory()
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = new ModPortfolioCategory;
			$model->attributes = $_POST['ModPortfolioCategory'];
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
			$model = ModPortfolioCategory::model()->findByPk($id);
			if(!$model instanceof ModPortfolioCategory)
				throw new CHttpException(404,'The requested page does not exist.');

			return $model->delete();
		}
	}

	public function actionUploadImage($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model = $this->loadModel($id);

			$model->attributes = $_POST['ModPortfolio'];
			//default path uploads/images/portfolio
			$path = Extension::getConfigByModule('portfolio','portfolio_upload_path');
			//default path uploads/images/portfolio/_thumbs
			$path_thumbs = $path.'/_thumbs';

			$file = CUploadedFile::getInstance($model,'p_image');
			
			$extension = pathinfo($file->name, PATHINFO_EXTENSION);
			if(!empty($file)){
				list($CurWidth,$CurHeight) = getimagesize($file->tempName);
				$basePath = Yii::getPathOfAlias('webroot').'/';
				if(!is_dir($basePath.$path))
					Yii::app()->file->createDir($permissions=0755, $path);
				if(!is_dir($basePath.$path_thumbs))
					Yii::app()->file->createDir($permissions=0755, $path_thumbs);
				$fname = Tools::slug($model->content_rel->title).'-'.time().'.'.$extension;
				if($file->saveAs($path.'/'.$fname)){ //upload image
					//resize image to ideal size
					$force_resize = (int)Extension::getConfigByModule('portfolio','portfolio_image_force_resize');
					if($force_resize>0){
						$ideal_width = (int)Extension::getConfigByModule('portfolio','portfolio_image_width');
						$ideal_height = (int)Extension::getConfigByModule('portfolio','portfolio_image_height');
						if(($CurWidth!=$ideal_width) || ($CurHeight!=$ideal_height)){
							$thumb2 = Yii::app()->phpThumb->create($path.'/'.$fname);
							$percentage = ($ideal_width/$CurWidth)*100;
							$thumb2->resizePercent($percentage);
							$thumb2->save($path.'/'.$fname);
							//force resize thumb
							$thumb3 = Yii::app()->phpThumb->create($path.'/'.$fname);
							$thumb3->adaptiveResize($ideal_width,$ideal_height);
							$thumb3->save($path.'/'.$fname);
						}
					}

					//create thumb
					$thumb = Yii::app()->phpThumb->create($path.'/'.$fname);
					$thumb->adaptiveResize(256,128);
					$thumb->save($path_thumbs.'/'.$fname);

						$model2 = new ModPortfolioImages;
						$model2->portfolio_id = $model->id;
						$model2->src = $path.'/';
						$model2->thumb = $path_thumbs.'/';
						$model2->image = $fname;
						$model2->date_entry = date(c);
						$model2->user_entry = Yii::app()->user->id;
						$model2->save();
					}
			}

			echo CJSON::encode(array(
				'status'=>'success',
				'div'=>'Data berhasil disimpan',
			));
			exit;
		}
	}

	public function actionDeleteImage($id)
	{
		$model = ModPortfolioImages::model()->findByPk($id);
		//delete the image
		if($model->delete()){
			if(file_exists($model->src.$model->image))
				unlink($model->src.$model->image);
			if(file_exists($model->thumb.$model->image))
				unlink($model->thumb.$model->image);
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
		$model = ModPortfolio::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
