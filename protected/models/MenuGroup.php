<?php

/**
 * This is the model class for table "{{menu_group}}".
 *
 * The followings are the available columns in table '{{menu_group}}':
 * @property integer $id
 * @property string $nama_group
 * @property integer $status
 * @property integer $user_entry
 * @property string $date_entry
 * @property integer $user_update
 * @property string $date_update
 */
class MenuGroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{core_menu_group}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nama_group, status, user_entry, date_entry', 'required'),
			array('status, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('date_update','safe'),
			array('nama_group', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nama_group, status, user_entry, date_entry, user_update, date_update', 'safe', 'on'=>'search'),
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
			'userentry' => array(self::BELONGS_TO, 'User', 'user_entry'),
			'userupdate' => array(self::BELONGS_TO, 'User', 'user_update'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nama_group' => 'Nama Group',
			'status' => 'Status',
			'user_entry' => 'User Entry',
			'date_entry' => 'Date Entry',
			'user_update' => 'User Update',
			'date_update' => 'Date Update',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('nama_group',$this->nama_group,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('user_entry',$this->user_entry);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_update',$this->user_update);
		$criteria->compare('date_update',$this->date_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listData($title='- Pilih Group -')
	{
		$models=self::model()->findAll();
		if(!empty($title))
			$list['']=$title;
		foreach($models as $model){
			$list[$model->id]=ucfirst($model->nama_group);
		}
		return $list;
	}
}
