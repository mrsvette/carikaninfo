<?php
Yii::import('zii.widgets.CPortlet');

class SlideShowWidget extends CPortlet{
	public $visible = true;
	public $id;
	public $type;
	
	public function init()
	{
		if($this->visible)
		{
	 
		}
	}
 
	public function run()
	{
		if($this->visible)
		{
		    $this->renderContent();
		}
	}
	
	protected function renderContent()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('slug',$this->id);
		$model = ModSlideShow::model()->find($criteria);
		if(!$model instanceof ModSlideShow)
			throw new Exception('No slide found.');
		$this->type = $model->type;
		
		$this->render('slide_show',array('model'=>$model));
	}

	/*public function publishAssets()
	{
		$assets = dirname(__FILE__).'/assets/'.$this->type;
		$baseUrl = Yii::app()->assetManager->publish($assets);
		if(is_dir($assets)){
			//Yii::app()->clientScript->registerCoreScript('jquery');
			if($this->type == 'revolution'){
				Yii::app()->clientScript->registerCssFile($baseUrl . '/css/settings.css');
				Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.themepunch.plugins.min.js',CClientScript::POS_END);
				Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.themepunch.revolution.min.js',CClientScript::POS_END);
			}
			Yii::app()->clientScript->registerScriptFile($baseUrl . '/main.js',CClientScript::POS_END);
		} else {
			throw new Exception($this->type.' - Error: Couldn\'t publish assets.');
		}
	}*/

	private $_assetsUrl;

	public function getAssetsUrl()
	{
		if ($this->_assetsUrl === null)
		    $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('slideshow.components.assets'));
		return $this->_assetsUrl;
	}
}

?>
