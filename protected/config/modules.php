<?php
class EModule
{
	public $modules;

	public function __construct()
	{
		$this->modules=$this->findModules();
	}
	
	public function findModules()
	{

		$modules = array(
			'gii'=>array(
				'class'=>'system.gii.GiiModule',
				'password'=>'123456',
				'ipFilters'=>array('127.0.0.1','::1'),
			),
			'appadmin',
		);
		$dir=dirname(__FILE__);
		$pecah=explode("/config",$dir);
		$path=$pecah[0].'/modules';
		$names=scandir($path);
		$avoid=array('appadmin','api');
		foreach($names as $name){
			if($name[0]!=='.' && is_dir($path.'/'.$name)){
				if(!in_array($name,$avoid))
					array_push($modules,$name);
			}
		}
		return $modules;
	} 
}
$emodule=new EModule;
return $emodule->modules;
