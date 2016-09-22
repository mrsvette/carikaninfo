<?php

class Post extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_post':
	 * @var integer $id
	 * @var string $title
	 * @var string $content
	 * @var string $tags
	 * @var integer $status
	 * @var integer $create_time
	 * @var integer $update_time
	 * @var integer $author_id
	 */
	const STATUS_DRAFT=1;
	const STATUS_PUBLISHED=2;
	const STATUS_ARCHIVED=3;

	private $_oldTags;

	/*public $language;
	
	public function __construct()
	{
		if(Yii::app()->user->hasState('language'))
			$this->language=Yii::app()->user->getState('language')->id;
		else
			$this->language=PostLanguage::getDefault()->id;
	}*/
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
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
		return '{{core_post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'required'),
			array('status', 'in', 'range'=>array(1,2,3)),
			array('allow_comment, post_type, post_category, headline','numerical', 'integerOnly'=>true),
			array('tags', 'match', 'pattern'=>'/^[\w\s,]+$/', 'message'=>'Tags can only contain word characters.'),
			array('tags', 'normalizeTags'),
			array('layout','safe'),
			array('status', 'safe', 'on'=>'search'),
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
			'author_rel' => array(self::BELONGS_TO, 'User', 'author_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'condition'=>'comments.status='.Comment::STATUS_APPROVED, 'order'=>'comments.create_time DESC'),
			'commentCount' => array(self::STAT, 'Comment', 'post_id', 'condition'=>'status='.Comment::STATUS_APPROVED),
			'category' => array(self::BELONGS_TO, 'PostCategory', 'post_category'),
			'content_rel' => array(self::HAS_ONE, 'PostContent', 'post_id', 'condition'=>'content_rel.language="'.Yii::app()->user->getState('language')->id.'"'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'tags' => 'Tags',
			'status' => 'Status',
			'post_category'=>Yii::t('post','Category'),
			'allow_comment'=>Yii::t('post','Allow Comment'),
			'create_time' => Yii::t('post','Create Time'),
			'update_time' => Yii::t('post','Update Time'),
			'author_id' => Yii::t('post','Author'),
		);
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl()
	{
		if($this->post_type>0){
			return Yii::app()->createUrl('page/view', array(
				'slug'=>$this->content_rel->slug
			));

		}else{
			return Yii::app()->createUrl('post/view', array(
				'id'=>$this->id,
				'title'=>$this->content_rel->title,
			));
		}
	}

	/**
	 * @return array a list of links that point to the post list filtered by every tag of this post
	 */
	public function getTagLinks()
	{
		$links=array();
		foreach(Tag::string2array($this->tags) as $tag)
			$links[]=CHtml::link(CHtml::encode($tag), array('post/index', 'tag'=>$tag));
		return $links;
	}

	/**
	 * Normalizes the user-entered tags.
	 */
	public function normalizeTags($attribute,$params)
	{
		$this->tags=Tag::array2string(array_unique(Tag::string2array($this->tags)));
	}

	/**
	 * Adds a new comment to this post.
	 * This method will set status and post_id of the comment accordingly.
	 * @param Comment the comment to be added
	 * @return boolean whether the comment is saved successfully
	 */
	public function addComment($comment)
	{
		if(Yii::app()->params['commentNeedApproval'])
			$comment->status=Comment::STATUS_PENDING;
		else
			$comment->status=Comment::STATUS_APPROVED;
		$comment->post_id=$this->id;
		return $comment->save();
	}

	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();
		$this->_oldTags=$this->tags;
	}

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->create_time=$this->update_time=time();
				$this->author_id=Yii::app()->user->id;
			}
			else
				$this->update_time=time();
			return true;
		}
		else
			return false;
	}

	/**
	 * This is invoked after the record is saved.
	 */
	protected function afterSave()
	{
		parent::afterSave();
		Tag::model()->updateFrequency($this->_oldTags, $this->tags);
	}

	/**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete()
	{
		parent::afterDelete();
		Comment::model()->deleteAll('post_id='.$this->id);
		Tag::model()->updateFrequency($this->tags, '');
	}

	/**
	 * Retrieves the list of posts based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('content_rel.title',$this->content_rel->title,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('post_type',$this->post_type);
		$criteria->compare('post_category',$this->post_category);

		return new CActiveDataProvider('Post', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'status, update_time DESC',
			),
			'pagination'=>array('pageSize'=>20),
		));
	}
	
	//short content to be listed
	public function parseContent($char=100)
	{
		$p = new CHtmlPurifier();
		$pecah=explode(" ",$p->purify($this->content_rel->content));
		if(count($pecah)<$char){
			array_push($pecah, '. . . '.CHtml::link(Yii::t('post','Read more'),$this->url));
			$news=implode(" ",$pecah);
		}else{
			$new_arr=array_slice($pecah, 0, $char);   
			$new_arr[$char]='. . . '.CHtml::link(Yii::t('post','Read more'),$this->url);
			$news=implode(" ",$new_arr);
		}
		return $news;
	}

	public function parseContent2($char=100,$include_readmore=true)
	{
		$p = new CHtmlPurifier();
		$pecah=explode(" ",strip_tags($p->purify($this->content_rel->content)));
		if(count($pecah)<$char){
			if($include_readmore)
				array_push($pecah, '. . . '.CHtml::link(Yii::t('post','Read more'),$this->url));
			else
				array_push($pecah, '. . .');
			$news=implode(" ",$pecah);
		}else{
			$new_arr=array_slice($pecah, 0, $char); 
			if($include_readmore)  
				$new_arr[$char]='. . . '.CHtml::link(Yii::t('post','Read more'),$this->url);
			else
				$new_arr[$char]='. . .';
			$news=implode(" ",$new_arr);
		}
		return $news;
	}

	public function listItems($title='- Choose Page-')
	{
		$criteria=new CDbCriteria;
		$criteria->compare('post_type',1);
		$criteria->compare('status',2);
		$criteria->order='id ASC';
		if(!empty($title))
			$list['']=$title;
			
		$models=self::model()->findAll($criteria);
		if(!empty($models)){
			foreach($models as $model){
				$list[$model->id]=ucfirst($model->content_rel->title);
			}
		}
		return $list;
	}
	
	public function getPage($page_id)
	{
		$model=self::model()->findByPk((int)$page_id);
		$url=Yii::app()->createUrl('page/view', array(
			'slug'=>$model->content_rel->slug,
		));
		if(!empty($url)){
			$pecah=explode("/",$url);
			return '/'.$pecah[count($pecah)-1];
		}
			return '/site';
		/*$url=Yii::app()->createUrl('page/view', array(
			'id'=>$model->id,
			'title'=>$model->content_rel->title,
		));
		
		return strstr($url,"page");*/
	}
	
	public function getTotalPost($post_type=0)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('post_type',$post_type);
		return self::model()->count($criteria);
	}

	public function indonesianDay($n)
	{
		$days=array(1=>'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu');
		return $days[$n];
	}

	public function getLatestPost($limit=4)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('post_type',0);
		$criteria->order='create_time DESC';
		$criteria->limit=$limit;

		$models=self::model()->findAll($criteria);

		$items=array();
		foreach($models as $model){
			$items[]=array(
					'id'=>$model->id,
					'title'=>CHtml::link($model->content_rel->title,$model->url),
					'content'=>$model->parseContent(10),
					'author'=>$model->author_rel->username,
					'date'=>self::indonesianDay(date("N",$model->create_time)).', '.date("d/m/Y H:i",$model->create_time),
				);
		}
		return $items;
	}

	public function findReplace($pattern,$string,$length=300)
	{
		$pattern=strtolower($pattern);
		$string=strtolower($string);
		if(!empty($string))
			$ereg=ereg_replace($pattern,'<u>'.$pattern.'</u>',$string);
		else
			return null;
		$stringDisplay = substr($ereg, 0, $length); 
		if(strlen($ereg)>$length) 
			$stringDisplay .= ' ...'; 
		return $stringDisplay;
	}

	public function getImage($id=0)
	{
		if($id>0)
			$model=self::model()->findByPk($id);
		else
			$model=$this;
		$matches=array();
		if(!empty($model->content_rel->content))
			preg_match_all('/(<img [^>]*>)/',$model->content_rel->content, $matches, PREG_PATTERN_ORDER);
		$img=$matches[0][0];
		return $img;
	}

	public function getAuthor($id=0)
	{
		if($id>0)
			$model=self::model()->findByPk($id);
		else
			$model=$this;
		return $model->author_rel->username;
	}

	public function getTotalComment($id=0)
	{
		if($id>0)
			return self::model()->findByPk($id)->commentCount;
		else{
			$criteria=new CDbCriteria;
			$criteria->compare('post_id','>0');
			return Comment::model()->count($criteria);
		}
	}

	public function getContentPage($title='dashboard')
	{
		$criteria=new CDbCriteria;
		$criteria->compare('LOWER(slug)',strtolower($title));
		$criteria->with=array('content_rel');
		$model=self::model()->find($criteria);
		return (empty($model->id))? false : $model->content_rel->content;
	}

	public function createSlug($title=null)
	{
		if(empty($title))
			$title=$this->content_rel->title;
		/*$string=preg_replace("/\s+/",'z0',strtolower($title));
		$string=preg_replace("/[^[:alnum:]]/",'',$string);
		$string=preg_replace("/z0z0/",'z0',$string);
		$string=preg_replace("/z0/",'-',$string);*/
        $title = preg_replace('/[^a-z0-9-]/', '-', strtolower($title));
        $title = preg_replace('/-+/', "-", $title);
        $title = trim($title, '-');
		return $title;
	}
	
	/** this function create by boxbilling */
	public static function slug($str)
    {
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        $str = trim($str, '-');
        return $str;
    }

	public function getContent($lang=1,$id=0)
	{
		$criteria=new CDbCriteria;
		if($id==0)
			$criteria->compare('post_id',$this->id);
		else
			$criteria->compare('post_id',$id);
		$criteria->compare('language',$lang);
		$model=PostContent::model()->find($criteria);
		return $model;
	}

	public function getLayoutItems($title='- Pilih Layout -',$basename = '.php')
	{
		//$columns=glob('themes/'.Yii::app()->theme->name.'/views/layouts/column*'.$basename);
		//bugs found 1 apr 16, Yii::app()->theme->name not right value on appadmin
		$columns = glob('themes/'.Yii::app()->config->get('theme').'/views/layouts/column*'.$basename);
		
		$items=array();
		if(!empty($title))
			$items['']=$title;
		if(is_array($columns)){
			foreach($columns as $index=>$column){
				$name=basename($column,$basename);
				$items[$name]=$name;
			}
		}
		return $items;
	}

	public function getSiteMap()
	{
		//search for post
		$sql="SELECT t.id, c.title, c.slug FROM `app_post` t JOIN `app_post_content` c ON c.post_id=t.id WHERE t.post_type=:post_type";
		$command=Yii::app()->db->createCommand($sql);
		$command->params=array('post_type'=>0);
		$post=$command->queryAll();
		//search for page
		$sql2="SELECT t.id, c.title, c.slug FROM `app_post` t JOIN `app_post_content` c ON c.post_id=t.id WHERE t.post_type=:post_type";
		$command2=Yii::app()->db->createCommand($sql2);
		$command2->params=array('post_type'=>1);
		$page=$command2->queryAll();
		//search for categoriew
		$sql3="SELECT t.id, t.category_name AS title, t.key AS slug FROM `app_post_category` t ORDER BY t.category_name ASC";
		$command3=Yii::app()->db->createCommand($sql3);
		$category=$command3->queryAll();
		$items=array(
				'post'=>$post,
				'page'=>$page,
				'category'=>$category
			);
		return $items;
	}
}
