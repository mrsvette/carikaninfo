<?php
Yii::import('zii.widgets.CPortlet');

class CategoryWidget extends CPortlet{
	public $visible=true;
	public $limit=10;
	public $htmloptions = array();
	
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
		$criteria=new CDbCriteria;
		$criteria->limit = $this->limit;
		$criteria->compare('level',0);
		$criteria->order='title ASC';
		$dataProvider=new CActiveDataProvider('ModProductCategory',array('criteria'=>$criteria,'pagination'=>false));

		$this->render('_category',array('dataProvider'=>$dataProvider));
	}
}

?>
