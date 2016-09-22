<?php
Yii::import('zii.widgets.CPortlet');

class adminMainMenu extends CPortlet{
	public $visible=true;
	
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
		$Rbac=new Rbac;

		$items = array(
			array('label'=>'<span class="fa fa-lock"></span> <span class="nav_title">Login</span>', 'url'=>array('/appadmin/default/login'),'visible'=>Yii::app()->user->isGuest),
			array('label'=>'<span class="ion-speedometer"></span> <span class="nav_title">Dashboard</span>', 'url'=>array('/appadmin'),'visible'=>!Yii::app()->user->isGuest),
			array('label'=>'<span class="ion-compose"></span> <span class="nav_title">'.Yii::t('menu','Post').'</span>', 'url'=>'#', 
				'items'=>array(
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span>'.Yii::t('menu','All Post'),'url'=>array('/appadmin/posts/admin')),
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span>'.Yii::t('menu','Add New'),'url'=>array('/appadmin/posts/create')),
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span>'.Yii::t('menu','Categories'),'url'=>array('/appadmin/postcategory/create')),
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span>'.Yii::t('menu','Comments'),'url'=>array('/appadmin/comments/view')),
				),'itemOptions'=>array('class'=>'nav-parent'),'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),'visible'=>!Yii::app()->user->isGuest),
			array('label'=>'<span class="ion-clipboard"></span> <span class="nav_title">'.Yii::t('menu','Pages').'</span><b class="arrow icon-angle-down"></b>', 'url'=>'#', 
				'items'=>array(
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span>'.Yii::t('menu','All Pages'),'url'=>array('/appadmin/pages/admin')),
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span>'.Yii::t('menu','Add New'),'url'=>array('/appadmin/pages/create')),
				),'itemOptions'=>array('class'=>'nav-parent'),'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),'visible'=>!Yii::app()->user->isGuest),
			array('label'=>'<span class="ion-monitor"></span> <span class="nav_title">'.Yii::t('menu','Appearance').'</span><b class="arrow icon-angle-down"></b>', 'url'=>'#', 
				'items'=>self::getModulesMenu('appearance'),
				'itemOptions'=>array('class'=>'nav-parent'),'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),'visible'=>!Yii::app()->user->isGuest),
			array('label'=>'<span class="ion-settings"></span> <span class="nav_title">'.Yii::t('menu','Manage').'</span><b class="arrow icon-angle-down"></b>', 'url'=>'#', 
				'items'=>self::getModulesMenu('manage'),
				'itemOptions'=>array('class'=>'nav-parent'),'linkOptions'=>array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'),'visible'=>!Yii::app()->user->isGuest),
			);

		$custom_menu = self::getModulesMenu('custom');
		if(is_array($custom_menu) && count($custom_menu)>0){
			foreach($custom_menu as $i=>$custom){
				array_push($items,$custom);
			}
		}

		$this->render('_adminmainmenu',array('items'=>$items,'Rbac'=>$Rbac));
	}

	public function getModulesMenu($group='appearance')
	{
		$items = array();
		switch ($group){
			case 'appearance': //default items for appearance
				$items = array(
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Menu','url'=>array('/appadmin/menu/view')),
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> '.Yii::t('menu','Themes'), 'url'=>array('/appadmin/themes/view')),
				);
				break;
			case 'manage': //default items for appearance
				$items = array(
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Users', 'url'=>array('/appadmin/users/view')),
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> Parameter', 'url'=>array('/appadmin/params/view')),
					array('label'=>'<span class="side_icon ion-ios7-folder-outline"></span> '.Yii::t('menu','Extensions'), 'url'=>array('/appadmin/extensions/view')),
				);
				break;
		}
		$criteria = new CDbCriteria;
		$criteria->compare('status','installed');
		$criteria->order='name ASC';
		$models = Extension::model()->findAll($criteria);
		
		foreach($models as $model){
			$mod_class = ucfirst($model->name).'Module';
			Yii::import('application.modules.'.$model->name.'.'.$mod_class);
			if(method_exists($mod_class,'fetchNavigation')){
				$nav = $mod_class::fetchNavigation();
				if(!empty($nav[$group])){
					foreach($nav[$group] as $i=>$item){
						array_push($items, $item);
					}
				}
			}
		}
		return $items;
	}
}

?>
