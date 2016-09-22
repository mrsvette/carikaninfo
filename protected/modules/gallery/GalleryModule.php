<?php

class GalleryModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		
		$this->defaultController = 'gDefault';

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
			'gallery.models.*',
			'gallery.components.*',
			'appadmin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			if(!Extension::getIsInstalled(array('id'=>'gallery')))
				throw new CHttpException(404,'The requested page does not exist.');
			return true;
		}
		else
			return false;
	}

	public function install()
	{
		$sql = "CREATE TABLE IF NOT EXISTS `tbl_mod_gallery` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(128) NOT NULL,
		  `description` text,
		  `image` varchar(128) NOT NULL,
		  `thumb` varchar(128) DEFAULT NULL,
		  `src` varchar(128) NOT NULL,
		  `category_id` int(11) DEFAULT '0',
		  `date_entry` datetime NOT NULL,
		  `user_entry` int(11) NOT NULL,
		  `date_update` datetime DEFAULT NULL,
		  `user_update` int(11) DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		CREATE TABLE IF NOT EXISTS `tbl_mod_gallery_category` (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `title` varchar(100) DEFAULT NULL,
		  `slug` varchar(256) DEFAULT NULL,
		  `description` text,
		  `date_entry` datetime NOT NULL,
		  `user_entry` int(11) NOT NULL DEFAULT '0',
		  `date_update` datetime DEFAULT NULL,
		  `user_update` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->execute();
		return true;
	}

	public function fetchNavigation()
    {
        return array(
            'appearance'=> array(
				array(
					'label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Gallery',
					'url'=>array('/gallery'), 
					'visible'=>Extension::getIsInstalled(array('id'=>'gallery'))
				),
            ),
        );
    }
}
