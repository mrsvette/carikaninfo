<?php

class PCategoryController extends DController
{
	public $layout = 'column2';
	
	public function init(){
		$this->module->setLayoutPath(Yii::getPathOfAlias('webroot.themes.'.Yii::app()->theme->name.'.views.layouts'));
	}

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
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex($slug)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('slug',$slug);
		$category = ModPortfolioCategory::model()->find($criteria);

		$criteria2 = new CDbCriteria;
		$criteria2->compare('category_id',$category->id);
		$dataProvider = new CActiveDataProvider('ModPortfolio',array('criteria'=>$criteria2));

		$this->render('index',array('dataProvider'=>$dataProvider,'model'=>$category));
	}
}
