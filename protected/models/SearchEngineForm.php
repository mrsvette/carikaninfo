<?php

/**
 * SearchEngineForm class.
 */
class SearchEngineForm extends CFormModel
{
	public $search_for;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('search_for', 'required'),
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
			'search_for'=>'Search For',
		);
	}
}