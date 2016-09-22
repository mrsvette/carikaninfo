<?php

/**
 * This is the model class for table "{{mod_activity_client_email}}".
 *
 * The followings are the available columns in table '{{mod_activity_client_email}}':
 * @property string $id
 * @property string $client_id
 * @property string $sender
 * @property string $recipients
 * @property string $subject
 * @property string $content_html
 * @property string $content_text
 * @property string $date_entry
 * @property string $date_update
 */
class ModActivityClientEmail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mod_activity_client_email}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id', 'length', 'max'=>20),
			array('sender, subject', 'length', 'max'=>255),
			array('recipients, content_html, content_text, date_entry, date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_id, sender, recipients, subject, content_html, content_text, date_entry, date_update', 'safe', 'on'=>'search'),
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
			'client_id' => 'Client',
			'sender' => 'Sender',
			'recipients' => 'Recipients',
			'subject' => 'Subject',
			'content_html' => 'Content Html',
			'content_text' => 'Content Text',
			'date_entry' => 'Date Entry',
			'date_update' => 'Date Update',
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
		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('recipients',$this->recipients,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('content_html',$this->content_html,true);
		$criteria->compare('content_text',$this->content_text,true);
		$criteria->compare('date_entry',$this->date_entry,true);
		$criteria->compare('date_update',$this->date_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModActivityClientEmail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
