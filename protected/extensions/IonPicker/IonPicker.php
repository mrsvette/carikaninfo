<?php
/**
 * IonPicker class file.
 *
 * @author Farid Efendi <miftahfaridse@gmail.com>
 */

class IonPicker extends CInputWidget
{
	public $options=array();
	
	public function init()
	{
		$this->publishAssets();
	}
	
    public function run()
    {
		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;

		if($this->hasModel())
			echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::textField($name,$this->value,$this->htmlOptions);
		
		$options=CJavaScript::encode($this->options);	
		Yii::app()->clientScript->registerScript($id,"
			$('#{$id}').iconpicker({$options});
		");
	}
	
	protected static function publishAssets()
	{
		$assets=dirname(__FILE__).'/assets';
		$baseUrl=Yii::app()->assetManager->publish($assets);
		if(is_dir($assets)){
			Yii::app()->clientScript->registerCoreScript('jquery');
			Yii::app()->clientScript->registerScriptFile($baseUrl.'/js/fontawesome-iconpicker.min.js',CClientScript::POS_END);
			Yii::app()->clientScript->registerCssFile($baseUrl.'/css/fontawesome-iconpicker.min.css');
		} else {
			throw new Exception('IonPicker - Error: Couldn\'t find assets to publish.');
		}
	}
}
