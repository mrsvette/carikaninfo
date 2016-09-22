<?php

/**
 * This is the model class for table "{{post_category}}".
 *
 * The followings are the available columns in table '{{post_category}}':
 * @property integer $id
 * @property string $category_name
 * @property integer $parent_id
 * @property string $notes
 */
class PostCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PostCategory the static model class
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
		return '{{core_post_category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_name, key, parent_id', 'required'),
			//array('category_name', 'match', 'pattern'=>'/^[\w\s,]+$/', 'message'=>'Category Name can only contain word characters.'),
			array('notes','safe'),
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('category_name', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category_name, key, parent_id, notes', 'safe', 'on'=>'search'),
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
			'post' => array(self::HAS_MANY, 'Post', 'post_category'),
			'post_count' => array(self::STAT, 'Post', 'post_category'),
			'parentid' => array(self::BELONGS_TO, 'PostCategory', 'parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category_name' => Yii::t('post','Category Name'),
			'parent_id' => Yii::t('post','Parent'),
			'notes' => Yii::t('post','Notes'),
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
		$criteria->compare('category_name',$this->category_name,true);
		$criteria->compare('key',$this->key);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('notes',$this->notes,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listItems($title='- Choose -',$for_parent=true)
	{
		$criteria=new CDbCriteria;
		$criteria->order='id ASC';
		$models=self::model()->findAll($criteria);
		if(!empty($title))
			$items['']=$title;
		if($for_parent)
			$items[0]='Root';
		else
			$items[0]='none';
		if(!empty($models)){
			foreach($models as $model){
				$items[$model->id]=ucfirst($model->category_name);
			}
		}
		return $items; 
	}
	
	public function getDropDownItems($parent_id = 0, $level = 0) {
		$itemsFormatted = array();
		
        $criteria=new CDbCriteria;
		$criteria->compare('parent_id',$parent_id);
		$criteria->order='id ASC';
		
		$items = self::model()->findAll($criteria);
                
		$itemsFormatted[0]='Root';
		foreach ($items as $item) {
            $itemsFormatted[$item->id] = str_repeat(' - ', $level) . ucwords($item->category_name);
            $itemsFormatted = $itemsFormatted + self::getDropDownItems($item->id, $level + 1);
        }
                
        return $itemsFormatted;
	}
	
	public function getIdByKey($key)
	{
		$models=self::model()->findAll('LOWER(t.key)=?',array($key));
		if(count($models)>1){
			$model=array();
			foreach($models as $data){
				if(self::hasChild($data->id)){
					$childs=self::model()->findAllByAttributes(array('parent_id'=>$data->id));
					foreach($childs as $child){
						array_push($model,$child->id);
					}
				}else{
					array_push($model,$data->id);
				}
			}
			return $model;
		}else{
			$models=self::model()->find('LOWER(t.key)=?',array($key));
			if(self::hasChild($models->id)){
				$childs=self::model()->findAllByAttributes(array('parent_id'=>$models->id));
				$model[0]=$models->id;
				foreach($childs as $child){
					array_push($model,$child->id);
				}
				return $model;
			}else{
				return (!empty($models->id))? (int)$models->id : 0;
			}
		}
	}
	
	public function hasChild($parent)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('parent_id',$parent);
		$count=self::model()->count($criteria);
		return ($count>0)? true : false;
	}
	
	public function getTotalCategory()
	{
		return self::model()->count();
	}

	public function parseContent($char=100)
	{
		$p = new CHtmlPurifier();
		$pecah=explode(" ",$p->purify($this->notes));
		if(count($pecah)<$char){
			array_push($pecah, '. . . '.CHtml::link('Read more',$this->url));
			$news=implode(" ",$pecah);
		}else{
			$new_arr=array_slice($pecah, 0, $char);   
			$new_arr[$char]='. . . '.CHtml::link('Read more',$this->url);
			$news=implode(" ",$new_arr);
		}
		return $news;
	}

	public function getUrl()
	{
		return Yii::app()->createUrl('post/index', array(
			'type'=>$this->key,
		));
	}
}
