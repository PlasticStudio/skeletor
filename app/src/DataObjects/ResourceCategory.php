<?php

use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;

class ResourceCategory extends DataObject {
	
	private static $db = [
		'Title' => 'Varchar'
	];

	private static $has_one = [
		'ResourceHolderPage' => 'ResourceHolderPage'
	];

	private static $belongs_many_many = [
		'Resources' => 'Resource'
	];

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName([
			'ResourceHolderPageID',
			'Resources'
		]);

		return $fields;
	}

	public function Link() {
		return $this->ResourceHolderPage->Link('/category/' . strtolower($this->Title));
	}

	public function LinkingMode() {
        return ucwords(Controller::curr()->getRequest()->param('ID')) == $this->Title ? 'current' : 'link';
    }
}