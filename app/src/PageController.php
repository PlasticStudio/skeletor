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

	public function MenuCacheKey()
	{
		$fragments = [
			'main_menu',
			$this->ID,
			SiteTree::get()->max('LastEdited'),
			SiteTree::get()->count()
		];
		return implode('-_-', $fragments);
	}

	public function ElementalAreaCacheKey()
	{
		$fragments = [
			'elemental_area',
			$this->ID,
			BaseElement::get()->max('LastEdited'),
			BaseElement::get()->count()
		];
		return implode('-_-', $fragments);
	}
}