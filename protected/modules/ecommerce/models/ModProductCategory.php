<?php

/**
 * This is the model class for table "{{mod_product_category}}".
 *
 * The followings are the available columns in table '{{mod_product_category}}':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $icon_url
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ModProductCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_product_category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, date_entry', 'required'),
			array('level, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('icon_url, slug', 'length', 'max'=>255),
			array('icon_url', 'file', 'safe'=>true, 'allowEmpty' => true, 'types'=>'jpg,jpeg,png'),
			array('childs, parents, icon_fa, icon_url, description, date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, description, icon_url, childs, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'product_rel'=>array(self::HAS_MANY,'ModProduct','product_category_id'),
			'items_count'=>array(self::STAT,'ModProduct','product_category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => Yii::t('EcommerceModule.product','Category Title'),
			'description' => Yii::t('EcommerceModule.product','Description'),
			'icon_url' => Yii::t('EcommerceModule.product','Icon Url'),
			'childs' => Yii::t('EcommerceModule.product','Childs'),
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('icon_url',$this->icon_url,true);
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
	 * @return ModProductCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getItem($id=0)
	{
		if($id>0)
			$model = self::model()->findByPk($id);
		else
			$model = $this;
		return $model;
	}

	public function getItems($title='Pilih Kategori', $id=0)
	{
		$items = array();
		if(!empty($title))
			$items['']=$title;
		$items = $items + CHtml::listData(self::model()->findAll(), 'id', 'title');
		if($id>0)
			return $items[$id];
		else
			return $items;
	}

	public function getChildItems($id=0,$in_array=false)
	{
		if($id>0)
			$model = self::model()->findByPk($id);
		else
			$model = $this;
		if(!empty($model->childs)){
			$childs = CJSON::decode($model->childs,true);
			if($in_array)
				return $childs;
			else{
				$txt=array();
				if(is_array($childs)){
					foreach($childs as $i=>$child){
						$imodel = self::model()->findByPk($child);
						if($imodel instanceof ModProductCategory)
							$txt[] = $imodel->title;
					}
				}
				return implode(", ",$txt);
			}
		}
		return null;
	}

	public function getIonTitle($id=0)
	{
		if($id>0)
			$model = self::model()->findByPk($id);
		else
			$model = $this;
		if(!empty($model->icon_fa)){
			return '<i class="fa '.$model->icon_fa.'"></i> '.$model->title;
		}
		return $model->title;
	}

	public function getIon($id=0)
	{
		if($id>0)
			$model = self::model()->findByPk($id);
		else
			$model = $this;
		if(!empty($model->icon_fa)){
			return '<i class="fa '.$model->icon_fa.'"></i> ';
		}
		return false;
	}

	public function getIconUrl($id=0)
	{
		if($id>0)
			$model = self::model()->findByPk($id);
		else
			$model = $this;
		if(!empty($model->icon_url)){
			return Yii::app()->request->baseUrl.'/'.$model->icon_url;
		}
		return false;
	}

	public function getIcon($id=0)
	{
		return self::getIconUrl($id);
	}

	public function items($type=null,$title=null)
	{
		$criteria=new CDbCriteria;
		$criteria->order='id DESC';
		$models=self::model()->findAll($criteria);
		$items=array();
		if(!empty($title))
			$items['']=$title;
		foreach($models as $model){
			$items[$model->id]=$model->title;
		}
		return $items;
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
		if($model->items_count>0){
			foreach($model->product_rel as $item){
				$items[]=array('label'=>$item->title,'url'=>array('/'.$model->slug.'/'.$item->slug));
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
		return Yii::app()->createUrl('/'.$model->slug);
	}

	public function createSlug($title=null)
	{
		if(empty($title))
			$title=$this->name;
        $title = preg_replace('/[^a-z0-9-]/', '-', strtolower($title));
        $title = preg_replace('/-+/', "-", $title);
        $title = trim($title, '-');
		return $title;
	}

	public function getDataProvider($data=array())
	{
		$criteria=new CDbCriteria;
		$criteria->order='t.title ASC';

		return new CActiveDataProvider(__CLASS__,
			array(
				'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>(isset($data['pageSize']))? $data['pageSize']:12,
				)
			));
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

	public function getClientCount($id=0)
	{
		if($id==0)
			$id=$this->id;
		$criteria = new CDbCriteria;
		$criteria->compare('product_item_rel.product_id',$id);
		$criteria->with=array('product_item_rel');
		$count = Clients::model()->count($criteria);
		return $count;
	}

	public function getCmenuitems($linkOptions=array())
	{
		$criteria=new CDbCriteria;
		$criteria->order='title ASC';
		
		$models=self::model()->findAll($criteria);
		$list=array();
		foreach($models as $model){
			if(!empty($model->childs))
				$list[$model->id]=array('label'=>Yii::t('menu',$model->title), 'url'=>'#', 'items'=>self::child($model->id),'linkOptions'=>$linkOptions);
			else{
				if($model->items_count>0){
					$list[$model->id]=array('label'=>Yii::t('menu',$model->title), 'url'=>array('/'.$model->slug), 'items'=>ModProduct::getCmenuitems($model->id), 'linkOptions'=>$linkOptions);
				}else
					$list[$model->id]=array('label'=>Yii::t('menu',$model->title), 'url'=>array('/'.$model->slug), 'linkOptions'=>$linkOptions);
			}
		}
		return $list;
	}
	
	public function child($id)
	{
		$model = self::model()->findByPk($id);
		$items = CJSON::decode($model->childs);
		$datas = array();
		foreach($items as $item){
			/*if(!empty($model->childs))
				$datas[]=array('label'=>Yii::t('menu',$model->title), 'items'=>self::child($model->id));
			else*/
				$datas[]=array('label'=>Yii::t('menu',$model->title), 'url'=>array('/'.$model->slug));
		}
		return $datas;
	}
}
