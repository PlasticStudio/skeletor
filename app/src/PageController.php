<?php

use SilverStripe\View\SSViewer;
use SilverStripe\View\Requirements;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Controllers\ContentController;
use DNADesign\Elemental\Models\BaseElement;

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
			sprintf(
                'main_menu_page-%s',
                $this->ID
            ),
			$this->ID,
			SiteTree::get()->max('LastEdited'),
			SiteTree::get()->count()
		];
		return implode('-_-', $fragments);
	}
}