<?php

class EcommerceModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		
		$this->defaultController = 'products';

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
			'ecommerce.models.*',
			'ecommerce.components.*',
			'appadmin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			if(!Extension::getIsInstalled(array('id'=>'ecommerce')))
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
					'label'=>'<span class="ion-bag"></span> <span class="nav_title">'.Yii::t('menu','E-Commerce').'</span><b class="arrow icon-angle-down"></b>', 
					'url'=>'#', 
					'items'=>array(
						array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Products', 'url'=>array('/ecommerce/products/view')),
						array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Clients', 'url'=>array('/ecommerce/clients/view')),
						array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Orders', 'url'=>array('/ecommerce/orders/view')),
						array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Invoices', 'url'=>array('/ecommerce/invoices/view')),
					),
					'itemOptions'=>array('class'=>'nav-parent'),
					'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),
					'visible'=>!Yii::app()->user->isGuest && Extension::getIsInstalled(array('id'=>'ecommerce'))
				),
            ),
        );
    }
}
