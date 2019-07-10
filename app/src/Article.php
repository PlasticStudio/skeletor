<?php

class Article extends Page {

	private static $has_many = [
		'Resources' => 'Resource'
	];
	
	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName([
			'DropdownNavLabel',
			'SidebarModules'
		]);

		return $fields;
	}

}