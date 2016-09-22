<?php

/**
 * This is the model class for table "{{mod_invoice_item}}".
 *
 * The followings are the available columns in table '{{mod_invoice_item}}':
 * @property string $id
 * @property string $invoice_id
 * @property string $type
 * @property string $rel_id
 * @property string $task
 * @property string $status
 * @property string $title
 * @property string $period
 * @property string $quantity
 * @property string $unit
 * @property double $price
 * @property integer $charged
 * @property integer $taxed
 * @property string $date_entry
 * @property string $date_update
 */
class ModInvoiceItem extends CActiveRecord
{
	const TASK_VOID     = 'void';
    const TASK_ACTIVATE = 'activate';
    const TASK_RENEW    = 'renew';

    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_PENDING_SETUP = 'pending_setup';
    const STATUS_EXECUTED = 'executed';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_invoice_item}}';
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
			array('charged, taxed', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('invoice_id, quantity', 'length', 'max'=>20),
			array('type, task, status, unit', 'length', 'max'=>100),
			array('title', 'length', 'max'=>255),
			array('period', 'length', 'max'=>10),
			array('rel_id, date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, invoice_id, type, rel_id, task, status, title, period, quantity, unit, price, charged, taxed, date_entry, date_update', 'safe', 'on'=>'search'),
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
			'invoice_rel'=>array(self::BELONGS_TO,'ModInvoice','invoice_id'),
			'order_rel'=>array(self::BELONGS_TO,'ModClientOrder','rel_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'invoice_id' => 'Invoice ID',
			'type' => Yii::t('EcommerceModule.invoice','Type'),
			'rel_id' => Yii::t('EcommerceModule.invoice','Order ID'),
			'task' => Yii::t('EcommerceModule.invoice','Task'),
			'status' => Yii::t('EcommerceModule.invoice','Status'),
			'title' => Yii::t('EcommerceModule.invoice','Item Name'),
			'period' => Yii::t('EcommerceModule.invoice','Period'),
			'quantity' => Yii::t('EcommerceModule.invoice','Quantity'),
			'unit' => 'Unit',
			'price' => Yii::t('EcommerceModule.invoice','Price'),
			'charged' => Yii::t('EcommerceModule.invoice','Charged'),
			'taxed' => Yii::t('EcommerceModule.invoice','Taxed'),
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('invoice_id',$this->invoice_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('rel_id',$this->rel_id,true);
		$criteria->compare('task',$this->task,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('period',$this->period,true);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('charged',$this->charged);
		$criteria->compare('taxed',$this->taxed);
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
	 * @return ModInvoiceItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
