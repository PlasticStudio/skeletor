<?php

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\View\Requirements;
use SilverStripe\Control\Director;

class PageController extends ContentController {

	private static $allowed_actions = [];

	public function init() {
		parent::init();

			Requirements::css('app/production/index.css');
			Requirements::javascript('app/production/index.js');

	}
}
