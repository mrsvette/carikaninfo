<?php

class ThemesController extends EController
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
				'actions'=>array('create','install'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('update'),
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
	public function actionCreate()
	{
		$model=new Params;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Params']))
		{
			$model->attributes=$_POST['Params'];
			if(!empty($_FILES['Params']['name']['image'])){
				$model->value=serialize($_FILES['Params']['name']['image']);
				$model->type=3;
			}else{
				$model->value=serialize($model->value);
				$model->type=1;
			}
			$pecah=explode(" ", strtolower($model->params_name));
			$model->key=implode("_",$pecah);
			//var_dump($model->attributes);exit;
			if($model->save()){
				if(!empty($_FILES['Params']['name']['image'])){
					$uploaddir = 'uploads/images/';
					$uploadfile = $uploaddir . basename($_FILES['Params']['name']['image']);
					move_uploaded_file($_FILES['Params']['tmp_name']['image'], $uploadfile);
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionView()
	{
		$files = new CFileHelper;
		$path = explode('protected',Yii::app()->basePath);
		$manifests = $files->findFiles($path[0].'themes',array('fileTypes'=>array('json')));
		$rawData = array();
		foreach($manifests as $i=>$manifest){
			$content = file_get_contents($manifest);
			$jcontent = CJSON::decode($content);
			$jcontent['path'] = $path[0].'themes/'.$jcontent['id'].'/';
			$jcontent['img_path'] = Yii::app()->request->baseUrl.'/themes/'.$jcontent['id'].'/'.$jcontent['preview'];
			$rawData[] = $jcontent;
		}
		$dataProvider=new CArrayDataProvider($rawData, array(
			'id'=>'theme',
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

	public function actionInstall()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$criteria=new CDbCriteria;
			$criteria->compare('t.key','theme');
			$model=Params::model()->find($criteria);
			if($_POST['install']>0)
				$model->value=serialize($_POST['id']);
			else
				$model->value=serialize(Yii::app()->theme->name);
			
			if($model->save()){
				echo CJSON::encode(array('status'=>'success'));
			}
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Params::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='params-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
