<?php

/**
 * This is the model class for table "{{mod_portfolio_content}}".
 *
 * The followings are the available columns in table '{{mod_portfolio_content}}':
 * @property integer $id
 * @property integer $portfolio_id
 * @property string $title
 * @property string $content
 * @property integer $language
 * @property integer $viewed
 * @property string $slug
 * @property string $meta_keywords
 * @property string $meta_description
 */
class ModPortfolioContent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_portfolio_content}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('portfolio_id, title, meta_description, content', 'required'),
			array('portfolio_id, language, viewed', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>128),
			array('slug', 'length', 'max'=>256),
			array('meta_keywords, meta_description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, portfolio_id, title, content, language, viewed, slug, meta_keywords, meta_description', 'safe', 'on'=>'search'),
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
			'portfolio_id' => Yii::t('PortfolioModule.portfolio','Portfolio'),
			'title' => Yii::t('PortfolioModule.portfolio','Title'),
			'content' => Yii::t('PortfolioModule.portfolio','Description'),
			'language' => Yii::t('PortfolioModule.portfolio','Language'),
			'viewed' => Yii::t('PortfolioModule.portfolio','Viewed'),
			'slug' => Yii::t('PortfolioModule.portfolio','Slug'),
			'meta_keywords' => Yii::t('PortfolioModule.portfolio','Meta Keywords'),
			'meta_description' => Yii::t('PortfolioModule.portfolio','Short Description'),
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('language',$this->language);
		$criteria->compare('viewed',$this->viewed);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('meta_keywords',$this->meta_keywords,true);
		$criteria->compare('meta_description',$this->meta_description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModPortfolioContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getValue($attr,$post_id,$lang=1)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('t.portfolio_id',$post_id);
		$criteria->compare('t.language',$lang);
		$model = self::model()->find($criteria);
		
		return $model->$attr;
	}
}
