<?php

class ProductsController extends EController
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
				'actions'=>array('view','manage'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('create','createCategory','createImage'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('update','updateImageType','updateCategory'),
				'expression'=>'Rbac::ruleAccess(\'update_p\')',
			),
			array('allow',
				'actions'=>array('delete','deleteCategory','deleteImage','deleteImage2'),
				'expression'=>'Rbac::ruleAccess(\'delete_p\')',
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionView()
	{
		$criteria=new CDbCriteria;
		$criteria->order='id ASC';
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModProduct'])){
			foreach($_GET['ModProduct'] as $attr1=>$val1){
				if(!empty($val1))
					$criteria->compare($attr1,$val1,true);
			}
		}

		$dataProvider=new CActiveDataProvider('ModProduct',array('criteria'=>$criteria));

		$criteria2=new CDbCriteria;
		$criteria2->order='id DESC';
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ModProductCategory'])){
			foreach($_GET['ModProductCategory'] as $attr=>$val){
				if(!empty($val))
					$criteria2->compare($attr,$val,true);
			}
		}
		$categoryProvider=new CActiveDataProvider('ModProductCategory',array('criteria'=>$criteria2));

		$this->render('view',array(
			'dataProvider'=>$dataProvider,
			'categoryProvider'=>$categoryProvider,
		));
	}

	public function actionManage($id)
	{
		$model = $this->loadModel($id);
		if($model->payment_rel instanceof ModProductPayment)
			$model2 = $model->payment_rel;
		else{
			$model2 = new ModProductPayment;
			$model2->type = 'once';
			$model2->w_enabled = false;
			$model2->m_enabled = false;
			$model2->q_enabled = false;
			$model2->b_enabled = false;
			$model2->a_enabled = false;
			$model2->bia_enabled = false;
			$model2->tria_enabled = false;
		}

		$criteria= new CDbCriteria;
		$criteria->compare('product_id',$id);
		$imageProvider = new CActiveDataProvider('ModProductImages',array('criteria'=>$criteria));
		
		$this->render('manage',array(
			'model'=>$model,
			'model2'=>$model2,
			'imageProvider'=>$imageProvider,
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
			$model=new ModProduct;
			$model->attributes=$_POST['ModProduct'];
			$model->slug = $model->createSlug($model->title);
			$model->type = strtolower(Lookup::item('ProductType',$model->type));
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
					'div'=>$this->renderPartial('_form_product',array('model'=>$model),true,true),
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
			$model=$this->loadModel($id);
			//product payment
			if($model->payment_rel instanceof ModProductPayment)
				$model2 = $model->payment_rel;
			else{
				$model2 = new ModProductPayment;
				$model2->type = 'once';
				$model2->w_enabled = 0;
				$model2->m_enabled = 0;
				$model2->q_enabled = 0;
				$model2->b_enabled = 0;
				$model2->a_enabled = 0;
				$model2->bia_enabled = 0;
				$model2->tria_enabled = 0;
				$model2->date_entry=date(c);
				$model2->user_entry=Yii::app()->user->id;
			}

			$model->attributes=$_POST['ModProduct'];
			$model->slug=Post::slug($model->title);
			$model->date_update=date(c);
			$model->user_update=Yii::app()->user->id;
			if($model->validate() && $model2->validate()){
				$model->save(); //simpan data setelah semua tervalidasi
				if(isset($_POST['ModProductPayment'])){
					$model2->attributes=$_POST['ModProductPayment'];
					if(!$model2->isNewRecord){
						$model2->date_update=date(c);
						$model2->user_update=Yii::app()->user->id;
					}
					if($model2->save()){
						$model->product_payment_id = $model2->id;
						$model->update('product_payment_id');
					}
				}
				
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_manage',array('model'=>$model,'model2'=>$model2),true,true),
				));
			}
			exit;
		}
	}

	public function actionCreateCategory()
	{
		if(Yii::app()->request->isPostRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$model=new ModProductCategory;
			$model->attributes=$_POST['ModProductCategory'];
			$model->slug = Post::createSlug($model->title);
			if(is_array($model->childs))
				$model->childs = CJSON::encode($model->childs);
			$model->date_entry=date(c);
			$model->user_entry=Yii::app()->user->id;
			if($model->save()){
				$file=CUploadedFile::getInstance($model,'icon_url');
				$extension = pathinfo($file->name, PATHINFO_EXTENSION);
				if(!empty($file)){
					$path = 'uploads/images/products/';
					$model->icon_url = $path.Post::createSlug($model->title).'-'.time().'.'.$extension;//$path.$file->name;
					if($file->saveAs($model->icon_url)){ //upload image
						$model->save();
					}
				}
				//update the parents for the child given
				if(!empty($model->childs)){
					$model->childs = CJSON::decode($model->childs,true);
					foreach($model->childs as $ic=>$child){
						$cmodel = ModProductCategory::getItem($child);
						if($cmodel instanceof ModProductCategory){
							if(empty($cmodel->parents)){
								$cmodel->parents = CJSON::encode(array($model->id));
							}else{
								$cparents = CJSON::decode($cmodel->parents,true);
								if(!in_array($model->id,$cparents))
									array_push($cparents,$model->id);
								$cmodel->parents = CJSON::encode($cparents);
							}
							$cmodel->level = $model->level + 1; //increase the level 1 more than the parent
							$cmodel->update(array('parents','level'));
						}
					}
				}
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
					'icon'=>(!empty($model->icon_url))? CHtml::image(Yii::app()->request->baseUrl.'/'.$model->icon_url) : false,
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

	public function actionUpdateCategory($id)
	{
		$model = ModProductCategory::model()->findByPk($id);
		$old_image = $model->icon_url;
		if(!empty($model->childs))
			$model->childs = CJSON::decode($model->childs,true);
		$criteria = new CDbCriteria;
		$criteria->order = 'id DESC';

		$dataProvider = new CActiveDataProvider('ModProductCategory',array('criteria'=>$criteria));
		if(Yii::app()->request->isPostRequest){
			$model->attributes=$_POST['ModProductCategory'];
			if(is_array($model->childs)){
				//update the parents for the child given
				foreach($model->childs as $ic=>$child){
					$cmodel = ModProductCategory::getItem($child);
					if($cmodel instanceof ModProductCategory){
						if(empty($cmodel->parents)){
							$cmodel->parents = CJSON::encode(array($model->id));
						}else{
							$cparents = CJSON::decode($cmodel->parents,true);
							if(!in_array($model->id,$cparents))
								array_push($cparents,$model->id);
							$cmodel->parents = CJSON::encode($cparents);
						}
						$cmodel->level = $model->level + 1; //increase the level 1 more than the parent
						$cmodel->update(array('parents','level'));
					}
				}
				$model->childs = CJSON::encode($model->childs);
			}
			$model->slug = Post::createSlug($model->title);
			$model->date_update=date(c);
			$model->user_update=Yii::app()->user->id;
			if($model->update(array('title','slug','level','icon_fa','description','childs','date_update','user_update'))){
				$file=CUploadedFile::getInstance($model,'icon_url');
				$extension = pathinfo($file->name, PATHINFO_EXTENSION);
				if(!empty($file)){
					$path = 'uploads/images/products/';
					$model->icon_url = $path.Post::createSlug($model->title).'-'.time().'.'.$extension;//$path.$file->name;
					if($file->saveAs($model->icon_url)){ //upload image
						if(!empty($old_image)){
							if(file_exists($old_image))
								unlink($old_image);
						}
						$model->save();
					}
				}
				echo CJSON::encode(array(
					'status'=>'success',
					'div'=>'Data berhasil disimpan',
					'icon'=>(!empty($model->icon_url))? CHtml::image(Yii::app()->request->baseUrl.'/'.$model->icon_url) : false,
				));
			}else{
				echo CJSON::encode(array(
					'status'=>'failed',
					'div'=>$this->renderPartial('_form_category',array('model'=>$model),true,true),
				));
			}
			exit;
		}
		$this->render('category',array('model'=>$model,'dataProvider'=>$dataProvider));
	}

	public function actionDeleteCategory($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model=ModProductCategory::model()->findByPk($id);
			if(!empty($model->icon_url)){
				if(file_exists($model->icon_url))
					unlink($model->icon_url);
			}
			$model->delete();
		}
	}

	public function actionCreateImage($id)
	{
		Yii::import("ext.EAjaxUpload.qqFileUploader");
	        
        $folder='uploads/images/';// folder for uploaded files
        $allowedExtensions = array("jpg","jpeg","gif","png");
        $sizeLimit = Yii::app()->params['sizeLimit'];// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);

		//create thumb
		$thumb=Yii::app()->phpThumb->create($folder.$result['filename']);
		$thumb->resize(250,250);
		$thumb->save('uploads/_thumbs/Images/'.$result['filename']);

		$model=new ModProductImages;
		$model->product_id=$id;
		$model->image=$result['filename'];
		$model->thumb='uploads/_thumbs/Images/';
		$model->src=$folder;
		$model->date_entry=date(c);
		$model->user_entry=Yii::app()->user->id;
		if($model->save()){
		    $result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		  	echo $result;// it's array
		}
	}

	public function actionDeleteImage()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model=ModProductImages::model()->findByPk($_POST['id']);
			if(!empty($model->image)){
				$criteria=new CDbCriteria;
				$criteria->compare('image',$model->image);
				$count=ModProductImages::model()->count($criteria);
				if($count<=1){
					if(file_exists($model->src.$model->image)){
						if(unlink($model->src.$model->image)){
							if(file_exists($model->thumb.$model->image))
								unlink($model->thumb.$model->image);
							$model->delete();
						}
					}
				}else
					$model->delete();
			}
			echo CJSON::encode(array('status'=>'success'));
			exit;
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionDeleteImage2($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model=ModProductImages::model()->findByPk($id);
			if(!empty($model->image)){
				$criteria=new CDbCriteria;
				$criteria->compare('image',$model->image);
				$count=ModProductImages::model()->count($criteria);
				if($count<=1){
					if(file_exists($model->src.$model->image)){
						if(unlink($model->src.$model->image)){
							if(file_exists($model->thumb.$model->image))
								unlink($model->thumb.$model->image);
							$model->delete();
						}
					}
				}else
					$model->delete();
			}
			echo CJSON::encode(array('status'=>'success'));
			exit;
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionUpdateImageType()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model=ModProductImages::model()->findByPk($_POST['id']);
			if(!empty($_POST['value'])){
				$model->type=$_POST['value'];
				$model->update('type');
			}
			echo CJSON::encode(array('status'=>'success'));
			exit;
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
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
		$model=ModProduct::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
