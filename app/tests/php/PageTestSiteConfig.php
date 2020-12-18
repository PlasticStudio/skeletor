<?php

namespace Skeletor\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\Assets\Image;
use SilverStripe\SiteConfig\SiteConfig;

class PageTestSiteConfig extends SiteConfig implements TestOnly
{
    private static $table_name = 'PageTestSiteConfig';

	private static $has_one = [
		'Logo' => Image::class
	];

	private static $owns = [
		'Logo'
	];

}