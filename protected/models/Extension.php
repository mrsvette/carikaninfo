<?php

/**
 * This is the model class for table "{{core_extension}}".
 *
 * The followings are the available columns in table '{{core_extension}}':
 * @property string $id
 * @property string $name
 * @property string $status
 * @property string $manifest
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class Extension extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{core_extension}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_entry, user_entry', 'required'),
			array('user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('status', 'length', 'max'=>100),
			array('manifest, date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, status, manifest, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'name' => Yii::t('AppadminModule.extension','Name'),
			'status' => Yii::t('AppadminModule.extension','Status'),
			'manifest' => Yii::t('AppadminModule.extension','Manifest'),
			'date_entry' => Yii::t('global','Date Entry'),
			'user_entry' => Yii::t('global','User Entry'),
			'date_update' => Yii::t('global','Date Update'),
			'user_update' => Yii::t('global','User Update'),
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('manifest',$this->manifest,true);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('user_entry',$this->user_entry);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('user_update',$this->user_update);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Extension the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function createImage($file)
    {
		if(@file_exists($file)) {
			$path_parts = @pathinfo($file);
			$filename = basename($file);
			$file_extension = strtolower(substr(strrchr($filename,"."),1));
			switch( $file_extension ) {
				case "gif": $ctype="image/gif"; $im = imagecreatefromgif($file); header('Content-Type: '.$ctype); imagegif($im);break;
				case "png": $ctype="image/png"; $im = imagecreatefrompng($file); header('Content-Type: '.$ctype); imagepng($im);break;
				case "jpeg":
				case "jpg": $ctype="image/jpg"; $im = imagecreatefromjpeg($file); header('Content-Type: '.$ctype); imagejpeg($im);break;
			}
			imagedestroy($im);
		}
    }

	public function getIsInstalled($data)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.name',$data['id']);
		$model=self::model()->find($criteria);
		if($model instanceof Extension){
			return ($model->status=='installed')? true : false;
		}else
			return false;
	}

	public function getActionButton($data)
	{
		$installed = self::getIsInstalled($data);
		if($installed){
			$button = '<a class="btnIconLeft" id="btn-action" href="'.Yii::app()->createUrl('/appadmin/extensions/uninstall').'" attr-id="'.$data['id'].'">
			<i class="fa fa-times icon"></i> <span>'.Yii::t('AppadminModule.extension','Deactivate').'</span>
			</a>';
			if(isset($data['config'])){
				$button .= '<a class="btnIconLeft" href="'.Yii::app()->createUrl('/appadmin/extensions/setting',array('id'=>$data['id'])).'" style="margin-left:5px;" target="_newtab">
				<i class="fa fa-pencil icon"></i> <span>'.Yii::t('AppadminModule.extension','Setting').'</span>
				</a>';
			}
		}else{
			$button = '<a class="btnIconLeft" id="btn-action" href="'.Yii::app()->createUrl('/appadmin/extensions/install').'" attr-id="'.$data['id'].'">
			<i class="fa fa-cog icon"></i> <span>'.Yii::t('AppadminModule.extension','Activate').'</span>
			</a>';
		}
		return $button;
	}

	public function getConfigs($id=0)
	{
		if($id==0)
			$model = $this;
		else
			$model = self::model()->findByPk($id);
		$manifest = CJSON::decode($model->manifest,true);
		if(!empty($manifest['config'])){
			foreach($manifest['config'] as $cid=>$config){
				$criteria = new CDbCriteria;
				$criteria->compare('t.key',$config['name']);
				$model2 = Params::model()->find($criteria);
				if($model2 instanceof Params){
					$manifest['config'][$cid]['value'] = unserialize($model2->value);
				}
			}
			return $manifest['config'];
		}
		return false;
	}

	public function saveConfig($data)
	{
		$module = $data['module'];
		foreach($data as $attr=>$val){
			if(!in_array($attr,array('module','yt0'))){
				$criteria=new CDbCriteria;
				$criteria->compare('t.key',$attr);
				$model = Params::model()->find($criteria);
				if(!$model instanceof Params)
					$model = new Params('create');
				$pecah=explode("_", $attr);
				$model->params_name = ucfirst(implode(" ",$pecah));
				if($model->isNewRecord){
					$model->module = $module;
					$model->key = $attr;
					$model->value = serialize($val);
					$model->type = (is_int($val))? 2 : 1;
					$model->date_entry = date(c);
					$model->user_entry = Yii::app()->user->id;
				}else{
					$model->value = serialize($val);
					$model->date_update = date(c);
					$model->user_update = Yii::app()->user->id;
				}
				$model->save();
			}
		}
		return true;
	}

	public function getConfigByModule($module,$key)
	{
		if(Yii::app()->config->get($key))
			return Yii::app()->config->get($key);
		$criteria = new CDbCriteria;
		$criteria->compare('t.key',$key);
		$criteria->compare('t.module',$module);
		$model = Params::model()->find($criteria);
		if($model instanceof Params){
			return unserialize($model->value);
		}else{
			$criteria1 = new CDbCriteria;
			$criteria1->compare('t.name',$module);
			$model = self::model()->find($criteria1);
			$manifest = CJSON::decode($model->manifest,true);
			$val = null;
			if(!empty($manifest['config'])){
				foreach($manifest['config'] as $cid=>$config){
					if($config['name']==$key)
						$val = $config['value'];
				}
			}
			return $val;
		}
	}

	public function getConfigsByModule($module)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('t.module',$module);
		$models = Params::model()->findAll($criteria);
		$items = array();
		foreach($models as $model){
			$items[$model->key] = unserialize($model->value);
		}
		return $items;
	}
}
