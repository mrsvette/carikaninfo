<?php

/**
 * This is the model class for table "{{mod_promo}}".
 *
 * The followings are the available columns in table '{{mod_promo}}':
 * @property string $id
 * @property string $code
 * @property string $description
 * @property string $type
 * @property string $value
 * @property integer $maxuses
 * @property integer $used
 * @property integer $freesetup
 * @property integer $once_per_client
 * @property integer $recurring
 * @property integer $active
 * @property string $products
 * @property string $periods
 * @property string $start_at
 * @property string $end_at
 * @property string $created_at
 * @property string $updated_at
 */
class ModPromo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_promo}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('maxuses, used, freesetup, once_per_client, recurring, active', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>100),
			array('type', 'length', 'max'=>30),
			array('value', 'length', 'max'=>18),
			array('start_at, end_at, created_at, updated_at', 'length', 'max'=>35),
			array('description, products, periods', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, description, type, value, maxuses, used, freesetup, once_per_client, recurring, active, products, periods, start_at, end_at, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'description' => 'Description',
			'type' => 'Type',
			'value' => 'Value',
			'maxuses' => 'Maxuses',
			'used' => 'Used',
			'freesetup' => 'Freesetup',
			'once_per_client' => 'Once Per Client',
			'recurring' => 'Recurring',
			'active' => 'Active',
			'products' => 'Products',
			'periods' => 'Periods',
			'start_at' => 'Start At',
			'end_at' => 'End At',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('maxuses',$this->maxuses);
		$criteria->compare('used',$this->used);
		$criteria->compare('freesetup',$this->freesetup);
		$criteria->compare('once_per_client',$this->once_per_client);
		$criteria->compare('recurring',$this->recurring);
		$criteria->compare('active',$this->active);
		$criteria->compare('products',$this->products,true);
		$criteria->compare('periods',$this->periods,true);
		$criteria->compare('start_at',$this->start_at,true);
		$criteria->compare('end_at',$this->end_at,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModPromo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
