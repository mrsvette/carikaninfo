<?php

/**
 * This is the model class for table "{{product_images}}".
 *
 * The followings are the available columns in table '{{product_images}}':
 * @property integer $id
 * @property integer $product_id
 * @property string $image
 * @property string $thumb
 * @property string $src
 * @property string $date_entry
 * @property integer $user_entry
 */
class ModProductImages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_product_images}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, image, src, date_entry, user_entry', 'required'),
			array('product_id, type, user_entry', 'numerical', 'integerOnly'=>true),
			array('image, thumb, src', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_id, image, thumb, src, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'product_rel'=>array(self::BELONGS_TO,'ModProduct','product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => Yii::t('EcommerceModule.product','Product'),
			'image' => Yii::t('EcommerceModule.product','Image'),
			'thumb' => Yii::t('EcommerceModule.product','Thumb'),
			'src' => Yii::t('EcommerceModule.product','Src'),
			'type' => Yii::t('EcommerceModule.product','Image Type'),
			'date_entry' => Yii::t('global','Date Entry'),
			'user_entry' => Yii::t('global','User Entry'),
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
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('thumb',$this->thumb,true);
		$criteria->compare('src',$this->src,true);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_entry',$this->user_entry);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductImages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getImageByType($product_id,$type=0)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('product_id',$product_id);
		$criteria->compare('type',$type);
		$count=self::model()->count($criteria);
		if($count>0)
			return self::model()->find($criteria);
		else{
			$criteria2=new CDbCriteria;
			$criteria2->compare('product_id',$product_id);
			$criteria2->compare('type','<=1');
			return self::model()->find($criteria2);
		}
	}
}
