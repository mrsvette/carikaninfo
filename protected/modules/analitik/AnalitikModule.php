<?php

class AnalitikModule extends CWebModule
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
				'errorAction'=>Yii::app()->createUrl('appadmin/default/error'),
			),
			'user'=>array(
				'class'=>'CWebUser',
				'stateKeyPrefix'=>'jagungbakar',
				'loginUrl'=>Yii::app()->createUrl('appadmin/default/login'),
				'allowAutoLogin'=>true,
			),
		), false);

		//set view path
		$this->setLayoutPath(Yii::getPathOfAlias('application.modules.appadmin').'/views/layouts');
		
		// import the module-level models and components
		$this->setImport(array(
			'analitik.models.*',
			'analitik.components.*',
			'appadmin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			if(!Extension::getIsInstalled(array('id'=>'analitik')))
				throw new CHttpException(404,'The requested page does not exist.');
			
			return true;
		}
		else
			return false;
	}

	public function fetchNavigation()
    {
        return array(
            'custom'=> array(
                array(
					'label'=>'<span class="ion-stats-bars"></span> <span class="nav_title">'.Yii::t('menu','Analytics').'</span><b class="arrow icon-angle-down"></b>', 
					'url'=>'#', 
					'items'=>array(
						array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Realtime', 'url'=>array('/analitik/summary/realtime')),
						array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Audience', 'url'=>array('/analitik/summary/audiences')),
					),
					'itemOptions'=>array('class'=>'nav-parent'),
					'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),
					'visible'=>!Yii::app()->user->isGuest && Extension::getIsInstalled(array('id'=>'analitik'))
				),
            ),
        );
    }
}
