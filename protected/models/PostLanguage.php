<?php

/**
 * This is the model class for table "{{post_language}}".
 *
 * The followings are the available columns in table '{{post_language}}':
 * @property integer $id
 * @property string $name
 * @property string $code
 */
class PostLanguage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{core_post_language}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, code', 'required'),
			array('name', 'length', 'max'=>32),
			array('code', 'length', 'max'=>3),
			array('default', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, code, default', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'default' => 'Default',
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
		$criteria->compare('code',$this->code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PostLanguage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function items($title='- Pilih -')
	{
		$criteria=new CDbCriteria;
		$criteria->order='id ASC';
		$models=self::model()->findAll($criteria);
		if(!empty($title))
			$items['']=$title;
		if(!empty($models)){
			foreach($models as $model){
				$items[$model->id]=$model->name;
			}
		}
		return $items; 
	}

	public function getDefault()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.default',1);
		$model=self::model()->find($criteria);
		return $model;
	}
}
