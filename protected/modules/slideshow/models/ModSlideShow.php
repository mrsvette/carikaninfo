<?php

/**
 * This is the model class for table "{{mod_slide_show}}".
 *
 * The followings are the available columns in table '{{mod_slide_show}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string $status
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ModSlideShow extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_slide_show}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, slug, status, type, date_entry, user_entry', 'required'),
			array('user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('name, slug', 'length', 'max'=>128),
			array('status', 'length', 'max'=>16),
			array('description, date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, slug, status, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'items_rel'=>array(self::HAS_MANY,'ModSlideShowItem','slide_show_id'),
			'items_count'=>array(self::STAT,'ModSlideShowItem','slide_show_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => Yii::t('SlideshowModule.general','Name'),
			'description' => Yii::t('SlideshowModule.general','Description'),
			'slug' => 'Slug',
			'status' => 'Status',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('status',$this->status,true);
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
	 * @return ModSlideShow the static model class
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

	public function getTypes($status=null)
	{
		$items=array('revolution'=>'Revolution','carousel'=>'Carousel');
		return (!empty($status))? $items[$status] : $items;
	}

	public function getUsage($id=0)
	{
		if($id==0)
			$model = $this;
		else
			$model = self::model()->findByPk($id);
		return "{% do this.widget('application.modules.slideshow.components.SlideShowWidget',{'id':'".$model->slug."'}) %}";
	}
}
