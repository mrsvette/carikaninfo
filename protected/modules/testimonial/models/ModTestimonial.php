<?php

/**
 * This is the model class for table "{{mod_testimonial}}".
 *
 * The followings are the available columns in table '{{mod_testimonial}}':
 * @property integer $id
 * @property integer $client_id
 * @property string $name
 * @property string $activity
 * @property string $website
 * @property string $comment
 * @property double $rate
 * @property string $image
 * @property string $thumb
 * @property string $src
 * @property string $notes
 * @property integer $status
 * @property string $date_entry
 * @property string $date_update
 */
class ModTestimonial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_testimonial}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, activity, comment', 'required'),
			array('client_id, status', 'numerical', 'integerOnly'=>true),
			array('rate', 'numerical'),
			array('name, activity, image', 'length', 'max'=>128),
			array('website, thumb, src', 'length', 'max'=>256),
			array('notes, date_entry, date_update', 'safe'),
			array('image', 'file', 'safe'=>true, 'allowEmpty' => false, 'types'=>self::getAllowedTypes(), 'maxSize'=>self::getMaxSize(), 'on'=>'create'),
			array('image', 'file', 'safe'=>true, 'allowEmpty' => true, 'types'=>self::getAllowedTypes(), 'maxSize'=>self::getMaxSize(), 'on'=>'update'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_id, name, activity, website, comment, rate, image, thumb, src, notes, status, date_entry, date_update', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'client_id' => Yii::t('TestimonialModule.testimonial','Client'),
			'name' => Yii::t('TestimonialModule.testimonial','Name'),
			'activity' => Yii::t('TestimonialModule.testimonial','Activity'),
			'website' => Yii::t('TestimonialModule.testimonial','Website'),
			'comment' => Yii::t('TestimonialModule.testimonial','Comment'),
			'rate' => Yii::t('TestimonialModule.testimonial','Rate'),
			'image' => Yii::t('TestimonialModule.testimonial','Image'),
			'thumb' => 'Thumb',
			'src' => Yii::t('TestimonialModule.testimonial','Source'),
			'notes' => 'Notes',
			'status' => 'Status',
			'date_entry' => Yii::t('global','Date Entry'),
			'date_update' => Yii::t('global','Date Update'),
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
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('activity',$this->activity,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('rate',$this->rate);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('thumb',$this->thumb,true);
		$criteria->compare('src',$this->src,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('date_update',$this->date_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModTestimonial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getAllowedTypes()
	{
		$default = 'jpg, jpeg, png';
		$allowed = Extension::getConfigByModule('gallery','gallery_allowed_file_type');
		if(empty($allowed))
			$allowed = $default;
		return $allowed;
	}

	public function getMaxSize()
	{
		$default = 3000000; //3MB
		$allowed = Extension::getConfigByModule('gallery','gallery_max_file_size');
		if(empty($allowed))
			$allowed = $default;
		return (int)$allowed;
	}
}
