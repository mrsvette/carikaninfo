<?php
Yii::import('zii.widgets.CPortlet');

class ImagePortfolioWidget extends CPortlet{

	public $visible = true;
	public $pagination = true;
	public $show_pagination = true;
	public $pageSize = 1;
	public $portfolio_id;
	public $url_query;
	
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
		$criteria->compare('t.portfolio_id',$this->portfolio_id);
		$criteria->order = 't.id ASC';

		$items = array();
		if(isset($this->url_query)){
			$pairs = explode('&',$this->url_query);
			foreach ($pairs as $pair) {
				$keyVal = explode('=',$pair);
				$key = &$keyVal[0];
				$val = urlencode($keyVal[1]);
				$items[$key] = $val;
			}
		}

		$params = array(
				'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>$this->pageSize,
					'currentPage'=>($items['ModPortfolioImages_page']>0)? (int)$items['ModPortfolioImages_page']-1 : null,
				)
			);

		if(!$this->pagination){
			$criteria->limit = 18;
			$params = array(
				'criteria'=>$criteria,
				'pagination'=>false
			);
		}

		$dataProvider = new CActiveDataProvider('ModPortfolioImages',$params);

		$this->render(
			'_portfolio_image',
			array(
				'dataProvider' => $dataProvider,
			)
		);
	}
}

?>
