<?php

/**
 * This is the model class for table "{{mod_client}}".
 *
 * The followings are the available columns in table '{{mod_client}}':
 * @property string $id
 * @property string $aid
 * @property string $client_group_id
 * @property string $role
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $status
 * @property integer $email_approved
 * @property integer $tax_exempt
 * @property string $type
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $birthday
 * @property string $phone_cc
 * @property string $phone
 * @property string $company
 * @property string $company_vat
 * @property string $company_number
 * @property string $address_1
 * @property string $address_2
 * @property string $city
 * @property string $state
 * @property string $postcode
 * @property string $country
 * @property string $notes
 * @property string $currency
 * @property string $lang
 * @property string $ip
 * @property string $date_entry
 * @property string $date_update
 */
class ModClient extends CActiveRecord
{
	public $full_name;
    const ACTIVE                    = 'active';
    const SUSPENDED                 = 'suspended';
    const CANCELED                  = 'canceled';
    const UNVERIFIED                = 'unverified';

	public $verifyCode;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_client}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email_approved, tax_exempt', 'numerical', 'integerOnly'=>true),
			array('aid, email, password, salt, company_number', 'length', 'max'=>255),
			array('client_group_id, gender', 'length', 'max'=>20),
			array('role, status', 'length', 'max'=>30),
			array('type, first_name, last_name, birthday, phone, company, company_vat, address_1, address_2, city, state, postcode, country', 'length', 'max'=>100),
			array('phone_cc, currency, lang', 'length', 'max'=>10),
			array('ip', 'length', 'max'=>45),
			array('notes, date_entry, date_update, full_name', 'safe'),
			array('email, password, first_name, last_name, phone, address_1, city, state, gender','required'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'on'=>'create'),
			array('full_name, company','required','on'=>'create'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, aid, client_group_id, role, email, password, salt, status, email_approved, tax_exempt, type, first_name, last_name, gender, birthday, phone_cc, phone, company, company_vat, company_number, address_1, address_2, city, state, postcode, country, notes, currency, lang, ip, date_entry, date_update', 'safe', 'on'=>'search'),
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
			'group_rel'=>array(self::BELONGS_TO,'ModClientGroup','client_group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'aid' => Yii::t('EcommerceModule.client','Alternative ID'),
			'client_group_id' => Yii::t('EcommerceModule.client','Client Group'),
			'role' => 'Role',
			'email' => 'Email',
			'password' => 'Password',
			'salt' => 'Salt',
			'status' => 'Status',
			'email_approved' => Yii::t('EcommerceModule.client','Email Approved'),
			'tax_exempt' => Yii::t('EcommerceModule.client','Tax Exempt'),
			'type' => Yii::t('EcommerceModule.client','Type'),
			'first_name' => Yii::t('EcommerceModule.client','First Name'),
			'last_name' => Yii::t('EcommerceModule.client','Last Name'),
			'gender' => Yii::t('EcommerceModule.client','Gender'),
			'birthday' => Yii::t('EcommerceModule.client','Birthday'),
			'phone_cc' => Yii::t('EcommerceModule.client','Phone Cc'),
			'phone' => Yii::t('EcommerceModule.client','Phone'),
			'company' => Yii::t('EcommerceModule.client','Company'),
			'company_vat' => Yii::t('EcommerceModule.client','Company Vat'),
			'company_number' => Yii::t('EcommerceModule.client','Company Number'),
			'address_1' => Yii::t('EcommerceModule.client','Address'),
			'address_2' => Yii::t('EcommerceModule.client','Address 2'),
			'city' => Yii::t('EcommerceModule.client','City'),
			'state' => Yii::t('EcommerceModule.client','State'),
			'postcode' => Yii::t('EcommerceModule.client','Postcode'),
			'country' => Yii::t('EcommerceModule.client','Country'),
			'notes' => Yii::t('EcommerceModule.client','Notes'),
			'currency' => Yii::t('EcommerceModule.client','Currency'),
			'lang' => Yii::t('EcommerceModule.client','Language'),
			'ip' => Yii::t('EcommerceModule.client','Ip Address'),
			'date_entry' => Yii::t('global','Date Entry'),
			'date_update' => Yii::t('global','Date Update'),
			'full_name' => Yii::t('EcommerceModule.client','Full Name'),
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
		$criteria->compare('aid',$this->aid,true);
		$criteria->compare('client_group_id',$this->client_group_id,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('email_approved',$this->email_approved);
		$criteria->compare('tax_exempt',$this->tax_exempt);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('phone_cc',$this->phone_cc,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('company_vat',$this->company_vat,true);
		$criteria->compare('company_number',$this->company_number,true);
		$criteria->compare('address_1',$this->address_1,true);
		$criteria->compare('address_2',$this->address_2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('postcode',$this->postcode,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->order = 'date_entry DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModClient the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTypes($type=null)
	{
		$types = array(
				'personal'=>Yii::t('EcommerceModule.client','Personal'),
				'corporate'=>Yii::t('EcommerceModule.client','Corporate'),
			);
		if(empty($type))
			return $types;
		else
			return $types[$type];
	}

	public function getGenders($sex=null)
	{
		$types = array(
				'male'=>Yii::t('EcommerceModule.client','Male'),
				'female'=>Yii::t('EcommerceModule.client','Female'),
			);
		if(empty($sex))
			return $types;
		else
			return $types[$sex];
	}

	public function getStatuses($status=null)
	{
		$types = array(
				self::ACTIVE=>Yii::t('EcommerceModule.client','Active'),
				self::SUSPENDED=>Yii::t('EcommerceModule.client','Suspended'),
				self::CANCELED=>Yii::t('EcommerceModule.client','Canceled'),
				self::UNVERIFIED=>Yii::t('EcommerceModule.client','Unverified'),
			);
		if(empty($status))
			return $types;
		else
			return $types[$status];
	}

	public function getFullName()
	{
		return $this->first_name.' '.$this->last_name;
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return $this->hashPassword($password,$this->salt)===$this->password;
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @param string salt
	 * @return string hash
	 */
	public function hashPassword($password,$salt)
	{
		return md5($salt.$password);
	}

	/**
	 * Generates a salt that can be used to generate a password hash.
	 * @return string the salt
	 */
	public function generateSalt()
	{
		return uniqid('',true);
	}

	/**
     * Returns list of world countries
     * 
     * @return array
     */
    public function getCountries($code=null)
    {
        //default countries
        $countries = array(
            "US" => "United States",
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua And Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia And Herzegowina",
            "BW" => "Botswana",
            "BR" => "Brazil",
            //          "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            //          "CD" => "Congo, The Democratic Republic Of The",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Cote D'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "TP" => "East Timor",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            //          "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GA" => "Gabon",
            "GB" => "Great Britain",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "EL" => "Hellenic Republic (Greece)",
            //          "HM" => "Heard And Mc Donald Islands",
            //          "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            //          "KP" => "Korea, Democratic People's Republic Of",
            "KR" => "Korea, Republic Of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            //          "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libyan Arab Jamahiriya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macau",
            //          "MK" => "Macedonia, Former Yugoslav Republic Of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            //          "FM" => "Micronesia, Federated States Of",
            "MD" => "Moldova, Republic Of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "KN" => "Saint Kitts And Nevis",
            "LC" => "Saint Lucia",
            //          "VC" => "Saint Vincent And The Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome And Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            //          "GS" => "South Georgia, South Sandwich Islands",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SH" => "St. Helena",
            "PM" => "St. Pierre And Miquelon",
            "SD" => "Sudan",
            "SR" => "Suriname",
            //          "SJ" => "Svalbard And Jan Mayen Islands",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania",
            "TH" => "Thailand",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad And Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            //          "TC" => "Turks And Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            //          "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands (British)",
            "VI" => "Virgin Islands (U.S.)",
            //          "WF" => "Wallis And Futuna Islands",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "YU" => "Yugoslavia",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe"
        );
		if(empty($code))
			return $countries;
		else
			return $countries[$code];
	}

	public function getCurrencies($cur=null)
	{
		$types = array(
				'IDR'=>'Rupiah',
				'USD'=>'Dolar',
			);
		if(empty($cur))
			return $types;
		else
			return $types[$cur];
	}

	public function items($client_id=null,$title=null)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('status',self::ACTIVE);
		$models=self::model()->findAll($criteria);
		$items=array();
		if(!empty($title))
			$items['']=$title;
		foreach($models as $model){
			$items[$model->id]=$model->id.' - '.$model->fullName.' <'.$model->email.'>';
		}
		return $items;
	}

	public function getCount($status=null)
	{
		$criteria=new CDbCriteria;
		if(!empty($status))
			$criteria->compare('status',$status);
		$count=self::model()->count($criteria);
		return $count;
	}
}
