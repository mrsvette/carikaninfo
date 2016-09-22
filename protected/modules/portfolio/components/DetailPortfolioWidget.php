<?php
Yii::import('zii.widgets.CPortlet');

class DetailPortfolioWidget extends CPortlet{

	public $visible = true;
	public $slug;
	
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
		$criteria->compare('slug',$this->slug);
		$content = ModPortfolioContent::model()->find($criteria);

		if(!$content instanceof ModPortfolioContent)
			throw new CHttpException(404,'The requested page does not exist.');

		$model = $content->portfolio_rel;

		$this->render(
			'_detail_portfolio',
			array(
				'model' => $model,
			)
		);
	}
}

?>
