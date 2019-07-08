<?php

/*
	Resource filter form
	Filters via ajax, see resources.js
	To filter by action/page reload, uncomment the form action, and do not add the resources.js
*/

use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Dev\Backtrace;
use SilverStripe\Dev\Debug;
use SilverStripe\Forms\CheckboxsetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\View\ArrayData;

class ResourceHolderPageController extends PageController {

	private static $allowed_actions = [
		'FilterForm'
	];

	public function index(HTTPRequest $request) {

		$params = $request->requestVars();

		$resources = Resource::get()->sort('SortID');

		if( isset($params['Category']) && $params['Category'] != '' ) {
			$resources = $resources->filter([
				'Categories.Title:nocase' => $params['Category']
			]);
		}

		if( isset($params['Type']) && $params['Type'] != '' ) {
			$resources = $resources->filter([
				'Type' => $params['Type']
			]);
		}

		// filter resources by tag title
		if( isset($params['Tag']) && $params['Tag'] != '' ) {
			$resources = $resources->filter([
				'Tags.Title:nocase' => $params['Tag']
			]);
		}

		$paginatedResources = PaginatedList::create($resources, $request)->setPageLength(12);
		$url = $this->Link();
		// Build url encoded query string from params array
		$queryString = $url . '?' . http_build_query($params);
		
		$data = [
			'Resources' => $paginatedResources,
			'QueryString' => $queryString
		];

		if($request->isAjax()) {
			return $this->customise($data)->renderWith('Includes/FilteredResources');
		}

		return $data;

	}
	
	function FilterForm() {

		$params = $this->request->requestVars();

		$fields = FieldList::create(
			CheckboxsetField::create(
				'Category', 
				'Category',
				$this->Categories()->map('Title')
			)->setDefaultItems( (isset($params['Category']) && is_array($params['Category']) ) ? array_keys($params['Category']) : '' ),
			CheckboxsetField::create(
				'Type', 
				'Type',
				$this->ResourceTypesDataList()->map('Name', 'Value')
			)->setDefaultItems( (isset($params['Type']) && is_array($params['Type']) ) ? array_keys($params['Type']) : '' ),
			CheckboxsetField::create(
				'Tag', 
				'Tag',
				$this->Tags()->map('Title')
			)->setDefaultItems( (isset($params['Tag']) && is_array($params['Tag']) ) ? array_keys($params['Tag']) : '' ),
			LiteralField::create('Button', '<a class="readmore readmore__linkBox" href="/insights"><p>Clear</p></span></span></a>')
		);

		$actions = FieldList::create(
		//	FormAction::create('doFilterForm')->setTitle('Filter')
		);

		return Form::create($this, 'FilterForm', $fields, $actions)
			->setAttribute('data-url', $this->Link())
			->addExtraClass('ajax-filter-form')
			->disableSecurityToken();

	}

	function doFilterForm($data, $form) {
		$url = $this->Link();
		$queryString = $url . '?' . http_build_query($data);
		return $this->redirect($queryString);
	}

}