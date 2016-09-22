<?php

class SiteController extends DController
{
	public $layout='column1';

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('index','contact','captcha','search','error','slug','image'),
				'users'=>array('*'),
			),
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('install','completed','home','finishedInstalation'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('tracking'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->layout='column1';
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	public function actionIndex()
	{
		if(Yii::app()->user->getState('install')){
			$this->forward('/site/install');
		}else{
			$this->layout='column1';
			$this->forward('/site/home');
			$this->render('index');
		}
	}

	public function actionInstall()
	{
		$this->layout='column_install';
		$model=new InstallForm('step1');
		//default value
		$model->database_server='localhost';
		$model->database_username='root';
		$model->database_name='jagung_bakar';
		$model->theme='jagungbakar';
		if(isset($_POST['InstallForm'])){
			$model->attributes=$_POST['InstallForm'];
			if($model->validate()){
				$dbhelp=new MySQLDatabaseConfigurationHelper;
				$databaseConfig=array(
						'server'=>$model->database_server,
						'username'=>$model->database_username,
						'password'=>$model->database_password,
						'database'=>$model->database_name,
					);
				$connect=$dbhelp->requireDatabaseServer($databaseConfig);
				if($connect['success']){ //if success to connect into database
					if($dbhelp->createDatabase($databaseConfig)){
						//dump the sql
						$schema=Yii::app()->basePath.'/data/schema.mysql.sql';
						if(is_file($schema)){
							$sql=file_get_contents($schema);
							$connection=new CDbConnection('mysql:host='.$model->database_server.';dbname='.$model->database_name,$model->database_username,$model->database_password);
							$connection->active=true;
							$command=$connection->createCommand($sql);
							$command->execute();
							//setup config file
							$config=$model->setConfig($model->attributes);
							Yii::app()->user->setState('InstallForm',$model->attributes);
							$this->forward('completed');
						}
					}
				}
			}
		}
		$this->render('install',array('model'=>$model));
	}

	public function actionCompleted()
	{
		if(Yii::app()->user->hasState('InstallForm')){
			$this->layout='column_install';
			$model=new InstallForm('step2');
			$this->render('completed',array('model'=>$model));
		}else
			$this->redirect('index');
	}

	public function actionFinishedInstalation()
	{
		if(Yii::app()->request->isAjaxRequest){
			$data=Yii::app()->user->getState('InstallForm');
			if(!empty($data)){
				$create_user=User::create_data(array('username'=>$data['admin_username'],'password'=>$data['admin_password']));
				$set_title=Params::update_data($data['application_name']);
				if($set_title){
					Yii::app()->user->setState('InstallForm',null);
					Yii::app()->user->setState('install',false);
					echo CJSON::encode(array('status'=>'success'));exit;
				}
			}else
				$this->redirect('/home');
		}
	}

	public function actionHome()
	{
		$this->layout='column1';

		$this->render('home');
	}

	public function actionSearch()
	{
		if(Yii::app()->request->isAjaxRequest){
			if(isset($_POST['question'])){
				// Stop jQuery from re-initialization
				Yii::app()->clientScript->scriptMap['jquery.js'] = false;
				//Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
				//Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;

				$criteria=new CDbCriteria;
				$criteria->compare('content_rel.title',$_POST['question'],true,'OR');
				$criteria->compare('content_rel.content',$_POST['question'],true,'OR');
				$criteria->compare('lang.code',Yii::app()->language);
				$criteria->with=array('content_rel','content_rel.language_rel'=>array('alias'=>'lang'));
				$criteria->together=true;
				$dataProvider=new CActiveDataProvider('Post',array(
								'criteria'=>$criteria,
								'pagination'=>array(
									'pageSize'=>100,
								),
					));
				//set state for search result
				Yii::app()->user->setState('Search_key',$_POST['question']);

				echo CJSON::encode( array(
					'status'=>'success',
					'div' => $this->renderPartial('search_result',array('dataProvider'=>$dataProvider),true,true),
				));
				exit;
			}
		}else{
			$model=new SearchEngineForm;
			if(isset($_POST['SearchEngineForm'])){
				$model->attributes=$_POST['SearchEngineForm'];
				if($model->validate()){
					$criteria=new CDbCriteria;
					$criteria->compare('content',$model->search_for,true);
					$criteria->compare('title',$model->search_for,true,'OR');
					$dataProvider=new CActiveDataProvider('Post',array('criteria'=>$criteria));
				}
			}
		
			$this->render('search',array('model'=>$model,'dataProvider'=>$dataProvider));
		}
	}

	public function actionSlug()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('slug','');
		$models=Post::model()->findAll($criteria);
		$items=array();
		if(count($models)>0){
			foreach($models as $model){
				$title=$model->createSlug();
				$model->slug=$title;
				if($model->update('slug'))
					$items[]=$model->slug;
			}
		}
		var_dump($items);exit;
	}

	public function actionImage($key)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.key',$key);
		$model=Banner::model()->find($criteria);
		if(@file_exists($model->src)) {
			$path_parts = @pathinfo($model->src);
			$filename = basename($model->src);
			$file_extension = strtolower(substr(strrchr($filename,"."),1));
			switch( $file_extension ) {
				case "gif": $ctype="image/gif"; $im = imagecreatefromgif($model->src); break;
				case "png": 
					$ctype="image/png"; 
					$im = imagecreatefrompng($model->src); 
					$background = imagecolorallocate($im, 0, 0, 0);
					imagecolortransparent($im, $background);
					imagealphablending($im, false);
					imagesavealpha($im, true);
					break;
				case "jpeg":
				case "jpg": $ctype="image/jpg"; $im = imagecreatefromjpeg($model->src); break;
				default:
			}

			header('Content-Type: '.$ctype); 
			imagepng($im);
			imagedestroy($im);
		}
	}

	public function actionTracking()
	{
		if(Yii::app()->request->isPostRequest){
			$model=new Visitor('create');
			$model->client_id=0;
			if(!empty($_POST['s'])){
				$model->session_id=$model->getCookie('_ma',false);
				if(!empty($model->cookie)){
					$model->date_expired=$model->cookie;
				}else{
					Yii::app()->request->cookies->remove('_ma');
					$model->date_expired=date("Y-m-d H:i:s",time()+1800);
				}
			}
			$model->ip_address=$_SERVER['REMOTE_ADDR'];
			$model->page_title=$_POST['t'];
			$model->url=$_POST['u'];
			$model->url_referrer=$_POST['r'];
			$model->date_entry=date('c');
			$model->platform=$_POST['p'];
			$model->user_agent=$_POST['b'];
			$mobile_detect=new MobileDetect;
			$model->mobile=($mobile_detect->isMobile())? 1 : 0;
			if($model->save()){
				if($model->session_id=='false' || empty($model->session_id)){
					$model->session_id=md5($model->id);
					$model->update(array('session_id'));
					//$cookie_time = (3600 * 0.5); // 30 minute
					//setcookie("ma_session", $model->session_id, time() + $cookie_time, '/');
				}
				//set notaktif
				$criteria=new CDbCriteria;
				$criteria->compare('active',1);
				$criteria->compare('session_id',$model->session_id);
				$models2=Visitor::model()->findAll($criteria);
				foreach($models2 as $model2){
					$model2->active=0;
					$model2->update(array('active'));
				}
				$model->active=1;
				$model->update(array('active'));
				echo $model->session_id;
			}else{
				var_dump($model->errors);exit;
				echo 'failed';
			}
			exit;
		}
	}
}
