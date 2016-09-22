<?php

class AppadminModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		//default controller
		$this->defaultController = 'default';
		
		Yii::app()->setComponents(array(
			'errorHandler'=>array(
				'class'=>'CErrorHandler',
				'errorAction'=>$this->getId().'/default/error',
			),
			'user'=>array(
				'class'=>'CWebUser',
				'stateKeyPrefix'=>'jagungbakar',
				'loginUrl'=>Yii::app()->createUrl($this->getId().'/default/login'),
				'allowAutoLogin'=>true,
			),
		), false);

		// import the module-level models and components
		$this->setImport(array(
			'appadmin.models.*',
			'appadmin.components.*',
		));
		/*$import = array('appadmin.components.*');
		foreach(Yii::app()->modules as $mod=>$moddetail){
			if(!in_array($mod,array('gii')))
				$import[] = $mod.'.models.*';
		}
		$this->setImport($import);*/
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			if(!Yii::app()->user->hasState('language'))
				Yii::app()->user->setState('language',PostLanguage::getDefault());
			return true;
		}
		else
			return false;
	}
}
