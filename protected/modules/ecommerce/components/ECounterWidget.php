<?php
Yii::import('zii.widgets.CPortlet');

class ECounterWidget extends CPortlet{
	public $visible=true;
	
	public function init()
    {
        if($this->visible)
        {
			if($this->controller->module->id!='ecommerce'){
 			$this->controller->module->setImport(array(
				'ecommerce.models.*',
			));
			}
        }
    }
 
    public function run()
    {
        if($this->visible)
        {
			if(Extension::getIsInstalled(array('id'=>'ecommerce')))
            	$this->renderContent();
        }
    }
	
	protected function renderContent()
	{
		$this->render('_ecounter');
	}
}

?>
