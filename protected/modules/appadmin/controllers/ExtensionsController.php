<?php

class ExtensionsController extends EController
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
				'actions'=>array('ajax','renderImage'),
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('install'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('view'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('uninstall','setting'),
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionInstall()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$path = Yii::getPathOfAlias('application.modules').'/'.$_POST['id'].'/';
			$jcontent = file_get_contents($path.'manifest.json');
			$content = CJSON::decode($jcontent);
			$criteria=new CDbCriteria;
			$criteria->compare('name',$content['id']);
			$model = Extension::model()->find($criteria);
			if($model instanceof Extension){
				$model->status='installed';
				$model->manifest=$jcontent;
				$model->date_update=date(c);
				$model->user_update=Yii::app()->user->id;
				$save = $model->save();
			}else{
				$model = new Extension;
				$model->name=$_POST['id'];
				$model->status='installed';
				$model->manifest=$jcontent;
				$model->date_entry=date(c);
				$model->user_entry=Yii::app()->user->id;
				$save = $model->save();
				if($save){
					$module_name = ucfirst($content['id']).'Module';
					Yii::import('application.modules.'.$content['id'].'.'.$module_name);
					if(method_exists($module_name,'install')){
						$exc = $module_name::install();
					}
				}
			}
			echo CJSON::encode(array(
				'status'=>($save)? 'success' : 'failed',
			));
			exit;
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUninstall()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$path = Yii::getPathOfAlias('application.modules').'/'.$_POST['id'].'/';
			$jcontent = file_get_contents($path.'manifest.json');
			$content = CJSON::decode($jcontent);
			$criteria=new CDbCriteria;
			$criteria->compare('name',$content['id']);
			$model = Extension::model()->find($criteria);
			if($model instanceof Extension){
				$model->status='uninstalled';
				$model->date_update=date(c);
				$model->user_update=Yii::app()->user->id;
				$save = $model->save();
			}
			echo CJSON::encode(array(
				'status'=>($save)? 'success' : 'failed',
			));
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
		$files = new CFileHelper;
		$manifests = $files->findFiles(Yii::getPathOfAlias('application.modules'),array('fileTypes'=>array('json')));
		$rawData = array();
		foreach($manifests as $i=>$manifest){
			$content = file_get_contents($manifest);
			$jcontent = CJSON::decode($content);
			$jcontent['path'] = Yii::getPathOfAlias('application.modules').'/'.$jcontent['id'].'/';
			$rawData[] = $jcontent;
		}
		$dataProvider=new CArrayDataProvider($rawData, array(
			'id'=>'modules',
			'sort'=>array(
				'defaultOrder'=> 'id ASC',
			),
			'pagination'=>array(
				'pageSize'=>10,
			),
		));

		$this->render('view',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionRenderImage()
	{
		$imgpath = Yii::getPathOfAlias('application.modules').'/'.$_GET['mod'].'/'.$_GET['icon'];
		if(!file_exists($imgpath))
			return false;
		// Get the mimetype for the file
		$finfo = finfo_open(FILEINFO_MIME_TYPE);  // return mime type ala mimetype extension
		$mime_type = finfo_file($finfo, $imgpath);
		finfo_close($finfo);
		switch ($mime_type){
			case "image/jpeg":
				// Set the content type header - in this case image/jpg
				header('Content-Type: image/jpeg');
			
				// Get image from file
				$img = imagecreatefromjpeg($imgpath);
			
				// Output the image
				imagejpeg($img);
			
				break;
			case "image/png":
				// Set the content type header - in this case image/png
				header('Content-Type: image/png');
			
				// Get image from file
				$img = imagecreatefrompng($imgpath);
			
			    // integer representation of the color black (rgb: 0,0,0)
			    $background = imagecolorallocate($img, 0, 0, 0);
			
			    // removing the black from the placeholder
			    imagecolortransparent($img, $background);
			
			    // turning off alpha blending (to ensure alpha channel information 
			    // is preserved, rather than removed (blending with the rest of the 
			    // image in the form of black))
			    imagealphablending($img, false);
			
			    // turning on alpha channel information saving (to ensure the full range 
			    // of transparency is preserved)
			    imagesavealpha($img, true);
			
				// Output the image
				imagepng($img);
			
			    break;
			case "image/gif":
				// Set the content type header - in this case image/gif
				header('Content-Type: image/gif');
			
				// Get image from file
				$img = imagecreatefromgif($imgpath);
			
			    // integer representation of the color black (rgb: 0,0,0)
			    $background = imagecolorallocate($img, 0, 0, 0);
			
			    // removing the black from the placeholder
			    imagecolortransparent($img, $background);
			
				// Output the image
				imagegif($img);
			
				break;
		}
	
		// Free up memory
		imagedestroy($img);
	}

	public function actionSetting($id)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('name',$id);
		$model = Extension::model()->find($criteria);
		if(!$model instanceof Extension)
			throw new CHttpException(404,'The requested page does not exist.');
		if(Yii::app()->request->isPostRequest){
			$save_configs = $model->saveConfig($_POST);
			if($save_configs){
				Yii::app()->user->setFlash('save',Yii::t('global','Your config has been successfully saved.'));
				$this->refresh();
			}
		}

		$this->render('setting',array('model'=>$model));
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
		$model=Extension::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Extension $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='extension-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
