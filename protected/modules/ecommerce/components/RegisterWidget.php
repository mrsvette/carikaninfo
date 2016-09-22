<?php
Yii::import('zii.widgets.CPortlet');

class RegisterWidget extends CPortlet{
	public $visible=true;
	public $position='main'; //main, right
	public $site_name=null;
	public $admin_email=null;
	public $client_name=null;
	
	public function init()
	{
		if($this->visible)
		{
			if(empty($this->site_name))
				$this->site_name=Yii::app()->config->get('site_name');
			if(empty($this->admin_email))
				$this->admin_email=Yii::app()->config->get('admin_email');
			if(empty($this->client_name))
				$this->client_name=Yii::app()->config->get('site_name');
		}
	}
 
	public function run()
	{
		if($this->visible)
		{
	 		$this->renderContent();
			if($this->position=='main'){
        		$this->registerClientScript();
        		$this->registerCss();
			}
		}
	}
	
	protected function renderContent()
	{
		Yii::import('application.modules.ecommerce.*');

		if($this->position=='main')
			$model=new ModClient('create');
		else
			$model=new ModClient;
		if(isset($_GET['name']))
			$model->name = $_GET['name'];
		if(isset($_GET['email']))
			$model->email = $_GET['email'];
		$model2 = new ModClientOrder('create');
		if(isset($_POST['ModClient']))
		{
			$model->attributes=$_POST['ModClient'];
			if($this->position=='right'){
				if($model->validate()){
					$this->controller->redirect(array('/mendaftar?name='.$model->name.'&email='.$model->email));
				}
			}
			$model->full_name = $model->company;
			if(!empty($model->full_name)){
				$ex_name = explode(" ",$model->full_name);
				$model->first_name = $ex_name[0];
				if(count($ex_name)==1)
					$last_name = $ex_name[0];
				else{
					if(count($ex_name)>2){
						$lname = array();
						for($i=1; $i<=count($ex_name); $i++){
							$lname[] = $ex_name[$i];
						}
						$last_name = implode(" ",$lname);
					}else	
						$last_name = $ex_name[1];
				}
				$model->last_name = $last_name;
			}
			$model->gender = 'male';
			$model->salt = md5($model->generateSalt());
			$model->password = $model->hashPassword('123456',$model->salt);
			if(!empty($model->company))
				$model->aid=Post::slug($model->company);
			/*$sosmed = array(
				'facebook'=>$model->sosmed_facebook,
				'twitter'=>$model->sosmed_twitter,
				'gplus'=>$model->sosmed_gplus,
			);
			$model->sosmed=CJSON::encode($sosmed);*/
			$model->type = 'corporate';
			$model->client_group_id = 1;
			$model->country = 'ID';
			$model->ip = $_SERVER['REMOTE_ADDR'];
			$model->status = ModClient::UNVERIFIED;
			$model->date_entry = date(c);
			//for order
			$model2->attributes = $_POST['ModClientOrder'];
			$model2->client_id = 1;
			$model2->period = '1Y';
			$model2->status = ModClientOrder::PENDING_SETUP;
			if(!empty($model2->product_id)){
				$product = ModProduct::model()->findByPk($model2->product_id);
				$model2->price = (is_object($product->pricing))? $product->pricing->{$model2->period} : $product->pricing;
				$model2->service_type = $product->type;
				$model2->title = ucfirst(strtolower($model2->service_type.' '.$product->title));
			}
			$model2->date_entry = date(c);
			if($model->validate() & $model2->validate()){
				$model->city = ModCity::model()->findByPk($model->city)->name;
				$model->state = ModProvince::model()->findByPk($model->state)->name;
				if($model->save()){
					if($model2->price>0){
						$model2->discount = $model2->price; //100% discount
						$model2->invoice_option = ModClientOrder::ISSUE_INVOICE;
						$model2->currency = 'IDR';
						$model2->quantity = 1;
						$model2->unit = 'product';
					}
					$model2->client_id = $model->id;
					$model2->save();
					//send mail
					Yii::import('application.modules.email.components.*');
					EmailHook::onAfterClientSignUp(array('id'=>$model->id,'password'=>'123456'));
				
					Yii::app()->user->setFlash('register',Yii::t('EcommerceModule.client','Thank you for register'));
					$this->controller->refresh();
				}
			}
		}
		$this->render('_register',array('model'=>$model,'model2'=>$model2));
	}

	private function sendMail($data){
		//$template_email =  Yii::app()->file->set('emails/'.$data['template'].'.html', true);
		//$user_email = $template_email->contents;
		$user_email = EmailTemplate::findOneByCode($data['template'])->content;
			
		$user_email = FPMail::addHF($user_email);
		$vari = array(
					'{-tanggal-}'=>date(Yii::app()->params['emailTime']),
					'{-sitename-}'=>Yii::app()->config->get('site_name'),
					'{-logo-}'=>Yii::app()->request->hostInfo.Yii::app()->request->baseUrl.'/uploads/images/'.Yii::app()->config->get('logo'),
					'{-support-}'=>Yii::app()->config->get('admin_email'),
					'{-copyright-}'=>'Copyright &copy; '.date(Y).' '.Yii::app()->config->get('site_name').'. All rights reserved.'
		);	
		$vari = $vari+$data['variable'];
		// just send to user	
		$user_email = str_replace(array_keys($vari), array_values($vari), $user_email);
		
		$email = Yii::app()->bbmail;
		$email->setSubject($data['subject']);
		$email->setBodyHtml($user_email);
		$email->setFrom($data['mail_from'], $data['from_name']);
		$email->addTo($data['mail_to'], $data['to_name']);
        
		$email->send('sendmail', array('mailer'=>'sendmail'));
		return true;
	}

	
    public function registerCss()
    {
		$cs = Yii::app()->getClientScript();
		$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/css/jquery.switch.min.css');
		$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/check_radio/skins/square/aero.css');
    }

    public function registerClientScript()
    {
        $cs = Yii::app()->getClientScript();
        //$select = YII_DEBUG === true ? 'bootstrap-select.js' : 'bootstrap-select.min.js';
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/js/jquery-ui-1.8.12.min.js',CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/js/jquery.wizard.min.js',CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/js/jquery.validate.js',CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/check_radio/jquery.icheck.min.js',CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/js/wizard_func.js',CClientScript::POS_END);
	}
}

?>
