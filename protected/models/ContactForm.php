<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
	public $name;
	public $email;
	public $phone;
	public $subject;
	public $body;
	public $company;
	public $country;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, email, phone, subject, body, country', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			array('company','safe'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'on'=>'create'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>Yii::t('contact','Name'),
			'phone'=>Yii::t('contact','Phone'),
			'subject'=>Yii::t('contact','Subject'),
			'body'=>Yii::t('contact','Body'),
			'verifyCode'=>Yii::t('contact','Verification Code'),
			'company'=>Yii::t('contact','Company'),
			'country'=>Yii::t('contact','Country'),
		);
	}
}
