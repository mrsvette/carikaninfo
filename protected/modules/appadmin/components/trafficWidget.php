<?php
Yii::import('zii.widgets.CPortlet');

class trafficWidget extends CPortlet{
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
		$model=new Visitor;
		$this->render('_traffic',array('model'=>$model));
	}
}

?>
