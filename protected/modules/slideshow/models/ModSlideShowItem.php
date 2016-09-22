<?php

/**
 * This is the model class for table "{{mod_slide_show_item}}".
 *
 * The followings are the available columns in table '{{mod_slide_show_item}}':
 * @property integer $id
 * @property string $title
 * @property string $image_path
 * @property string $caption
 * @property string $url
 * @property integer $order
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ModSlideShowItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_slide_show_item}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, date_entry, user_entry', 'required'),
			array('slide_show_id, order, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>128),
			array('caption, date_update', 'safe'),
			array('image_path', 'file', 'safe'=>true, 'allowEmpty' => false, 'types'=>'jpg,jpeg,png', 'on'=>'create'),
			array('image_path', 'file', 'safe'=>true, 'allowEmpty' => true, 'types'=>'jpg,jpeg,png', 'on'=>'update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, image_path, caption, url, order, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'slide_show_rel'=>array(self::BELONGS_TO,'ModSlideShow','slide_show_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'image_path' => 'Image Path',
			'caption' => 'Caption',
			'url' => 'Url',
			'order' => 'Order',
			'date_entry' => 'Date Entry',
			'user_entry' => 'User Entry',
			'date_update' => 'Date Update',
			'user_update' => 'User Update',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.slide_show_id',$this->slide_show_id);
		$criteria->compare('t.image_path',$this->image_path,true);
		$criteria->compare('t.caption',$this->caption,true);
		$criteria->compare('t.url',$this->url,true);
		if(!empty($this->order))
			$criteria->compare('t.order',$this->order);
		$criteria->compare('t.date_entry',$this->date_entry,true);
		$criteria->compare('t.user_entry',$this->user_entry);
		$criteria->compare('t.date_update',$this->date_update,true);
		$criteria->compare('t.user_update',$this->user_update);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModSlideShowItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getNextOrder($slide_show_id=0)
	{
		if($slide_show_id==0)
			$slide_show_id = $this->slide_show_id;
		$criteria=new CDbCriteria;
		$criteria->compare('t.slide_show_id',$slide_show_id);
		$count = self::model()->count($criteria);
		return $count + 1;
	}

	public function getItemsProvider($slide_show_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.slide_show_id',$slide_show_id);
		$criteria->order = 't.order ASC';

		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
		));
	}
}
