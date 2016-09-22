<?php

/**
 * This is the model class for table "{{mod_product_payment}}".
 *
 * The followings are the available columns in table '{{mod_product_payment}}':
 * @property string $id
 * @property string $type
 * @property string $once_price
 * @property string $once_setup_price
 * @property string $w_price
 * @property string $m_price
 * @property string $q_price
 * @property string $b_price
 * @property string $a_price
 * @property string $bia_price
 * @property string $tria_price
 * @property string $w_setup_price
 * @property string $m_setup_price
 * @property string $q_setup_price
 * @property string $b_setup_price
 * @property string $a_setup_price
 * @property string $bia_setup_price
 * @property string $tria_setup_price
 * @property integer $w_enabled
 * @property integer $m_enabled
 * @property integer $q_enabled
 * @property integer $b_enabled
 * @property integer $a_enabled
 * @property integer $bia_enabled
 * @property integer $tria_enabled
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ModProductPayment extends CActiveRecord
{
	public $total;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_product_payment}}';
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
			array('w_enabled, m_enabled, q_enabled, b_enabled, a_enabled, bia_enabled, tria_enabled, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>30),
			array('once_price, once_setup_price, w_price, m_price, q_price, b_price, a_price, bia_price, tria_price, w_setup_price, m_setup_price, q_setup_price, b_setup_price, a_setup_price, bia_setup_price, tria_setup_price', 'length', 'max'=>18),
			array('date_update, total', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, once_price, once_setup_price, w_price, m_price, q_price, b_price, a_price, bia_price, tria_price, w_setup_price, m_setup_price, q_setup_price, b_setup_price, a_setup_price, bia_setup_price, tria_setup_price, w_enabled, m_enabled, q_enabled, b_enabled, a_enabled, bia_enabled, tria_enabled, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'product_rel'=>array(self::HAS_ONE,'ModProduct','id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => Yii::t('EcommerceModule.product','Payment Type'),
			'once_price' => 'Once Price',
			'once_setup_price' => 'Once Setup Price',
			'w_price' => Yii::t('EcommerceModule.product','Weekly Price'),
			'm_price' => Yii::t('EcommerceModule.product','Monthly Price'),
			'q_price' => Yii::t('EcommerceModule.product','3 Month Price'),
			'b_price' => Yii::t('EcommerceModule.product','6 Month Price'),
			'a_price' => Yii::t('EcommerceModule.product','One Year Price'),
			'bia_price' => Yii::t('EcommerceModule.product','2 Year Price'),
			'tria_price' => Yii::t('EcommerceModule.product','3 Year Price'),
			'w_setup_price' => Yii::t('EcommerceModule.product','Weekly Setup Price'),
			'm_setup_price' => Yii::t('EcommerceModule.product','Montly Setup Price'),
			'q_setup_price' => Yii::t('EcommerceModule.product','3 Month Setup Price'),
			'b_setup_price' => Yii::t('EcommerceModule.product','6 Month Setup Price'),
			'a_setup_price' => Yii::t('EcommerceModule.product','One Year Setup Price'),
			'bia_setup_price' => Yii::t('EcommerceModule.product','2 Year Setup Price'),
			'tria_setup_price' => Yii::t('EcommerceModule.product','3 Year Setup Price'),
			'w_enabled' => Yii::t('EcommerceModule.product','Weekly Enabled'),
			'm_enabled' => Yii::t('EcommerceModule.product','Monthly Enabled'),
			'q_enabled' => Yii::t('EcommerceModule.product','3 Month Enabled'),
			'b_enabled' => Yii::t('EcommerceModule.product','6 Month Enabled'),
			'a_enabled' => Yii::t('EcommerceModule.product','One Year Enabled'),
			'bia_enabled' => Yii::t('EcommerceModule.product','2 Year Enabled'),
			'tria_enabled' => Yii::t('EcommerceModule.product','3 Year Enabled'),
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('once_price',$this->once_price,true);
		$criteria->compare('once_setup_price',$this->once_setup_price,true);
		$criteria->compare('w_price',$this->w_price,true);
		$criteria->compare('m_price',$this->m_price,true);
		$criteria->compare('q_price',$this->q_price,true);
		$criteria->compare('b_price',$this->b_price,true);
		$criteria->compare('a_price',$this->a_price,true);
		$criteria->compare('bia_price',$this->bia_price,true);
		$criteria->compare('tria_price',$this->tria_price,true);
		$criteria->compare('w_setup_price',$this->w_setup_price,true);
		$criteria->compare('m_setup_price',$this->m_setup_price,true);
		$criteria->compare('q_setup_price',$this->q_setup_price,true);
		$criteria->compare('b_setup_price',$this->b_setup_price,true);
		$criteria->compare('a_setup_price',$this->a_setup_price,true);
		$criteria->compare('bia_setup_price',$this->bia_setup_price,true);
		$criteria->compare('tria_setup_price',$this->tria_setup_price,true);
		$criteria->compare('w_enabled',$this->w_enabled);
		$criteria->compare('m_enabled',$this->m_enabled);
		$criteria->compare('q_enabled',$this->q_enabled);
		$criteria->compare('b_enabled',$this->b_enabled);
		$criteria->compare('a_enabled',$this->a_enabled);
		$criteria->compare('bia_enabled',$this->bia_enabled);
		$criteria->compare('tria_enabled',$this->tria_enabled);
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
	 * @return ModProductPayment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTypes($type=null)
	{
		$items=array(
			'free'=>Yii::t('EcommerceModule.product','Free'),
			'once'=>Yii::t('EcommerceModule.product','Once'),
			'requrrent'=>Yii::t('EcommerceModule.product','Requrrent'),
		);
		return (!empty($type))? $items[$type] : $items;
	}
}
