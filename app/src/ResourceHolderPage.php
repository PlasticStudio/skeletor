<?php

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Dev\Backtrace;
use SilverStripe\Dev\Debug;

class ResourceHolderPage extends Page {

	private static $allowed_children = [
		'Article'
	];
	
	private static $has_many = [
		'Categories' => 'ResourceCategory',
		'Tags' => 'ResourceTag',
		'Resources' => 'Resource'
	];

	public function ResourceTypes() {
		return [
			'Article' => 'Article',
			'InternalLink' => 'Internal Link', 
			'ExternalLink' => 'External Link',
			'Image' => 'Image',
			'Video' => 'Video',
			'Placeholder' => 'Placeholder'
		];
	}

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName([
			'DropdownNavLabel',
			'Content',
			'SidebarModules'
		]);

		// Add resources
		$resourceConfig = GridFieldConfig_RecordEditor::create()->addComponent( new GridFieldOrderableRows('SortID') );
		$fields->addFieldToTab('Root.Resources (' . count($this->Resources()) . ')', GridField::create('Resources', 'Resources', $this->Resources(), $resourceConfig));

		// Manage categories
		$catConfig = GridFieldConfig_RecordEditor::create();
		$fields->addFieldToTab('Root.ResourceSettings.Categories', GridField::create('Categories', 'Categories', $this->Categories(), $catConfig));
		
		// Manage tags	
		$tagConfig = GridFieldConfig_RecordEditor::create();
		$fields->addFieldToTab('Root.ResourceSettings.Tags', GridField::create('Tags', 'Tags', $this->Tags(), $tagConfig));

		return $fields;
	}

	/*
		Convert resources array into data list
		Used to loop out resources in template
	*/
	public function ResourceTypesDataList(){

		$list = ArrayList::create();

		foreach($this->ResourceTypes() as $key => $value) {
			$list->push( 
				new ArrayData( 
					array(
						'Name' => $key,
						'Value' => $value
					) 
				) 
			);
		}
		return $list;
	}
}