<?php

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\View\Requirements;
use SilverStripe\Control\Director;

class PageController extends ContentController {

	private static $allowed_actions = [];

	public function init() {
		parent::init();
		
		if (Director::isLive()){
			Requirements::javascript('app/production/index.min.js');
			Requirements::css('app/production/index.min.css');
		} else {
			Requirements::css('app/production/index.css');
			Requirements::javascript('app/production/index.js');
		}
	}

	/**
	 * Returns the GoogloeAnalytics token if in live environment mode
	 * @return String
	 **/
	public function getGoogleAnalyticsKey() {
		return Config::inst()->get('Google', 'analytics');
	}
}
