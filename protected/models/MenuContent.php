<?php

/**
 * This is the model class for table "{{menu_content}}".
 *
 * The followings are the available columns in table '{{menu_content}}':
 * @property integer $id
 * @property integer $menu_id
 * @property string $nama_menu
 * @property string $keterangan
 * @property string $link_action
 * @property integer $language
 */
class MenuContent extends CActiveRecord
{
	public $link_type;
	public $page;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{core_menu_content}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('menu_id, nama_menu, link_action', 'required'),
			array('menu_id, rel_id, language', 'numerical', 'integerOnly'=>true),
			array('nama_menu', 'length', 'max'=>128),
			array('keterangan, link_action', 'length', 'max'=>255),
			array('icon_fa, link_type, page','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, menu_id, nama_menu, keterangan, link_action, language', 'safe', 'on'=>'search'),
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
			'menu_id' => 'Menu',
			'nama_menu' => 'Nama Menu',
			'keterangan' => 'Keterangan',
			'link_action' => 'Link Action',
			'language' => 'Language',
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
		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('nama_menu',$this->nama_menu,true);
		$criteria->compare('keterangan',$this->keterangan,true);
		$criteria->compare('link_action',$this->link_action,true);
		$criteria->compare('language',$this->language);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MenuContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getValue($attr,$menu_id,$lang=1)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.menu_id',$menu_id);
		$criteria->compare('t.language',$lang);
		$model=self::model()->find($criteria);
		
		return $model->$attr;
	}

	public function getIonTitle($id=0,$with_span=true)
	{
		if($id>0)
			$model = self::model()->findByPk($id);
		else
			$model = $this;
		if(!empty($model->icon_fa)){
			if($with_span)
				return '<span><i class="fa '.$model->icon_fa.'"></i></span> '.$model->nama_menu;
			else
				return '<i class="fa '.$model->icon_fa.'"></i> '.$model->nama_menu;
		}
		return $model->nama_menu;
	}

	public function getIon($id=0)
	{
		if($id>0)
			$model = self::model()->findByPk($id);
		else
			$model = $this;
		if(!empty($model->icon_fa)){
			return '<i class="fa '.$model->icon_fa.'"></i> ';
		}
		return false;
	}
}
