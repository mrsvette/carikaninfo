<?php

class EmailHook
{

    public function approveClientEmailByHash($hash)
    {
        $result = R::getRow('SELECT id, client_id FROM extension_meta WHERE extension = "mod_client" AND meta_key = "confirm_email" AND meta_value = :hash', array('hash'=>$hash));
        if(!$result) {
            throw new Box_Exception('Invalid email confirmation link');
        }
        R::exec('UPDATE client SET email_approved = 1 WHERE id = :id', array('id'=>$result['client_id']));
        R::exec('DELETE FROM extension_meta WHERE id = :id', array('id'=>$result['id']));
        return true;
    }
    
    public function generateEmailConfirmationLink($client_id)
    {
        $hash = strtolower(Tools::generatePassword(50));
        
		$criteria = new CDbCriteria;
		$criteria->compare('client_id',$client_id);
		$criteria->compare('meta_key','confirm_email');
		$count = ExtensionMeta::model()->count($criteria);
		if($count>0)
			$meta = ExtensionMeta::model()->find($criteria);
		else
			$meta = new ExtensionMeta;

        $meta->extension    = 'mod_client';
        $meta->client_id    = $client_id;
        $meta->meta_key     = 'confirm_email';
        $meta->meta_value   = $hash;
        $meta->date_entry   = date(c);
        $meta->date_update   = date(c);
		if($meta->isNewRecord)
        	$meta->save();
		else
			$meta->update(array('meta_value','date_update'));

        return Yii::app()->createAbsoluteUrl('/ecommerce/clients/confirmEmail',array('hash'=>$hash));
    }
    
    public static function onAfterClientSignUp($data)
    {   
        try {
            $email = array();
            $email['to_client'] = $data['id'];
            $email['code']      = 'mod_client_signup';
            $email['password']  = $data['password'];
            $email['require_email_confirmation']  = true;
            $email['email_confirmation_link'] = self::generateEmailConfirmationLink($data['id']);
			$template = new ModEmailTemplate;
            $send = $template->template_send($email);
        } catch(Exception $exc) {
            return $exc->getMessage();
        }
        
        return true;
    }
}
