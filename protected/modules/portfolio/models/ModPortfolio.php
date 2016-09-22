<?php

/**
 * This is the model class for table "{{mod_portfolio}}".
 *
 * The followings are the available columns in table '{{mod_portfolio}}':
 * @property integer $id
 * @property string $image
 * @property string $thumb
 * @property string $src
 * @property string $client_name
 * @property integer $category_id
 * @property string $website
 * @property string $start_date
 * @property string $end_date
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ModPortfolio extends CActiveRecord
{
	public $title_cr;
	public $content_cr;
	public $p_image;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_portfolio}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, status, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('client_name', 'length', 'max'=>128),
			array('website', 'length', 'max'=>256),
			array('start_date, end_date, date_entry, date_update', 'safe'),
			array('p_image', 'file', 'safe'=>true, 'allowEmpty' => true, 'types'=>self::getAllowedTypes(), 'maxSize'=>self::getMaxSize()),
			array('title_cr, title_cr, p_image', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, p_image, client_name, category_id, website, start_date, end_date, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'author_rel' => array(self::BELONGS_TO, 'User', 'user_entry'),
			'content_rel' => array(self::HAS_ONE, 'ModPortfolioContent', 'portfolio_id', 'condition'=>'content_rel.language="'.Yii::app()->user->getState('language')->id.'"'),
			'category_rel' => array(self::BELONGS_TO, 'ModPortfolioCategory', 'category_id'),
			'images_rel' => array(self::HAS_MANY, 'ModPortfolioImages', 'portfolio_id'),
			'image_one_rel' => array(self::HAS_MANY, 'ModPortfolioImages', 'portfolio_id'),
			'images_count' => array(self::STAT, 'ModPortfolioImages', 'portfolio_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'p_image' => Yii::t('PortfolioModule.portfolio','Image'),
			'client_name' => Yii::t('PortfolioModule.portfolio','Client Name'),
			'category_id' => Yii::t('PortfolioModule.portfolio','Category'),
			'website' => Yii::t('PortfolioModule.portfolio','Website'),
			'start_date' => Yii::t('PortfolioModule.portfolio','Start Date'),
			'end_date' => Yii::t('PortfolioModule.portfolio','End Date'),
			'date_entry' => Yii::t('global','Date Entry'),
			'user_entry' => Yii::t('global','User Entry'),
			'date_update' => Yii::t('global','Date Update'),
			'user_update' => Yii::t('global','User Update'),
		);
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
		$criteria->compare('client_name',$this->client_name,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_entry',$this->user_entry);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('user_update',$this->user_update);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModPortfolio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getAllowedTypes()
	{
		$default = 'jpg, jpeg, png';
		$allowed = Extension::getConfigByModule('gallery','portfolio_allowed_file_type');
		if(empty($allowed))
			$allowed = $default;
		return $allowed;
	}

	public function getMaxSize()
	{
		$default = 3000000; //3MB
		$allowed = Extension::getConfigByModule('gallery','portfolio_max_file_size');
		if(empty($allowed))
			$allowed = $default;
		return (int)$allowed;
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl($use_slug=true)
	{
		if($use_slug){
			return Yii::app()->createUrl('/site/helper', array(
				'slug'=>$this->content_rel->slug,
				'mod'=>'portfolio'
			));
		}else{
			return Yii::app()->createUrl('/site/helper', array(
				'id'=>$this->id,
				'title'=>$this->content_rel->title
			));
		}
	}

	public function parseContent($char=100,$include_readmore=true)
	{
		$p = new CHtmlPurifier();
		$pecah=explode(" ",strip_tags($p->purify($this->content_rel->content)));
		if(count($pecah)<$char){
			if($include_readmore)
				array_push($pecah, '. . . '.CHtml::link(Yii::t('post','Read more'),$this->url));
			else
				array_push($pecah, '. . .');
			$news=implode(" ",$pecah);
		}else{
			$new_arr=array_slice($pecah, 0, $char); 
			if($include_readmore)  
				$new_arr[$char]='. . . '.CHtml::link(Yii::t('post','Read more'),$this->url);
			else
				$new_arr[$char]='. . .';
			$news=implode(" ",$new_arr);
		}
		return $news;
	}

	public function getContent($lang=1,$id=0)
	{
		$criteria = new CDbCriteria;
		if($id==0)
			$criteria->compare('portfolio_id',$this->id);
		else
			$criteria->compare('portfolio_id',$id);
		$criteria->compare('language',$lang);
		$model = ModPortfolioContent::model()->find($criteria);
		return $model;
	}

	public function getFirstImage($id=0)
	{
		$criteria = new CDbCriteria;
		if($id==0)
			$criteria->compare('t.portfolio_id',$this->id);
		else
			$criteria->compare('t.portfolio_id',$id);
		$criteria->order = 't.id ASC';
		$criteria->limit = 1;

		$model = ModPortfolioImages::model()->find($criteria);
		return $model;
	}

	public function getImageProvider($data=array())
	{
		$criteria = new CDbCriteria;
		if($id==0)
			$criteria->compare('t.portfolio_id',$this->id);
		else
			$criteria->compare('t.portfolio_id',$data['id']);
		$criteria->order = 't.id ASC';

		$dataProvider = new CActiveDataProvider('ModPortfolioImages',array('criteria'=>$criteria));
		return $dataProvider;
	}
}
