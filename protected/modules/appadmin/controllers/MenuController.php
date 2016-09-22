<?php

class MenuController extends EController
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
				'actions'=>array('ajax'),
				'users'=>array('@'),
			),
			array('allow',
				'actions'=>array('create'),
				'expression'=>'Rbac::ruleAccess(\'create_p\')',
			),
			array('allow',
				'actions'=>array('view'),
				'expression'=>'Rbac::ruleAccess(\'read_p\')',
			),
			array('allow',
				'actions'=>array('update','updateorder','dragupdate'),
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
		$model=new Menu;
		$model2=new MenuContent;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Menu']))
		{
			$model->attributes=$_POST['Menu'];
			$model->date_entry=date(c);
			$model->user_entry=Yii::app()->user->id;
			$model->level_id=Menu::item($model->parent_id)->level_id+1;
			$model->urutan=Menu::getUrutan($model->parent_id,$model->group_id);
			if($model->save()){
				$model2->attributes=$_POST['MenuContent'];
				if(is_array($model2->nama_menu)){
					foreach($model2->nama_menu as $lang=>$title){
						$model3=new MenuContent;
						$model3->menu_id=$model->id;
						$model3->language=$lang;
						$model3->nama_menu=$model2->nama_menu[$lang];
						$model3->keterangan=$model2->keterangan[$lang];
						$model3->link_action=$model2->link_action[$lang];
						if($model2->link_type[$lang]==2){
							$model3->rel_id=$model2->page[$lang];
							$model3->link_action=Post::getPage($model2->page[$lang]);
						}
						$model3->icon_fa=$model2->icon_fa[$lang];
						$model3->save();
					}
				}
				$this->redirect(array('view'));
			}else{
				var_dump($model->errors);exit;
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'model2'=>$model2
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model2=$model->content_rel;
		//given value
		foreach(PostLanguage::items(null) as $lang1=>$name){
			$model2->link_type[$lang1]=($model2->getValue('rel_id',$model->id,$lang1)>0)? 2 : 1;
			$model2->page[$lang1]=$model2->getValue('rel_id',$model->id,$lang1);
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Menu']))
		{
			$model->attributes=$_POST['Menu'];
			$model2->attributes=$_POST['MenuContent'];
			$model->date_update=date(c);
			$model->user_update=Yii::app()->user->id;
			$model->level_id=Menu::item($model->parent_id)->level_id+1;
			if($model->save()){
				if(is_array($model2->nama_menu)){
					foreach($model2->nama_menu as $lang=>$title){
						$cont=$model->getContent($lang);
						if($cont->id>0)
							$model3=$model->getContent($lang);
						else{
							$model3=new MenuContent;
							$model3->menu_id=$model->id;
							$model3->language=$lang;
						}
						$model3->nama_menu=$model2->nama_menu[$lang];
						$model3->keterangan=$model2->keterangan[$lang];
						$model3->link_action=$model2->link_action[$lang];
						if((int)$model2->link_type[$lang]==2){
							$model3->rel_id=$model2->page[$lang];
							$model3->link_action=Post::getPage($model2->page[$lang]);
						}else{
							$model3->rel_id = null;
						}
						$model3->icon_fa=$model2->icon_fa[$lang];
						$model3->save();
					}
				}
				$this->redirect(array('view','id'=>$model->id));
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
			$del=MenuContent::model()->deleteAllByAttributes(array('menu_id'=>$id));
			$this->loadModel($id)->delete();

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
		$model=new Menu('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Menu']))
			$model->attributes=$_GET['Menu'];
		
		$this->render('view',array(
			'model'=>$model,
		));
	}
	
	public function actionAjax()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model=new MenuContent;
			echo CJSON::encode(array(
				'status'=>'success',
				'div'=>$this->renderPartial('_ajax',array('model'=>$model, 'lang'=>$_POST['lang'], 'link_type'=>$_POST['link_type']),true,true)
			));
			exit;
		}
	}

	public function actionUpdateorder()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model=$this->loadModel($_POST['id']);
			$model->urutan=(int)$_POST['urutan'];
			if($model->update('urutan')){
				echo CJSON::encode(array('status'=>'success'));
			}
		}
	}

	public function actionDragupdate()
	{
		if(Yii::app()->request->isPostRequest)
		{
			if(isset($_POST['data'])){
				$datas = CJSON::decode($_POST['data']);
				$reorders = Menu::reorderdata($datas);
				if(is_array($reorders)){
					foreach($reorders as $id=>$data){
						$model=$this->loadModel($data['id']);
						$model->urutan = $data['urutan'];
						$model->level_id = $data['level_id'];
						$model->parent_id = $data['parent_id'];
						$model->date_update = date(c);
						$model->user_update = Yii::app()->user->id;
						$model->save();
					}
				}
			}
			return false;
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Menu::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='menu-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
