<?php

/**
 * This is the model class for table "{{mod_pay_gateway}}".
 *
 * The followings are the available columns in table '{{mod_pay_gateway}}':
 * @property string $id
 * @property string $name
 * @property string $gateway
 * @property string $accepted_currencies
 * @property integer $enabled
 * @property integer $allow_single
 * @property integer $allow_recurrent
 * @property integer $test_mode
 * @property string $config
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ModPayGateway extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_pay_gateway}}';
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
			array('enabled, allow_single, allow_recurrent, test_mode, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('name, gateway', 'length', 'max'=>255),
			array('accepted_currencies, config, date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, gateway, accepted_currencies, enabled, allow_single, allow_recurrent, test_mode, config, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'gateway' => 'Gateway',
			'accepted_currencies' => 'Accepted Currencies',
			'enabled' => 'Enabled',
			'allow_single' => 'Allow Single',
			'allow_recurrent' => 'Allow Recurrent',
			'test_mode' => 'Test Mode',
			'config' => 'Config',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('gateway',$this->gateway,true);
		$criteria->compare('accepted_currencies',$this->accepted_currencies,true);
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('allow_single',$this->allow_single);
		$criteria->compare('allow_recurrent',$this->allow_recurrent);
		$criteria->compare('test_mode',$this->test_mode);
		$criteria->compare('config',$this->config,true);
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
	 * @return ModPayGateway the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
