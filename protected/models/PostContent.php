<?php

/**
 * This is the model class for table "{{post_content}}".
 *
 * The followings are the available columns in table '{{post_content}}':
 * @property integer $id
 * @property integer $post_id
 * @property string $title
 * @property string $content
 * @property string $language
 * @property integer $viewed
 * @property string $slug
 */
class PostContent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{core_post_content}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('post_id, title, content', 'required'),
			array('post_id, language, viewed', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>128),
			array('slug', 'length', 'max'=>256),
			array('meta_keywords, meta_description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, post_id, title, content, language, viewed, slug', 'safe', 'on'=>'search'),
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
			'post_rel'=>array(self::BELONGS_TO,'Post','post_id'),
			'language_rel'=>array(self::BELONGS_TO,'PostLanguage','language')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post_id' => 'Post',
			'title' => Yii::t('post','Title'),
			'content' => Yii::t('post','Content'),
			'language' => 'Language',
			'viewed' => 'Viewed',
			'slug' => 'Seo Url',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.post_id',$this->post_id);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.content',$this->content,true);
		$criteria->compare('t.language',$this->language,true);
		$criteria->compare('t.viewed',$this->viewed);
		$criteria->compare('t.slug',$this->slug,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PostContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getValue($attr,$post_id,$lang=1)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.post_id',$post_id);
		$criteria->compare('t.language',$lang);
		$model=self::model()->find($criteria);
		
		return $model->$attr;
	}
}
