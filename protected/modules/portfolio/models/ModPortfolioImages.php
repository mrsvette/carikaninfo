<?php

/**
 * This is the model class for table "{{mod_portfolio_images}}".
 *
 * The followings are the available columns in table '{{mod_portfolio_images}}':
 * @property integer $id
 * @property integer $portfolio_id
 * @property string $image
 * @property string $thumb
 * @property string $src
 * @property string $date_entry
 * @property integer $user_entry
 */
class ModPortfolioImages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_portfolio_images}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('portfolio_id, image, thumb, src', 'required'),
			array('portfolio_id, user_entry', 'numerical', 'integerOnly'=>true),
			array('image', 'length', 'max'=>128),
			array('thumb, src', 'length', 'max'=>256),
			array('date_entry', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, portfolio_id, image, thumb, src, date_entry, user_entry', 'safe', 'on'=>'search'),
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
			'portfolio_rel'=>array(self::BELONGS_TO,'ModPortfolio','portfolio_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'portfolio_id' => 'Portfolio',
			'image' => 'Image',
			'thumb' => 'Thumb',
			'src' => 'Src',
			'date_entry' => 'Date Entry',
			'user_entry' => 'User Entry',
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
		$criteria->compare('portfolio_id',$this->portfolio_id);
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
	 * @return ModPortfolioImages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getImg($id=0)
	{
		if($id==0)
			$model = $this;
		else
			$model = self::model()->findByPk($id);
		
		return CHtml::image(Yii::app()->request->baseUrl.'/'.$model->src.''.$model->image,$model->id,array('class'=>'img-responsive'));
	}
}
