<?php

class EmailModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		
		$this->defaultController = 'templates';

		Yii::app()->setComponents(array(
			'errorHandler'=>array(
				'class'=>'CErrorHandler',
				//'errorAction'=>Yii::app()->createUrl($this->getId().'/products/error'),
				'errorAction'=>'/appadmin/default/error',
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
			'email.models.*',
			'email.components.*',
			'appadmin.components.*',
			//'ecommerce.models.*',
			//'ecommerce.components.*',
			//'ecommerce.EcommerceModule',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			if(!Extension::getIsInstalled(array('id'=>'email')))
				throw new CHttpException(404,'The requested page does not exist.');
			return true;
		}
		else
			return false;
	}

	public function install()
	{
		$sql = "CREATE TABLE IF NOT EXISTS `tbl_mod_email_template` (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `action_code` varchar(255) DEFAULT NULL,
		  `category` varchar(30) DEFAULT NULL COMMENT 'general, domain, invoice, hosting, support, download, custom, license',
		  `enabled` tinyint(1) DEFAULT '1',
		  `subject` varchar(255) DEFAULT NULL,
		  `content` text,
		  `description` text,
		  `vars` text,
		  `date_entry` datetime NOT NULL,
		  `user_entry` int(11) NOT NULL DEFAULT '0',
		  `date_update` datetime DEFAULT NULL,
		  `user_update` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `action_code` (`action_code`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->execute();
		return true;
	}

	public function fetchNavigation()
    {
        return array(
            'manage'=> array(
                array(
					'label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Email Template',
					'url'=>array('/email/templates/view'), 
					'visible'=>Extension::getIsInstalled(array('id'=>'email'))
				),
            ),
        );
    }
}
