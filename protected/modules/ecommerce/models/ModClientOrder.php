<?php

/**
 * This is the model class for table "{{mod_client_order}}".
 *
 * The followings are the available columns in table '{{mod_client_order}}':
 * @property string $id
 * @property string $client_id
 * @property string $product_id
 * @property string $promo_id
 * @property string $group_id
 * @property integer $group_master
 * @property string $invoice_option
 * @property string $title
 * @property string $currency
 * @property string $unpaid_invoice_id
 * @property string $service_id
 * @property string $service_type
 * @property string $period
 * @property string $quantity
 * @property string $unit
 * @property double $price
 * @property double $discount
 * @property string $status
 * @property string $reason
 * @property string $notes
 * @property string $config
 * @property string $referred_by
 * @property string $expires_at
 * @property string $activated_at
 * @property string $suspended_at
 * @property string $unsuspended_at
 * @property string $canceled_at
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ModClientOrder extends CActiveRecord
{

    const ACTIVE                    = 'active';
    const SUSPENDED                 = 'suspended';
    const CANCELED                  = 'canceled';
	const PENDING_SETUP 			= 'pending_setup';
	const FAILED_SETUP 				= 'failed_setup';

	const ISSUE_INVOICE				= 'issue-invoice';
	const NO_INVOICE				= 'no-invoice';

	public $company_search;
	public $client_search;
	public $product_search;
	public $product_category_id;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_client_order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, client_id, product_id, period, status, date_entry', 'required'),
			array('group_master, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('price, discount', 'numerical'),
			array('client_id, product_id, promo_id, currency, unpaid_invoice_id, service_id, period, quantity', 'length', 'max'=>20),
			array('group_id, invoice_option, title, reason, referred_by', 'length', 'max'=>255),
			array('service_type, unit', 'length', 'max'=>100),
			array('status', 'length', 'max'=>50),
			array('notes, config, expires_at, activated_at, suspended_at, unsuspended_at, canceled_at, date_update', 'safe'),
			array('product_category_id','required','on'=>'create'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_id, product_id, promo_id, group_id, group_master, invoice_option, title, currency, unpaid_invoice_id, service_id, service_type, period, quantity, unit, price, discount, status, reason, notes, config, referred_by, expires_at, activated_at, suspended_at, unsuspended_at, canceled_at, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
			array('company_search, client_search, product_search','safe'),
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
			'client_rel'=>array(self::BELONGS_TO,'ModClient','client_id'),
			'product_rel'=>array(self::BELONGS_TO,'ModProduct','product_id'),
			'promo_rel'=>array(self::BELONGS_TO,'ModPromo','promo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'client_id' => Yii::t('EcommerceModule.order','Client'),
			'product_id' => Yii::t('EcommerceModule.order','Product'),
			'promo_id' => Yii::t('EcommerceModule.order','Promo'),
			'group_id' => Yii::t('EcommerceModule.order','Group'),
			'group_master' => Yii::t('EcommerceModule.order','Group Master'),
			'invoice_option' => Yii::t('EcommerceModule.order','Invoice Option'),
			'title' => Yii::t('EcommerceModule.order','Title'),
			'currency' => Yii::t('EcommerceModule.order','Currency'),
			'unpaid_invoice_id' => Yii::t('EcommerceModule.order','Unpaid Invoice'),
			'service_id' => Yii::t('EcommerceModule.order','Service'),
			'service_type' => Yii::t('EcommerceModule.order','Service Type'),
			'period' => Yii::t('EcommerceModule.order','Period'),
			'quantity' => Yii::t('EcommerceModule.order','Quantity'),
			'unit' => Yii::t('EcommerceModule.order','Unit'),
			'price' => Yii::t('EcommerceModule.order','Price'),
			'discount' => Yii::t('EcommerceModule.order','Discount'),
			'status' => Yii::t('EcommerceModule.order','Status'),
			'reason' => Yii::t('EcommerceModule.order','Reason'),
			'notes' => Yii::t('EcommerceModule.order','Notes'),
			'config' => Yii::t('EcommerceModule.order','Config'),
			'referred_by' => Yii::t('EcommerceModule.order','Referred By'),
			'expires_at' => Yii::t('EcommerceModule.order','Expires At'),
			'activated_at' => Yii::t('EcommerceModule.order','Activated At'),
			'suspended_at' => Yii::t('EcommerceModule.order','Suspended At'),
			'unsuspended_at' => Yii::t('EcommerceModule.order','Unsuspended At'),
			'canceled_at' => Yii::t('EcommerceModule.order','Canceled At'),
			'date_entry' => Yii::t('global','Date Entry'),
			'date_update' => Yii::t('global','Date Update'),
			'date_update' => Yii::t('global','Date Update'),
			'user_update' => Yii::t('global','User Update'),
			'product_category_id' => Yii::t('EcommerceModule.client','Product Category'),
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

		$criteria->compare('t.id',$this->id,true);
		$criteria->compare('t.client_id',$this->client_id,true);
		$criteria->compare('t.product_id',$this->product_id,true);
		$criteria->compare('t.promo_id',$this->promo_id,true);
		$criteria->compare('t.group_id',$this->group_id,true);
		$criteria->compare('t.group_master',$this->group_master);
		$criteria->compare('t.invoice_option',$this->invoice_option,true);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.currency',$this->currency,true);
		$criteria->compare('t.unpaid_invoice_id',$this->unpaid_invoice_id,true);
		$criteria->compare('t.service_id',$this->service_id,true);
		$criteria->compare('t.service_type',$this->service_type,true);
		$criteria->compare('t.period',$this->period,true);
		$criteria->compare('t.quantity',$this->quantity,true);
		$criteria->compare('t.unit',$this->unit,true);
		$criteria->compare('t.price',$this->price);
		$criteria->compare('t.discount',$this->discount);
		$criteria->compare('t.status',$this->status,true);
		$criteria->compare('t.reason',$this->reason,true);
		$criteria->compare('t.notes',$this->notes,true);
		$criteria->compare('t.config',$this->config,true);
		$criteria->compare('t.referred_by',$this->referred_by,true);
		$criteria->compare('t.expires_at',$this->expires_at,true);
		$criteria->compare('t.activated_at',$this->activated_at,true);
		$criteria->compare('t.suspended_at',$this->suspended_at,true);
		$criteria->compare('t.unsuspended_at',$this->unsuspended_at,true);
		$criteria->compare('t.canceled_at',$this->canceled_at,true);
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
	 * @return ModClientOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getStatuses($status=null)
	{
		$types = array(
				self::ACTIVE=>Yii::t('EcommerceModule.order','Active'),
				self::SUSPENDED=>Yii::t('EcommerceModule.order','Suspended'),
				self::CANCELED=>Yii::t('EcommerceModule.order','Canceled'),
				self::PENDING_SETUP=>Yii::t('EcommerceModule.order','Pending Setup'),
				self::FAILED_SETUP=>Yii::t('EcommerceModule.order','Failed Setup'),
			);
		if(empty($status))
			return $types;
		else
			return $types[$status];
	}

	public function getCountOrder($status=null)
	{
		$criteria=new CDbCriteria;
		if(!empty($status))
			$criteria->compare('status',$status);
		$count=self::model()->count($criteria);
		return $count;
	}

	public function createExpirationDate($id=0)
	{
		if($id==0)
			$model = $this;
		else
			$model = self::model()->findByPk($id);
		if(empty($model->expires_at) || strtotime($model->expires_at)==0)
			$now = time();
		else
			$now = strtotime($model->expires_at);
		$period = ModProduct::periods(true,$model->period,true);
		switch ($period->unit) {
            case ModProduct::UNIT_DAY:
                $shift = 'days';
                break;
            case ModProduct::UNIT_WEEK:
                $shift = 'weeks';
                break;
            case ModProduct::UNIT_MONTH:
                $shift = 'months';
                break;
            case ModProduct::UNIT_YEAR:
                $shift = 'years';
                break;
            default:
                $shift = 'years';
                break;
        }
        return date("Y-m-d H:i:s",strtotime("+$period->qty $shift", $now));
	}

	public function getInvoiceOptions($option=null)
	{
		$types = array(
				self::ISSUE_INVOICE=>Yii::t('EcommerceModule.order','Automatically issue renewal invoices'),
				self::NO_INVOICE=>Yii::t('EcommerceModule.order','Issue invoices manually'),
			);
		if(empty($status))
			return $types;
		else
			return $types[$option];
	}

	public function activate($id=0)
	{
		
		if($id==0)
			$model = $this;
		else
			$model = self::model()->findByPk($id);
		if($model->status == ModClientOrder::ACTIVE)
			return false;
		$model->status = ModClientOrder::ACTIVE;
		$model->activated_at = date(c);
		$model->expires_at = $model->createExpirationDate();
		$model->date_update = date(c);
		$model->user_update = Yii::app()->user->id;
		if($model->save())
			return true;
		else
			return false;
	}

	public function getDataProvider($data=array())
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.product_id',$data['product_id']);
		if(isset($data['status']))
			$criteria->compare('t.status',$data['status']);
		$criteria->order='t.id ASC';

		return new CActiveDataProvider(__CLASS__,
			array(
				'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>(isset($data['pageSize']))? $data['pageSize']:10,
				)
			));
	}
}		
