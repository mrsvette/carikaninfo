<?php

/**
 * This is the model class for table "{{mod_product}}".
 *
 * The followings are the available columns in table '{{mod_product}}':
 * @property string $id
 * @property string $product_category_id
 * @property string $product_payment_id
 * @property string $form_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $unit
 * @property integer $active
 * @property string $status
 * @property integer $hidden
 * @property integer $is_addon
 * @property string $setup
 * @property string $addons
 * @property string $icon_url
 * @property integer $allow_quantity_select
 * @property integer $stock_control
 * @property integer $quantity_in_stock
 * @property string $plugin
 * @property string $plugin_config
 * @property string $upgrades
 * @property string $priority
 * @property string $config
 * @property string $type
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ModProduct extends CActiveRecord
{
	const UNIT_DAY          = 'D';
    const UNIT_WEEK         = 'W';
    const UNIT_MONTH        = 'M';
    const UNIT_YEAR         = 'Y';

	const SETUP_AFTER_ORDER	= 'after_order';
	const SETUP_AFTER_PAYMENT	='after_payment';
	const SETUP_MANUAL		= 'manual';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_product}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_entry', 'required'),
			array('active, hidden, is_addon, allow_quantity_select, stock_control, quantity_in_stock, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('product_category_id, product_payment_id, form_id, priority', 'length', 'max'=>20),
			array('title, slug, icon_url, plugin, type', 'length', 'max'=>255),
			array('unit, status, setup', 'length', 'max'=>50),
			array('description, addons, plugin_config, upgrades, config, date_update', 'safe'),
			array('title, slug','required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_category_id, product_payment_id, form_id, title, slug, description, unit, active, status, hidden, is_addon, setup, addons, icon_url, allow_quantity_select, stock_control, quantity_in_stock, plugin, plugin_config, upgrades, priority, config, type, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'category_rel'=>array(self::BELONGS_TO,'ModProductCategory','product_category_id'),
			'payment_rel'=>array(self::BELONGS_TO,'ModProductPayment','product_payment_id'),
			'images_rel'=>array(self::HAS_MANY,'ModProductImages','product_id'),
			'client_rel'=>array(self::HAS_MANY,'ModClientOrder','product_id'),
			'client_count'=>array(self::STAT,'ModClientOrder','product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_category_id' => Yii::t('EcommerceModule.product','Product Category'),
			'product_payment_id' => Yii::t('EcommerceModule.product','Product Payment'),
			'form_id' => 'Form',
			'title' => Yii::t('EcommerceModule.product','Title'),
			'slug' => 'Slug',
			'description' => Yii::t('EcommerceModule.product','Description'),
			'unit' => Yii::t('EcommerceModule.product','Unit'),
			'active' => Yii::t('EcommerceModule.product','Active'),
			'status' => 'Status',
			'hidden' => Yii::t('EcommerceModule.product','Hidden'),
			'is_addon' => 'Is Addon',
			'setup' => Yii::t('EcommerceModule.product','Setup'),
			'addons' => 'Addons',
			'icon_url' => Yii::t('EcommerceModule.product','Icon Url'),
			'allow_quantity_select' => Yii::t('EcommerceModule.product','Allow Quantity Select'),
			'stock_control' => Yii::t('EcommerceModule.product','Stock Control'),
			'quantity_in_stock' => Yii::t('EcommerceModule.product','Quantity In Stock'),
			'plugin' => 'Plugin',
			'plugin_config' => Yii::t('EcommerceModule.product','Plugin Config'),
			'upgrades' => 'Upgrades',
			'priority' => Yii::t('EcommerceModule.product','Priority'),
			'config' => Yii::t('EcommerceModule.product','Config'),
			'type' => Yii::t('EcommerceModule.product','Type'),
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('product_category_id',$this->product_category_id,true);
		$criteria->compare('product_payment_id',$this->product_payment_id,true);
		$criteria->compare('form_id',$this->form_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('hidden',$this->hidden);
		$criteria->compare('is_addon',$this->is_addon);
		$criteria->compare('setup',$this->setup,true);
		$criteria->compare('addons',$this->addons,true);
		$criteria->compare('icon_url',$this->icon_url,true);
		$criteria->compare('allow_quantity_select',$this->allow_quantity_select);
		$criteria->compare('stock_control',$this->stock_control);
		$criteria->compare('quantity_in_stock',$this->quantity_in_stock);
		$criteria->compare('plugin',$this->plugin,true);
		$criteria->compare('plugin_config',$this->plugin_config,true);
		$criteria->compare('upgrades',$this->upgrades,true);
		$criteria->compare('priority',$this->priority,true);
		$criteria->compare('config',$this->config,true);
		$criteria->compare('type',$this->type,true);
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
	 * @return ModProduct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getStatuses($status=null)
	{
		$items=array('enabled'=>'Enabled','disabled'=>'Disabled');
		return (!empty($status))? $items[$status] : $items;
	}

	public function getHiddenStatus($status=null)
	{
		$items=array(Yii::t('EcommerceModule.product','No'),Yii::t('EcommerceModule.product','Yes'));
		return (!empty($status))? $items[$status] : $items;
	}

	public function getSetupStatus($status=null)
	{
		$items=array(
			self::SETUP_AFTER_ORDER=>Yii::t('EcommerceModule.product','After order is placed'),
			self::SETUP_AFTER_PAYMENT=>Yii::t('EcommerceModule.product','After Payment'),
			self::SETUP_MANUAL=>Yii::t('EcommerceModule.product','Manual activation'),
		);
		return (!empty($status))? $items[$status] : $items;
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl('product/view', array(
				'id'=>$this->id,
				'title'=>$this->title,
			));
	}

	public function getParseContent($char=20,$include_readmore=false)
	{
		$p = new CHtmlPurifier();
		$pecah=explode(" ",strip_tags($p->purify($this->description)));
		if(count($pecah)<=$char){
			if($include_readmore)
				array_push($pecah, '. . . '.CHtml::link('Read more',$this->url));
			$news=implode(" ",$pecah);
		}else{
			$new_arr=array_slice($pecah, 0, $char); 
			if($include_readmore)  
				$new_arr[$char]='. . . '.CHtml::link('Read more',$this->url);
			else
				$new_arr[$char]='. . .';
			$news=implode(" ",$new_arr);
		}
		return $news;
	}

	public function items($product_category_id=null,$limit=100,$title=null)
	{
		$criteria=new CDbCriteria;
		if(!empty($product_category_id))
			$criteria->compare('product_category_id',$product_category_id);
		$criteria->limit=$limit;
		$models=self::model()->findAll($criteria);
		$items=array();
		if(!empty($title))
			$items['']=$title;
		foreach($models as $model){
			$items[$model->id]=$model->title;
		}
		return $items;
	}

	public function getDataProviderByType($product_type,$limit=10,$level=0)
	{
		$criteria=new CDbCriteria;
		//$criteria->compare('category_rel.type',$product_type);
		$criteria->compare('level',$level);
		//$criteria->with=array('category_rel');
		$criteria->limit=$limit;
		
		$dataProvider=new CActiveDataProvider(__CLASS__,array('criteria'=>$criteria,'pagination'=>false));
		
		return $dataProvider;
	}

	public function carousel()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('category_rel.type',3);
		$criteria->with=array('category_rel');
		$models=self::model()->findAll($criteria);
		$items=array();
		foreach($models as $model){
			$items[$model->id]=array(
					'thumb_url'=>ProductImages::getImageByType($model->id,2)->thumb,
					'image_thumb'=>ProductImages::getImageByType($model->id,2)->image,
					//'link'=>Yii::app()->request->baseUrl.'/'.$model->image_one_rel->src.$model->image_one_rel->image,
					'link'=>'#',	
				);
		}
		return $items;
	}

	public function hasChild($product_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('parent_id',$product_id);
		//$criteria->compare('level','>1');
		$count=self::model()->count($criteria);
		return ($count>0)? true : false;
	}

	public function findOneBySlug($slug)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('LOWER(slug)',strtolower($slug));
		$model = self::model()->find($criteria);
		return $model;
	}

	public function getItemsMenu($data=null)
	{
		if(isset($data['id']))
			$model=self::model()->findByPk($id);
		elseif(isset($data['id']))
			$model=self::findOneBySlug($data['slug']);
		else
			$model=$this;
		$items=array();
		if($model->client_count>0){
			foreach($model->client_rel as $item){
				$items[]=array('label'=>$item->name,'url'=>array('/'.$model->category_rel->slug.'/'.$model->slug.'/'.$item->slug));
			}
		}
		return $items;
	}

	public function getPermalink($id=0)
	{
		if($id==0)
			$model=$this;
		else
			$model=self::model()->findByPk($id);
		return Yii::app()->createUrl('/'.$model->category_rel->slug.'/'.$model->slug);
	}

	public function createSlug($title=null)
	{
		if(empty($title))
			$title=$this->title;
        $title = preg_replace('/[^a-z0-9-]/', '-', strtolower($title));
        $title = preg_replace('/-+/', "-", $title);
        $title = trim($title, '-');
		return $title;
	}

	public function getDataProvider($data=array())
	{
		$criteria=new CDbCriteria;
		$criteria->compare('product_category_id',$data['product_category_id']);
		$criteria->order='t.title ASC';

		return new CActiveDataProvider(__CLASS__,
			array(
				'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>(isset($data['pageSize']))? $data['pageSize']:10,
				)
			));
	}

	public function getItems_count()
	{
		return $this->client_count;
	}

	public function parseContent($char=100)
	{
		$p = new CHtmlPurifier();
		$pecah=explode(" ",$p->purify($this->description));
		if(count($pecah)<$char){
			array_push($pecah, '. . .');
			$news=implode(" ",$pecah);
		}else{
			$new_arr=array_slice($pecah, 0, $char);   
			$new_arr[$char]='. . .';
			$news=implode(" ",$new_arr);
		}
		return $news;
	}

	public function getIcon($id=0)
	{
		if($id==0)
			$model=$this;
		else
			$model=self::model()->findByPk($id);
		$file_name = $model->slug.'.png';
		$path = explode("/protected",Yii::app()->basePath);
		if(!file_exists($path[0].'/uploads/images/'.$file_name))
			$file_name = 'icon-subcategory.png';
		return Yii::app()->request->baseUrl.'/uploads/images/'.$file_name;
	}

	public function periods($detail=true,$period=null,$object=false)
	{
		$items = array(
				'1W'=>array('title'=>'1 Minggu','term'=>'Minggu','code'=>'w_price','enabling'=>'w_enabled','qty'=>1,'unit'=>self::UNIT_WEEK),
				'1M'=>array('title'=>'1 Bulan','term'=>'Bulan','code'=>'m_price','enabling'=>'m_enabled','qty'=>1,'unit'=>self::UNIT_MONTH),
				'3M'=>array('title'=>'3 Bulan','term'=>'Bulan','code'=>'q_price','enabling'=>'q_enabled','qty'=>3,'unit'=>self::UNIT_MONTH),
				'6M'=>array('title'=>'6 Bulan','term'=>'Bulan','code'=>'b_price','enabling'=>'b_enabled','qty'=>6,'unit'=>self::UNIT_MONTH),
				'1Y'=>array('title'=>'1 Tahun','term'=>'Tahun','code'=>'a_price','enabling'=>'a_enabled','qty'=>1,'unit'=>self::UNIT_YEAR),
				'2Y'=>array('title'=>'2 Tahun','term'=>'Tahun','code'=>'bia_price','enabling'=>'bia_enabled','qty'=>2,'unit'=>self::UNIT_YEAR),
				'3Y'=>array('title'=>'3 Tahun','term'=>'Tahun','code'=>'tria_price','enabling'=>'tria_enabled','qty'=>3,'unit'=>self::UNIT_YEAR),
			);
		if(!$detail){
			$periods = array();
			foreach($items as $ip=>$item){
				$periods[$ip] = $item['title'];
			}
			return $periods;
		}
		if(!empty($period)){
			if($object)
				return json_decode(json_encode($items[$period]));
			return $items[$period];
		}
		return $items;
	}

	public function getPricing($id=0,$object=true)
	{
		if($id==0)
			$model = $this;
		else
			$model = self::model()->findByPk($id);
		$payment = $model->payment_rel;
		switch ($model->payment_rel->type){
			case 'free':
				$price = 0;
				break;
			case 'once':
				$price = $model->payment_rel->once_price;
				break;
			case 'requrrent':
				$price = array();
				foreach(self::periods() as $i=>$period){
					$code = $period['code'];
					$enabling = $period['enabling'];
					if($model->payment_rel->$enabling>0)
						$price[$i] = $model->payment_rel->$code;
				}
				if($object)
					$price = json_decode(json_encode($price));
				break;
		}
		return $price;
	}

	public function getCmenuitems($id=0)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('product_category_id',$id);
		$criteria->order='title ASC';
		
		$models=self::model()->findAll($criteria);
		$list=array();
		foreach($models as $model){
			$list[$model->id]=array('label'=>Yii::t('menu',$model->title), 'url'=>array('/'.$model->category_rel->slug.'/'.$model->slug), 'linkOptions'=>$linkOptions);
		}
		return $list;
	}
}
