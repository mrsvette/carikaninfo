<?php

/**
 * InstallForm class.
 */
class InstallForm extends CFormModel
{
	public $application_name;
	public $database_server;
	public $database_username;
	public $database_password;
	public $database_name;
	public $admin_username;
	public $admin_password;
	public $language;
	public $theme;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('application_name, database_server, database_username, database_name, admin_username, admin_password', 'required', 'on'=>'step1'),
			array('database_password, language, theme','safe'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'application_name'=>Yii::t('install','Website Name'),
			'database_server'=>Yii::t('install','Database Server'),
			'database_username'=>Yii::t('install','Database Username'),
			'database_password'=>Yii::t('install','Database Password'),
			'database_name'=>Yii::t('install','Database Name'),
			'admin_username'=>Yii::t('install','Admin Username'),
			'admin_password'=>Yii::t('install','Admin Password'),
			'language'=>Yii::t('install','Language'),
			'theme'=>Yii::t('install','Theme'),
		);
	}

	public function languageList()
	{
		return array('id'=>'Bahasa Indonesia','en'=>'English');
	}

	public function themesList()
	{
		return array('tahutempe'=>'Tahu Tempe');
	}

	public function setConfig($attr)
	{
		$file=Yii::app()->basePath.'/config/main.tpl';
		if(is_file($file)){
			$template =  Yii::app()->file->set($file, true);
			$var = array(
					'{{host}}'=>$attr['database_server'],
					'{{dbname}}'=>$attr['database_name'],
					'{{username}}'=>$attr['database_username'],
					'{{password}}'=>$attr['database_password'],
					'{{theme}}'=>$attr['theme'],
					'{{language}}'=>$attr['language'],
					'{{appname}}'=>$attr['application_name'],
				);
			$tpl = str_replace(array_keys($var), array_values($var), $template->contents);
			$file2 = Yii::app()->basePath.'/config/main.php';
			$config = Yii::app()->file->set($file2, true);
			if($config->setContents($tpl)){ //execute to write config
				return true;
			}
		}
	}
}
