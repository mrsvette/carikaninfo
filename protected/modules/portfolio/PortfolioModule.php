<?php

class PortfolioModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		
		$this->defaultController = 'pDefault';

		Yii::app()->setComponents(array(
			'errorHandler'=>array(
				'class'=>'CErrorHandler',
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
			'portfolio.models.*',
			'portfolio.components.*',
			'appadmin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			if(!Extension::getIsInstalled(array('id'=>'portfolio')))
				throw new CHttpException(404,'The requested page does not exist.');
			return true;
		}
		else
			return false;
	}

	public function install()
	{
		$sql = "CREATE TABLE IF NOT EXISTS `tbl_mod_portfolio` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `client_name` varchar(128) DEFAULT NULL,
		  `category_id` int(11) DEFAULT NULL,
		  `website` varchar(256) DEFAULT NULL,
		  `start_date` datetime DEFAULT NULL,
		  `end_date` datetime DEFAULT NULL,
		  `status` int(11) DEFAULT '0' COMMENT '0=onprogress,1=completed',
		  `date_entry` datetime DEFAULT NULL,
		  `user_entry` int(11) DEFAULT '0',
		  `date_update` datetime DEFAULT NULL,
		  `user_update` int(11) DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;		
		CREATE TABLE IF NOT EXISTS `tbl_mod_portfolio_content` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `portfolio_id` int(11) NOT NULL,
		  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
		  `content` text COLLATE utf8_unicode_ci NOT NULL,
		  `language` int(11) DEFAULT '1',
		  `viewed` int(11) DEFAULT '0',
		  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `meta_keywords` text COLLATE utf8_unicode_ci,
		  `meta_description` text COLLATE utf8_unicode_ci,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->execute();
		return true;
	}

	public function fetchNavigation()
    {
        return array(
            'appearance'=> array(
                array(
					'label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Portfolio',
					'url'=>array('/portfolio/pDefault/view'), 
					'visible'=>Extension::getIsInstalled(array('id'=>'portfolio'))
				),
            ),
        );
    }
}
