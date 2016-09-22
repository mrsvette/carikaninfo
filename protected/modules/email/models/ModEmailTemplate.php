<?php

/**
 * This is the model class for table "{{mod_email_template}}".
 *
 * The followings are the available columns in table '{{mod_email_template}}':
 * @property string $id
 * @property string $action_code
 * @property string $category
 * @property integer $enabled
 * @property string $subject
 * @property string $content
 * @property string $description
 * @property string $vars
 * @property string $date_entry
 * @property integer $user_entry
 * @property string $date_update
 * @property integer $user_update
 */
class ModEmailTemplate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_email_template}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('action_code, subject, content, date_entry', 'required'),
			array('enabled, user_entry, user_update', 'numerical', 'integerOnly'=>true),
			array('action_code, subject', 'length', 'max'=>255),
			array('category', 'length', 'max'=>30),
			array('content, description, vars, date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, action_code, category, enabled, subject, content, description, vars, date_entry, user_entry, date_update, user_update', 'safe', 'on'=>'search'),
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
			'action_code' => 'Action Code',
			'category' => 'Category',
			'enabled' => 'Enabled',
			'subject' => 'Subject',
			'content' => 'Content',
			'description' => 'Description',
			'vars' => 'Vars',
			'date_entry' => 'Date Entry',
			'user_entry' => 'User Entry',
			'date_update' => 'Date Update',
			'user_update' => 'User Update',
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
		$criteria->compare('action_code',$this->action_code,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('vars',$this->vars,true);
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
	 * @return ModEmailTemplate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function encrypt($text, $pass = '')
    {
        if (!extension_loaded('mcrypt')) {
            throw new Exception('php mcrypt extension must be enabled on your server');
        }
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $pass, $text, MCRYPT_MODE_ECB, $iv));
    }

    public static function decrypt($text, $pass = '')
    {
        if (!extension_loaded('mcrypt')) {
            throw new Exception('php mcrypt extension must be enabled on your server');
        }
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $pass, base64_decode($text), MCRYPT_MODE_ECB, $iv));
    }

	public function setVars($model, $vars)
    {
        $model->vars = self::encrypt(json_encode($vars), 'v8JoWZph12DYSY4aq8zpvWdzC');
		$model->update('vars');
        return true;
    }
    
    public function getVars($model)
    {
        $json = self::decrypt($model->vars, 'v8JoWZph12DYSY4aq8zpvWdzC');
        return CJSON::decode($json);
    }

    public function template_send($data)
    {
        if(!isset($data['code'])) {
            throw new CHttpException(404,'Template code not passed');
        }
        
        if(!isset($data['to']) && !isset($data['to_staff']) && !isset($data['to_client'])) {
            throw CHttpException(404,'Receiver is not defined. Define to or to_client or to_staff parameter');
        }
        
        $vars = $data;
        //unset($vars['to'], $vars['to_client'], $vars['to_staff'], $vars['to_name'], $vars['from'], $vars['from_name']);
        //unset($vars['default_description'], $vars['default_subject'], $vars['default_template'], $vars['code']);

        //add aditional variables to template
        if(isset($data['to_staff']) && $data['to_staff']) {
            $staff = CHtml::listData(User::model()->findAll(), 'id', 'email');
			$vars['staff'] = $staff;
        }
        
        //add aditional variables to template
        if(isset($data['to_client']) && $data['to_client'] > 0) {
            $customer = ModClient::model()->findByPk($data['to_client']);
            $vars['c'] = $customer;
        }
        
		$vars['css'] = self::get_css(array('path'=>'/css/'.Yii::app()->theme->name.'/css/mail.css'));
 
		$criteria = new CDbCriteria;
		$criteria->compare('action_code',$data['code']);
		$t = self::model()->find($criteria);
		$this->setVars($t, $vars);
        
        // do not send inactive template
        if(!$t->enabled) {
            return false;
        }

        list($subject, $content) = $this->_parse($t, $vars);
		
        $from = isset($data['from']) ? $data['from'] : Yii::app()->config->get('admin_email');
        $from_name = isset($data['from_name']) ? $data['from_name'] : Yii::app()->config->get('site_name');
        $sent = false;
        if(isset($staff)) {
            foreach($staff as $sid=>$staff) {
                $to = $staff['email'];
                $to_name = User::model()->findByPk($sid)->username;
                $sent = $this->_send($to, $from, $subject, $content, $to_name, $from_name, null, $sid);
            }
        } else if(isset($customer)) {
            $to = $customer->email;
            $to_name = $customer->first_name . ' ' . $customer->last_name;
            $sent = $this->_send($to, $from, $subject, $content, $to_name, $from_name, $customer->id);
        } else {
            $to = $data['to'];
            $to_name = isset($data['to_name']) ? $data['to_name'] : null;
            $sent = $this->_send($to, $from, $subject, $content, $to_name, $from_name);
        }
        
        return $sent;
    }

	public function template_preview($data)
    {
        if(!isset($data['code'])) {
            throw new CHttpException(404,'Template code not passed');
        }
        
        if(!isset($data['to']) && !isset($data['to_staff']) && !isset($data['to_client'])) {
            throw CHttpException(404,'Receiver is not defined. Define to or to_client or to_staff parameter');
        }
        
        $vars = $data;
        //unset($vars['to'], $vars['to_client'], $vars['to_staff'], $vars['to_name'], $vars['from'], $vars['from_name']);
        //unset($vars['default_description'], $vars['default_subject'], $vars['default_template'], $vars['code']);

        //add aditional variables to template
        if(isset($data['to_staff']) && $data['to_staff']) {
            $staff = CHtml::listData(User::model()->findAll(), 'id', 'email');
			$vars['staff'] = $staff;
        }
        
        //add aditional variables to template
        if(isset($data['to_client']) && $data['to_client'] > 0) {
            $customer = ModClient::model()->findByPk($data['to_client']);
            $vars['c'] = $customer;
        }
        
		$vars['css'] = self::get_css(array('path'=>'/css/'.Yii::app()->theme->name.'/css/mail.css'));
 
		$criteria = new CDbCriteria;
		$criteria->compare('action_code',$data['code']);
		$t = self::model()->find($criteria);
        
        // do not send inactive template
        if(!$t->enabled) {
            return false;
        }
        list($subject, $content) = $this->_parse($t, $vars);
		return $content;
    }
    
    public function _parse($t, $vars)
    {
        $dd = $vars;
        $dd['_tpl'] = $t->content;
        $pc = $this->string_render($dd);
        
        $dd = $vars;
        $dd['_tpl'] = $t->subject;
        $ps = $this->string_render($dd);
        
        return array($ps, $pc);
    }

	public function string_render($data)
    {
        if(!isset($data['_tpl'])) {
            return '';
        }
        
        $tpl = $data['_tpl'];
        $try_render = isset($data['_try']) ? $data['_try'] : false;
        
        $vars = $data;
        unset($vars['_tpl'], $vars['_try']);

		$twig = Yii::app()->viewRenderer;
       	return $twig->renderText($tpl,$data);
    }
    
    private function _send($to, $from, $subject, $content, $to_name = null, $from_name = null, $client_id = null, $admin_id = null)
    {
		$settings = Extension::getConfigsByModule('email');
		
        if(isset($settings['log_enabled']) && $settings['log_enabled']) {
            //create log mail activity
			$logs = new ModActivityClientEmail;
			$logs->client_id = $client_id;
			$logs->sender = $from;
			$logs->recipients = $to;
			$logs->subject = $subject;
			$logs->content_html = $content;
			$logs->date_entry = date(c);
			$logs->date_update = date(c);
			$logs->save();
        }

        $transport = isset($settings['mailer']) ? $settings['mailer'] : 'sendmail';
        
        try {
			$email = Yii::app()->bbmail;
            $email->setSubject($subject);
            $email->setBodyHtml($content);
            $email->setFrom($from, $from_name);
            $email->addTo($to, $to_name);
        
			$email->send($transport, $settings);

            $sent = true;
        } catch(Exception $e) {
            error_log($e->getMessage());
            $sent = false;
        }
        return $sent;
    }

	public function get_css($data)
	{
		$path = explode('protected',Yii::app()->basePath);
		if(!file_exists($path[0].$data['path']))
			return false;
		$result = file_get_contents($path[0].$data['path']);
        if($result){
			$patterns = "../../images/";
			$replacements = Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/img/';
			$result = ereg_replace($patterns, $replacements, $result);
			return '<style>'.$result.'</style>';
		}else {
			return false;
		}
	}

}
