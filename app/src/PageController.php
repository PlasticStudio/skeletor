<?php

use SilverStripe\View\Requirements;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\CMS\Controllers\ContentController;

class PageController extends ContentController {

	private static $allowed_actions = [];

	public function init() 
	{
		parent::init();
		Requirements::javascript('resources/app/client/dist/index.js');
      	Requirements::css('resources/app/client/dist/index.css');
      	Requirements::set_force_js_to_bottom(true);
	}

	/**
	 * Key for cached version of main menu per page
	 */
	public function MainMenuCacheKey()
	{
		$fragments = [
			'main_menu_per_page',
			$this->ID,
			SiteTree::get()->max('LastEdited'),
			SiteTree::get()->count()
		];
		return implode('-_-', $fragments);
	}

	/**
	 * return 
	 */
	public function ClientAssetsPath()
	{
		return Controller::join_links(
			'resources',
			'app',
			'client',
			'assets'
		);
	}
}