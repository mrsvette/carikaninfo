<?php

/**
 * This is the model class for table "{{tbl_core_visitor}}".
 *
 * The followings are the available columns in table '{{api_visitor}}':
 * @property integer $id
 * @property string $client_id
 * @property string $ip_address
 * @property string $page_title
 * @property string $url_referrer
 * @property string $platform
 * @property string $user_agent
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_expired
 */
class Visitor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{core_visitor}}';
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ip_address, date_entry', 'required'),
			array('user_entry', 'numerical', 'integerOnly'=>true),
			array('client_id', 'length', 'max'=>16),
			array('ip_address', 'length', 'max'=>39),
			array('page_title, url, url_referrer, platform, user_agent', 'length', 'max'=>256),
			array('client_id, date_expired, mobile, active', 'safe'),
			//array('client_id', 'uniqueMe','on'=>'create'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_id, ip_address, page_title, url_referrer, platform, user_agent, date_entry, user_entry, date_expired', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'client_rel'=>array(self::BELONGS_TO,'ApiClient','client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'client_id' => 'Client ID',
			'ip_address' => 'Ip Address',
			'page_title' => 'Page Title',
			'url_referrer' => 'Url Referrer',
			'platform' => 'Platform',
			'user_agent' => 'User Agent',
			'date_entry' => 'Date Entry',
			'user_entry' => 'User Entry',
			'date_expired' => 'Date Closed',
		);
	}

	public function uniqueMe($attribute,$params)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('session_id',$this->session_id);
		$criteria->compare('page_title',$this->page_title);
		if(self::model()->count($criteria)>0)
			$this->addError('client_id','Data already recorded.');
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('page_title',$this->page_title,true);
		$criteria->compare('url_referrer',$this->url_referrer,true);
		$criteria->compare('platform',$this->platform,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_entry',$this->user_entry);
		$criteria->compare('date_expired',$this->date_expired,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApiVisitor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getActiveVisitor($date=null,$counter=true)
	{
		if(empty($date))
			$date=date("Y-m-d H:i:s");		
		$criteria=new CDbCriteria;
		$criteria->select='t.ip_address';
		$criteria->compare('DATE_FORMAT(date_expired,"%Y-%m-%d %H:%i:%s")','>='.date("Y-m-d H:i:s",strtotime($date)));
		$criteria->group='t.session_id';
		$count=self::model()->count($criteria);
		return $count;
	}

	public function getCookie($name='_ma',$expiration=true)
	{
		if(!empty($_COOKIE[$name])){
			$pecah=explode("-",$_COOKIE[$name]);
			if($expiration){
				if(!empty($pecah[1]))
					return date("Y-m-d H:i:s",strtotime($pecah[1]));
				else
					return null;
			}else
				return $pecah[0];
		}
		return null;
	}

	public function getGraphPerMinute()
	{
		$data1=Yii::app()->db->createCommand(
				'SELECT TIMEDIFF(NOW(),date_entry) as time_diff 
				FROM tbl_core_visitor WHERE DATEDIFF(NOW(),date_entry)=:diff');
		$data1->params=array('diff'=>0);
		$rows1=$data1->queryAll();
		$items=array();
		/** array(2) { [0]=> array(2) { ["i"]=> string(2) "05" ["s"]=> int(42) } [1]=> array(2) { ["i"]=> string(2) "05" ["s"]=> int(32) } }  */
		if(count($rows1)>0){
			foreach($rows1 as $i=>$row){
				$pecah=explode(":",$row['time_diff']);
				if((int)$pecah[0]==0)
					$items[]=(int)$pecah[1];
			}
		}
		$v_items=array_count_values($items);
		$dt=array();
		for($k=0; $k<30; $k++){
			$dt[]=array($k,(int)$v_items[$k]);
		}
		return $dt;
	}

	public function getActivePages()
	{
		$sql='SELECT t.id, t.url, session_id, 
			COUNT(t.ip_address) as count, TIMEDIFF(NOW(),t.date_expired) as timediff 
			FROM tbl_core_visitor t 
			WHERE DATEDIFF(NOW(),t.date_entry)=:diff AND TIMEDIFF(NOW(),t.date_expired)<=:timediff AND t.active=:active 
			GROUP BY t.url 
			ORDER BY count DESC';
		$command=Yii::app()->db->createCommand($sql);
		$command->params=array('diff'=>0,'timediff'=>'00:00:00','active'=>1); //just today
		//$count_data=count($command->queryAll());
		$rawData=$command->queryAll();
		return $rawData;
	}

	public function getCountActivePage($url)
	{
		//return self::getActivePages();
		//$sql='SELECT v.ip_address FROM tbl_core_visitor v WHERE v.url=:url AND DATEDIFF(NOW(),v.date_entry)=:diff GROUP BY v.ip_address';
		$sql='SELECT v.session_id FROM tbl_core_visitor v WHERE v.url=:url AND DATEDIFF(NOW(),v.date_entry)=:diff AND TIMEDIFF(NOW(),v.date_expired)<=:timediff ORDER BY id DESC';
		$command=Yii::app()->db->createCommand($sql);
		$command->params=array('diff'=>0,'url'=>$url,'timediff'=>'00:00:00'); //just today
		if(count($command->queryAll())>0){
			$datas=array_unique($command->queryAll());
			return count($datas);
		}else
			return 0;
	}

	public function getBrowserPercentage($mobile=1)
	{
		$sql='SELECT COUNT(v.mobile) as count FROM tbl_core_visitor v WHERE DATEDIFF(NOW(),v.date_entry)=:diff AND TIMEDIFF(NOW(),v.date_expired)<=:timediff AND mobile=:mobile AND v.active=:active ORDER BY id DESC';
		$command=Yii::app()->db->createCommand($sql);
		$command->params=array('diff'=>0,'timediff'=>'00:00:00','mobile'=>$mobile, 'active'=>1); //just today
		$row=$command->queryRow();
		return (int)$row['count'];
	}

	public function getCountVisitor($date,$type='pageview')
	{
		switch($type){
			case 'pageview':
				$sql='SELECT COUNT(v.id) as count FROM tbl_core_visitor v WHERE DATE_FORMAT(v.date_entry,"%Y-%m-%d")=:date ORDER BY v.id DESC';
				$command=Yii::app()->db->createCommand($sql);
				$command->params=array('date'=>$date);
				$row=$command->queryRow();
				return (int)$row['count'];
				break;
			case 'session':
				$sql='SELECT COUNT(DISTINCT v.session_id) as count FROM tbl_core_visitor v WHERE DATE_FORMAT(v.date_entry,"%Y-%m-%d")=:date GROUP BY v.session_id';
				$command=Yii::app()->db->createCommand($sql);
				$command->params=array('date'=>$date);
				$row=$command->queryAll();
				return count($row);
				break;
			case 'pageviewmonthly':
				$sql='SELECT COUNT(v.id) as count FROM tbl_core_visitor v WHERE DATE_FORMAT(v.date_entry,"%Y-%m")=:date ORDER BY v.id DESC';
				$command=Yii::app()->db->createCommand($sql);
				$command->params=array('date'=>date("Y-m",strtotime($date)));
				$row=$command->queryRow();
				return (int)$row['count'];
				break;
			case 'sessionmonthly':
				$sql='SELECT COUNT(DISTINCT v.session_id) as count FROM tbl_core_visitor v WHERE DATE_FORMAT(v.date_entry,"%Y-%m")=:date GROUP BY v.session_id';
				$command=Yii::app()->db->createCommand($sql);
				$command->params=array('date'=>date("Y-m",strtotime($date)));
				$row=$command->queryAll();
				return count($row);
				break;
		}
	}

	public function getPageViewInterval($date_from=null,$date_to=null)
	{
		if(empty($date_from))
			$date_from=date('Y-m-d', strtotime('last month'));
		if(empty($date_to))
			$date_to=date('Y-m-d');
		$begin = new DateTime($date_from);
		$end = new DateTime($date_to);
		$end = $end->modify( '+1 day' ); 

		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);

		foreach($daterange as $date){
			$items[]=array(
				'y'=>$date->format("Y-m-d"),
				'a'=>self::getCountVisitor($date->format("Y-m-d"),'pageview'),
				'b'=>self::getCountVisitor($date->format("Y-m-d"),'session'),
			);
		}
		return $items;
	}

	public function getAverageUsers()
	{
		$date_from=date('Y-m-d', strtotime('last month'));
		$date_to=date('Y-m-d');
		$sql='SELECT COUNT(DISTINCT v.session_id) as count FROM tbl_core_visitor v WHERE DATE_FORMAT(v.date_entry,"%Y-%m-%d")>=:date_from AND DATE_FORMAT(v.date_entry,"%Y-%m-%d")<=:date_to GROUP BY DATE_FORMAT(v.date_entry,"%Y-%m-%d")';
		$command=Yii::app()->db->createCommand($sql);
		$command->params=array('date_from'=>$date_from,'date_to'=>$date_to);
		$rows=$command->queryAll();
		if(count($rows)>0){
			$items=array();
			$sum=0;
			foreach($rows as $row){
				$sum+=$row['count'];
			}
			if($sum>0){
				return round($sum/count($rows),0);
			}
		}
		return 0;
	}

	public function getAveragePageView()
	{
		$date_from=date('Y-m-d', strtotime('last month'));
		$date_to=date('Y-m-d');
		$sql='SELECT COUNT(v.session_id) as count FROM tbl_core_visitor v WHERE DATE_FORMAT(v.date_entry,"%Y-%m-%d")>=:date_from AND DATE_FORMAT(v.date_entry,"%Y-%m-%d")<=:date_to GROUP BY DATE_FORMAT(v.date_entry,"%Y-%m-%d")';
		$command=Yii::app()->db->createCommand($sql);
		$command->params=array('date_from'=>$date_from,'date_to'=>$date_to);
		$rows=$command->queryAll();
		if(count($rows)>0){
			$items=array();
			$sum=0;
			foreach($rows as $row){
				$sum+=$row['count'];
			}
			if($sum>0){
				return round($sum/count($rows),0);
			}
		}
		return 0;
	}

	public function getUniqueVisitors()
	{
		$sql='SELECT COUNT(DISTINCT v.ip_address) as count FROM tbl_core_visitor v';
		$command=Yii::app()->db->createCommand($sql);
		$row=$command->queryRow();
		return $row['count'];
	}

	public function getAverageDuration()
	{
		$date_from=date('Y-m-d', strtotime('last week'));
		$date_to=date('Y-m-d');
		$sql='SELECT DISTINCT v.session_id FROM tbl_core_visitor v WHERE DATE_FORMAT(v.date_entry,"%Y-%m-%d")>=:date_from AND DATE_FORMAT(v.date_entry,"%Y-%m-%d")<=:date_to GROUP BY v.session_id';
		$command=Yii::app()->db->createCommand($sql);
		$command->params=array('date_from'=>$date_from,'date_to'=>$date_to);
		$rows=$command->queryAll();
		$items=array();
		if(count($rows)>0){
			foreach($rows as $row){
				$sql2='SELECT TIMEDIFF(MAX(v.date_entry),MIN(v.date_entry)) as diff FROM tbl_core_visitor v WHERE v.session_id=:session_id';
				$command2=Yii::app()->db->createCommand($sql2);
				$command2->params=array('session_id'=>$row['session_id']);
				$row2=$command2->queryRow();
				if($row2['diff']!='00:00:00')
					$items[]=strtotime($row2['diff']);
			}
		}
		if(count($items)>0)
			return date("H:i:s",round(array_sum($items)/count($items),0));
		else
			return '00:00:00';
	}
}
