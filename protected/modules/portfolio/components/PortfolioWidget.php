<?php
Yii::import('zii.widgets.CPortlet');

class PortfolioWidget extends CPortlet{

	public $visible = true;
	public $use_thumb = true;
	public $pageSize = 3;
	public $pagination = true;
	public $show_pagination = false;
	public $url_query;
	public $type = 'all';
	
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
		switch ($this->type){
			case 'onprogress':
				$criteria->compare('t.status',0);
				break;
			case 'completed':
				$criteria->compare('t.status',1);
				break;
		}
		$criteria->order = 'id DESC';

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
					'pageSize'=>1,//$this->pageSize,
					'currentPage'=>($items['ModPortfolio_page']>0)? $items['ModPortfolio_page'] : null,
				)
			);
		if(!$this->pagination){
			$criteria->limit = 18;
			$params = array(
				'criteria'=>$criteria,
				'pagination'=>false
			);
		}
		
		$dataProvider = new CActiveDataProvider('ModPortfolio', $params);
		
		$this->render(
			'_portfolio',
			array(
				'dataProvider' => $dataProvider,
			)
		);
	}
}

?>
