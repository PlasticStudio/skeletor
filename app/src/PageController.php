<?php

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\View\Requirements;

class PageController extends ContentController {

	private static $allowed_actions = [];

	public function init() 
	{
		parent::init();
		Requirements::javascript('resources/app/client/dist/index.js');
      	Requirements::css('resources/app/client/dist/index.css');
      	Requirements::set_force_js_to_bottom(true);
	}
}