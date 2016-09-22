<?php

/**
 * This is the model class for table "{{menu}}".
 *
 * The followings are the available columns in table '{{menu}}':
 * @property integer $id
 * @property string $nama_menu
 * @property string $keterangan
 * @property integer $level_id
 * @property integer $parent_id
 * @property integer $urutan
 * @property string $link_action
 * @property integer $notaktif
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class Menu extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Menu the static model class
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
		return '{{core_menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('level_id', 'required'),
			array('parent_id','check_parent'), //pastikan parentnya bkn dirinya sendiri
			array('urutan, group_id, date_entry, user_entry', 'required'),
			array('key, date_update, user_update', 'safe'),
			array('level_id, parent_id, urutan, notaktif, group_id, role, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, level_id, parent_id, urutan, notaktif, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'parentid' => array(self::BELONGS_TO, 'Menu', 'parent_id'),
			'group' => array(self::BELONGS_TO, 'MenuGroup', 'group_id'),
			'userentry' => array(self::BELONGS_TO, 'User', 'user_entry'),
			'userupdate' => array(self::BELONGS_TO, 'User', 'user_update'),
			'content_rel' => array(self::HAS_ONE, 'MenuContent', 'menu_id', 'condition'=>'content_rel.language="'.Yii::app()->user->getState('language')->id.'"'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'level_id' => 'Level',
			'parent_id' => 'Parent',
			'urutan' => 'Urutan',
			'notaktif' => 'Status Aktif',
			'group_id' => 'Group',
			'role' => 'Visibility',
			'date_entry' => 'Date Entry',
			'user_entry' => 'User Entry',
			'date_update' => 'Date Update',
			'user_update' => 'User Update',
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
		$criteria->compare('level_id',$this->level_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('urutan',$this->urutan);
		$criteria->compare('notaktif',$this->notaktif);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_entry',$this->user_entry);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('user_update',$this->user_update);
		$criteria->order='group_id, urutan ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function check_parent($attribute,$params)
	{
		if($this->parent_id==$this->id)
			$this->addError('parent_id','Parent is invalid.');
	}
	
	public function item($id)
	{
		return self::model()->findByPk((int)$id);
	}
	
	public function listData($title='Root')
	{
		$models=self::model()->findAll();
		if(!empty($title))
			$list[0]=$title;
		foreach($models as $model){
			$list[$model->id]=ucfirst($model->content_rel->nama_menu);
		}
		return $list;
	}
	
	public function listLevel()
	{
		$models=self::model()->findAll();
		foreach($models as $model){
			$list[$model->level_id]=$model->level_id;
		}
		return $list;
	}
	
	public function getVisibility()
	{
		if($this->role==1) //just for guest
			$visible=Yii::app()->member->isGuest;
		elseif($this->role==2) //just member
			$visible=!Yii::app()->member->isGuest;
		else //all user
			$visible=true;
		return $visible;
	}

	//for mb-menu
	public function items($group,$level_from=1,$linkOptions=array())
	{
		$criteria=new CDbCriteria;
		$criteria->compare('notaktif',0);
		$criteria->compare('group.key',$group);
		$criteria->order='urutan ASC';
		$criteria->with=array('group');
		
		$models=self::model()->findAll($criteria);
		$list=array();
		foreach($models as $model){
			if(self::issetChild($model->id))
				$list[$model->level_id][$model->id]=array('label'=>Yii::t('menu',$model->content_rel->ionTitle), 'url'=>'#', 'items'=>self::child($model->id),'visible'=>$model->getVisibility(),'linkOptions'=>$linkOptions);
			else{
				if($model->content_rel->link_action=='#')
					$list[$model->level_id][$model->id]=array('label'=>Yii::t('menu',$model->content_rel->ionTitle), 'url'=>array('/#'),'visible'=>$model->getVisibility(),'linkOptions'=>$linkOptions);
				else
					$list[$model->level_id][$model->id]=array('label'=>Yii::t('menu',$model->content_rel->ionTitle), 'url'=>self::getLinkAction($model),'visible'=>$model->getVisibility(),'linkOptions'=>$linkOptions);
			}
		}
		return $list[$level_from];
	}
	
	public function child($menu_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('notaktif',0);
		$criteria->compare('parent_id',$menu_id);
		$criteria->order='urutan ASC';
		
		$models=self::model()->findAll($criteria);
		foreach($models as $model){
			if(self::issetChild($model->id))
				$items[]=array('label'=>Yii::t('menu',$model->content_rel->ionTitle), 'items'=>self::child($model->id),'visible'=>$model->getVisibility());
			else{
				if($model->content_rel->link_action=='#')
					$items[]=array('label'=>Yii::t('menu',$model->content_rel->ionTitle), 'url'=>array('/#'),'visible'=>$model->getVisibility());
				else
					$items[]=array('label'=>Yii::t('menu',$model->content_rel->ionTitle), 'url'=>self::getLinkAction($model),'visible'=>$model->getVisibility());
			}	
		}
		return $items;
	}
	
	public function issetChild($menu_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('notaktif',0);
		$criteria->compare('parent_id',$menu_id);
		
		$count=self::model()->count($criteria);
		return ($count>0)? true : false;
	}
	
	public function getDropdownItems($parentId = 0, $level = 0) {
		$itemsFormatted = array();
		
        $criteria=new CDbCriteria;
		$criteria->compare('notaktif',0);
		$criteria->compare('parent_id',$parentId);
		$criteria->order='urutan ASC';
		
		$items = self::model()->findAll($criteria);
                
        $itemsFormatted[0]='Root';
		foreach ($items as $item) {
            $itemsFormatted[$item->id] = str_repeat(' - ', $level) . ucwords($item->content_rel->nama_menu);
            $itemsFormatted = $itemsFormatted + self::getDropdownItems($item->id, $level + 1);
        }
                
        return $itemsFormatted;
	}
	
	//link to
	public function listLinkType($title='- Choose -')
	{
		if(!empty($title))
			$data=array(''=>$title,1=>'Custom Link',2=>'Pages');
		else
			$data=array(1=>'Custom Link',2=>'Pages');
		
		return $data;
	}
	
	//for sitemap
	public function getSiteMapItems($datas) {
		if(is_array($datas)){
			foreach($datas as $index=>$data){
				echo '<li>'.CHtml::link(strtolower($data['label']),$data['url']);
				if(!empty($data['items'])){
					echo '<ul style="list-style-type: square">';
						self::getSiteMapItems($data['items']);
					echo '</ul>';
				}
				echo '</li>';
			}
		}else{
			echo '<i>Data is empty</i>';
		}
	}

	public function getUrutan($parent_id,$group_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('parent_id',$parent_id);
		$criteria->compare('group_id',$group_id);
		$criteria->order='urutan DESC';
		$criteria->limit=1;
		return self::model()->find($criteria)->urutan + 1;
	}

	public function getContent($lang=1,$id=0)
	{
		$criteria=new CDbCriteria;
		if($id==0)
			$criteria->compare('menu_id',$this->id);
		else
			$criteria->compare('menu_id',$id);
		$criteria->compare('language',$lang);
		$model=MenuContent::model()->find($criteria);
		return $model;
	}

	public function getLinkAction($model)
	{
		if($model->content_rel->rel_id>0)
			return Post::model()->findByPk($model->content_rel->rel_id)->url;
		else
			return array(CHtml::normalizeUrl($model->content_rel->link_action));
	}

	public function getNestableMenu($group='main_menu',$level_from=1)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('notaktif',0);
		$criteria->compare('group.key',$group);
		$criteria->order='urutan ASC';
		$criteria->with=array('group');
		
		$models=self::model()->findAll($criteria);
		$list='<ol class="dd-list">';
		foreach($models as $model){
			if($model->level_id==$level_from){
				$list.='<li class="dd-item dd3-item" data-id="'.$model->id.'">
						<div class="dd-handle dd3-handle">&nbsp;</div>
						<div class="dd3-content">'.Yii::t('menu',$model->content_rel->ionTitle).'
						<div class="pull-right">
							<a href="'.Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/menu/update',array('id'=>$model->id)).'" id="'.$model->id.'" class="update"><span class="fa fa-pencil" title="Update"></span></a> 
							<a href="'.Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/menu/delete',array('id'=>$model->id)).'" id="'.$model->id.'" class="delete"><span class="fa fa-trash-o" title="Delete"></span></a>
						</div></div>';
					if(self::issetChild($model->id)){
						$list.=self::nestable_child($model->id);
					}
				$list.='</li>';
			}
		}
		$list.='</ol>';
		return $list;
	}
	
	public function nestable_child($menu_id)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('notaktif',0);
		$criteria->compare('parent_id',$menu_id);
		$criteria->order='urutan ASC';
		
		$models=self::model()->findAll($criteria);
		$list='<ol class="dd-list">';
		foreach($models as $model){
			$list.='<li class="dd-item dd3-item" data-id="'.$model->id.'">
					<div class="dd-handle dd3-handle">&nbsp;</div>
					<div class="dd3-content">'.Yii::t('menu',$model->content_rel->ionTitle).'
					<div class="pull-right">
						<a href="'.Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/menu/update',array('id'=>$model->id)).'" id="'.$model->id.'" class="update"><span class="fa fa-pencil" title="Update"></span></a> 
						<a href="'.Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/menu/delete',array('id'=>$model->id)).'" id="'.$model->id.'" class="delete"><span class="fa fa-trash-o" title="Delete"></span></a>
					</div></div>';
				if(self::issetChild($model->id)){
					$list.=self::nestable_child($model->id);
				}
			$list.='</li>';	
		}
		$list.='</ol>';
		return $list;
	}

	public function reorderdata($data,$level_id =1,$parent_id =0)
	{
		if(!is_array($data))
			return false;
		$items = array();
		foreach($data as $i=>$menu){
			$urutan = $i+1; $id = $menu['id'];
			$items[$id] = array(
				'id'=>$id,
				'urutan'=>$urutan,
				'level_id'=>$level_id,
				'parent_id'=>$parent_id,
				'has_child'=>isset($menu['children'])
			);
			if(isset($menu['children'])){
				$level = $level_id + 1;
				$child = self::reorderdata($menu['children'],$level,$id);
				if(is_array($child))
					$items = $items+$child;
			}
		}
		return $items;
	}
}
