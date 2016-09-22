<?php

/**
 * Nestable widget class file.
 *
 *
 * @author Farid Efendi <miftahfaridse@gmail.com>
 * @copyright Copyright &copy; 2015
 * @license BSD
 */

/**
 * Nestable widget.
 *
 */
class Nestable extends CWidget
{
    public $visible	= true;
	public $baseUrl;
	public $options;
	public $element;
	public $updateUrl;

	public function init()
	{
		if(!$this->visible) {
			return;
		}
		$this->publishAssets();
	}

	public function defaultOptions()
	{
		return array(
			'listNodeName'=>'ol',
            'itemNodeName'=>'li',
		);
	}

	public function run()
    {
		if($this->visible) {
			$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
			$this->baseUrl = Yii::app()->getAssetManager()->publish($dir);
			
			if(is_array($this->options))
				$options = array_merge(self::defaultOptions(),$this->options);
			else
				$options = self::defaultOptions();

			if(empty($this->updateUrl))
				$this->updateUrl = Yii::app()->createUrl('/appadmin/menu/dragupdate');
		    Yii::app()->clientScript->registerScript(uniqid(), '
		        $(function(){
					//add the textarea
					$("'.$this->element.'").parent().append("<textarea id=\"nestable-output\" style=\"display:none;\"></textarea>");
					var updateOutput = function(e)
					{
						if(!e.length)
							e = $("'.$this->element.'").data("output", $("#nestable-output"));
						var list   = e.length ? e : $(e.target),
							output = list.data("output");
						if (window.JSON) {
							output.val(window.JSON.stringify(list.nestable("serialize")));//, null, 2));
						} else {
							output.val("JSON browser support required for this demo.");
						}
						update(output.val());
					};
					$("'.$this->element.'").nestable('.CJSON::encode($options).').on("change", updateOutput);
		        });
				function update(data){
					$.ajax({
						"beforeSend": function() { Loading.show(); },
						"complete": function() { Loading.hide(); },
						"url": "'.$this->updateUrl.'",
						"type":"post",
						"dataType": "json",
						"data":{"data":data},
						"success": function(data){},
					});
					return false;
				}'
		    );
		}
	}
	
	protected static function publishAssets()
	{
		$assets=dirname(__FILE__).'/assets';
		$baseUrl=Yii::app()->assetManager->publish($assets);
		if(is_dir($assets)){
			//Yii::app()->clientScript->registerCoreScript('jquery');
			Yii::app()->clientScript->registerScriptFile($baseUrl.'/jquery.nestable.js',CClientScript::POS_END);
            Yii::app()->clientScript->registerCssFile($baseUrl . '/nestable.css');
		} else {
			throw new Exception('Nestable - Error: Couldn\'t find assets to publish.');
		}
	}
}
