<?php

use SilverStripe\View\Requirements;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\ORM\FieldType\DBDatetime;
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
	 * Path to client assets directory
	 * for use in templates etc 
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

	public function PageFooterCacheKey()
	{
		$fragments = [
			'footer',
			SiteConfig::current_site_config()->Title,
			DBDatetime::now()->Year()
		];
		return implode('-_-', $fragments);
	}
}