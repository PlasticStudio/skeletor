<?php

use SilverStripe\Dev\Debug;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\TextareaField;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\AssetAdmin\Forms\UploadField;

class Page extends SiteTree {

	private static $table_name = 'Page';

	private static $db = [
		'MetaTitle' 	=> 'Text',
		'MetaKeywords' 	=> 'Text'
	];

	private static $has_one = [
		'BannerImage' => Image::class
	];

	private static $owns = [
		'BannerImage'
	];

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		if ($this->ClassName != 'SilverStripe\CMS\Model\RedirectorPage' ) {
			$fields->addFieldToTab(
				'Root.Main.Metadata',
				TextField::create('MetaTitle','Meta Title')
					->setRightTitle('Customised title for use in search engines. Defaults to the page title.'),
				'MetaDescription'
			);
			$fields->addFieldToTab("Root.Main.Metadata", TextareaField::create('MetaKeywords', 'Meta Keywords'), 'MetaDescription');
		}
		$fields->addFieldsToTab('Root.Banner', UploadField::create('BannerImage', 'Banner Image')->setFolderName('Banners'));

		return $fields;
	}

	/**
	 * Get this object's controller
	 * @return obj
	 */
	public function MyController()
	{
		$class = $this->ClassName . "Controller";
		if (class_exists($class)) {
			return new $class();
		}
		return false;
	}

	/**
	 * Get an inherited 'thing'
	 * This multi-purpose method allows us to iterate up the site tree to get a property or method.
	 * Usage: $Inherited('BannerImage') or $Inherited('Colour'), etc
	 *
	 * @param String $property
	 * @return Array
	 **/
	public function Inherited($property = null)
	{
		$page = $this;

		// Identify whether the requested property is a property or a method()
		$is_method = $page->hasMethod($property);

		// Recursively go up the tree looking for our property with a non-falsy value
		while ($page->ParentID > 0 && ! ($is_method ? ($page->$property() && $page->$property()->exists()) : ($page->$property !== null))){
			$page = $page->Parent();
		}

		return ($is_method ? $page->$property() : $page->$property);
	}


	/**
	 * Get a page type by ClassName
	 * Returns the *first* page instance of this ClassName
	 *
	 * @param string $class_name
	 * @return object
	 **/
	public function PageType($class_name)
	{
		if ($page = SiteTree::get()->Filter('ClassName',$class_name)->first()) {
			return $page;			
		}
		return false;
	}

	/**
	 * Get a page link by ClassName
	 *
	 * @param string $class_name
	 * @return string page link
	 **/
	public function PageLink($class_name)
	{
		if ($page = $this->PageType($class_name)) {
			return $page->Link();			
		}
		return false;
	}

	/**
	 * Get logo set in site config if it exists
	 **/
	public function Logo()
	{
		return $this->getLogoFromSiteConfig(SiteConfig::current_site_config());
	}

	public function getLogoFromSiteConfig($site_config)
	{
		if ($logo = $site_config->Logo()) return $logo;
		return false;
	}
	
	/**
	 * Return image to use in og:image meta tag
	 * Default to site logo if it exists, otherwise return false.
	 *
	 * Override this as needed in page classes to dish up a relevant image.
	 * For instance, a news item may have a featured image, so on
	 * that page class this function could return the featured image.
	 **/
	public function OgImage()
	{
		if ($image = $this->Logo()) {
			return $image;
		}
		return false;
	}
}
