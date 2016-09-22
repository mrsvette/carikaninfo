<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class DController extends CController
{
	public $layout='/layouts/main';
	public $menu=array();
	public $breadcrumbs=array();
	public $meta_description;
	public $meta_keywords;
	private $_basePath;

	public function init()
	{
		foreach(Yii::app()->modules as $mod=>$moddetail){
			Yii::import('application.modules.'.$mod.'.models.*');
		}
		Yii::app()->setTheme(Yii::app()->config->get('theme'));
		//set base path
		$this->_basePath = Yii::getPathOfAlias('webroot').'/';
	}

	public function beforeAction($action)
	{
		if(parent::beforeAction($action)) {
			if(isset($_GET['lang'])){
				if(Yii::app()->user->getState('language')->code!=$_GET['lang']){
					$criteria=new CDbCriteria;
					if(!empty($_GET['lang']))
						$criteria->compare('code',strtolower($_GET['lang']));
					else
						$criteria->compare('code','id');
					$language=PostLanguage::model()->find($criteria);
					if(!empty($language))
						Yii::app()->user->setState('language',$language);
				}
			}
			if(Yii::app()->user->getState('language')->code!=Yii::app()->language)
				Yii::app()->setLanguage(Yii::app()->user->getState('language')->code);
			//Yii::app()->user->setState('install',null);
			if(!Yii::app()->user->hasState('install')){
				$filehelper=new CFileHelper;
				$base_files=$filehelper->findFiles(dirname('index.php'),array('fileTypes'=>array('php'),'level'=>1));
				if(in_array('./install.php',$base_files)){
					if(Yii::app()->db->driverName=='sqlite')
						Yii::app()->user->setState('install',true);
					else
						Yii::app()->user->setState('install',false);
				}else
					Yii::app()->user->setState('install',false);
			}
			if(!Yii::app()->user->getState('install')){
				$this->meta_description = Yii::app()->config->get('meta_description');
				$this->meta_keywords = Yii::app()->config->get('meta_keywords');
				if(!Yii::app()->user->hasState('language')){
					$language = PostLanguage::getDefault();
					if($language instanceof PostLanguage)
						Yii::app()->user->setState('language',$language);
				}
			}
			return true;
		}else
			return false;
	}
	
	/**
	 * clean string from non alphanumeric
	 */
	public function alphaNumeric($string,$replace='+')
	{
		//return preg_replace("/[\/\&%#\$]/",$replace,$string);
		//remove all non alphanumeric
		return preg_replace("/[^[:alnum:][:space:]]/ui", $replace, $string);
	}

	/**
	 * unformat money format to base number
	 */
	public function money_unformat($number,$thousand='.',$decimal=',')
	{
		if(strstr($number, $thousand))
			$number = str_replace($thousand, '', $number);
		if(strstr($number, $decimal))
			$number = str_replace($decimal, '.', $number);
		return $number; 
	}

	/**
	 * clean string
	 */
	public function cleanString($string,$replace='+')
	{
		return preg_replace("/[\/\&%#*\$']/",$replace,$string);
	}

	public function createTag($string)
	{
		$string=$this->cleanString(strtolower($string),"");
		$pecah=array();
		if(!empty($string)){
			$pecah=explode(" ",$string);
		}
		$tag=serialize($pecah);
		return $tag;
	}

	public function getCustomBreadcrumb($links=array())
	{
	    	if(empty($links))
			$links=$this->breadcrumbs;

	   	echo '<ul class="breadcrumb">';
	    	echo '<li><i class="icon-home"></i>'.CHtml::link(Yii::t('zii','Home'),Yii::app()->homeUrl).'<span class="divider"><i class="icon-angle-right"></i></span></li>';
	    	foreach($links as $label=>$url)
	    	{
			if(is_string($label) || is_array($url))
		    		echo '<li>'.CHtml::link(CHtml::encode($label), $url);
			else
		    		echo '<li>'.CHtml::encode($url);
			echo '<span class="divider"><i class="icon-angle-right"></i></span>';
			echo '</li>';
	    	}
		echo '</ul>';
	}

	public function setBreadcrumbs($links=array())
	{
		$this->breadcrumbs = $links;
		return $this->breadcrumbs;
	}

	public function getParsePageTitle(){
		if(!empty($this->pageTitle)){
			$pecah=explode(" - ",$this->pageTitle);
			return $pecah[1];
		}
	}

	public function get_css($data,$eregs=null)
	{
		if(!file_exists($this->_basePath.$data['path']))
			return false;
		$result = file_get_contents($this->_basePath.$data['path']);
        if($result){
			if($eregs){
				$patterns = $eregs['patern']; //"../../images/";
				$replacements = $eregs['replacement']; //Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/images/';
				$result = ereg_replace($patterns, $replacements, $result);
			}
			return '<style>'.$result.'</style>';
		}else {
			return false;
		}
	}

	public function get_js($data)
	{
		$result = file_get_contents($data['path']);
        if($result){
			return '<script type="text/javascript">'.$result.'</script>';
		}else {
			return false;
		}
	}
}
