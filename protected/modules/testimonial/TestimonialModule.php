<?php

class TestimonialModule extends CWebModule
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
			'testimonial.models.*',
			'testimonial.components.*',
			'appadmin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			if(!Extension::getIsInstalled(array('id'=>'testimonial')))
				throw new CHttpException(404,'The requested page does not exist.');
			return true;
		}
		else
			return false;
	}

	public function install()
	{
		$sql = "CREATE TABLE IF NOT EXISTS `tbl_mod_testimonial` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `client_id` int(11) DEFAULT '0',
		  `name` varchar(128) NOT NULL,
		  `activity` varchar(128) DEFAULT NULL,
		  `website` varchar(256) DEFAULT NULL,
		  `comment` text NOT NULL,
		  `rate` double DEFAULT '1',
		  `image` varchar(128) DEFAULT NULL,
		  `thumb` varchar(256) DEFAULT NULL,
		  `src` varchar(256) DEFAULT NULL,
		  `notes` text,
		  `status` int(11) DEFAULT '0' COMMENT '0=pending, 1=approve',
		  `date_entry` datetime DEFAULT NULL,
		  `date_update` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->execute();
		return true;
	}

	public function fetchNavigation()
    {
        return array(
            'manage'=> array(
                array(
					'label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Testimonial',
					'url'=>array('/testimonial/tDefault/view'), 
					'visible'=>Extension::getIsInstalled(array('id'=>'testimonial'))
				),
            ),
        );
    }
}
