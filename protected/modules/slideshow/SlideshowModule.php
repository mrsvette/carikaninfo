<?php

class SlideshowModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		//default controller
		$this->defaultController = 'sDefault';
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
			'slideshow.models.*',
			'slideshow.components.*',
			'appadmin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			if(!Extension::getIsInstalled(array('id'=>'slideshow')))
				throw new CHttpException(404,'The requested page does not exist.');
			
			return true;
		}
		else
			return false;
	}

	public function install()
	{
		$sql = "CREATE TABLE IF NOT EXISTS `tbl_mod_slide_show` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(128) NOT NULL,
		  `description` text,
		  `slug` varchar(128) NOT NULL,
		  `type` varchar(64) DEFAULT 'responsive' COMMENT 'responsive, revolution, carousel',
		  `status` varchar(16) DEFAULT 'enabled' COMMENT 'enabled, disabled',
		  `date_entry` datetime NOT NULL,
		  `user_entry` int(11) NOT NULL,
		  `date_update` datetime DEFAULT NULL,
		  `user_update` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		CREATE TABLE IF NOT EXISTS `tbl_mod_slide_show_item` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `title` varchar(128) NOT NULL,
		  `slide_show_id` int(11) NOT NULL,
		  `image_path` text NOT NULL,
		  `caption` text,
		  `url` varchar(256) DEFAULT NULL,
		  `order` int(11) NOT NULL DEFAULT '0',
		  `date_entry` datetime NOT NULL,
		  `user_entry` int(11) NOT NULL,
		  `date_update` datetime DEFAULT NULL,
		  `user_update` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->execute();
		return true;
	}

	public function fetchNavigation()
    {
        return array(
            'appearance'=> array(
				array(
					'label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Slide Show',
					'url'=>array('/slideshow'), 
					'visible'=>Extension::getIsInstalled(array('id'=>'slideshow'))
				),
            ),
        );
    }
}
