<?php

namespace Skeletor\Tests;

use Page;
use PageController;
use Skeletor\Pages\HomePage;
use Skeletor\Tests\PageTestSiteConfig;
use SilverStripe\Dev\Debug;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Core\Injector\Injector;

// vendor/bin/phpunit app/tests/php/PageTest.php

class PageTest extends SapphireTest
{
    protected static $fixture_file = 'fixtures.yml';
    protected $usesDatabase = true;
    protected static $extra_dataobjects = [
        PageTestSiteConfig::class
    ]; 
    private $page;

    protected function setUp()
    {
        $this->page = Injector::inst()->get(Page::class);
        parent::setUp();
    }

    protected function tearDown()
    {
        $this->page = null;
        parent::tearDown();
    }

    public function testMyController()
    {
        $this->assertInstanceOf(PageController::class, $this->page->MyController());

        $home_page = Injector::inst()->get(HomePage::class);
        $this->assertFalse($home_page->MyController());
    }

    public function testPageType()
    {
        $obj = $this->objFromFixture('Skeletor\Pages\ContactPage', 'contact');
        $this->assertEquals($obj->ClassName, $this->page->PageType($obj->ClassName)->ClassName);
        $this->assertFalse($this->page->PageType('HomePage'));
        $this->assertFalse($this->page->PageType('BogusClassName'));
    }

    public function testPageLink()
    {
        $obj = $this->objFromFixture('Skeletor\Pages\ContactPage', 'contact');
        $this->assertEquals($obj->Link(), $this->page->PageLink($obj->ClassName));
        $this->assertFalse($this->page->PageLink('HomePage'));
        $this->assertFalse($this->page->PageLink('BogusClassName'));
    }

    public function testInherited()
    {
        $page = $this->objFromFixture('Skeletor\Pages\ContactPage', 'contact');
        $obj = $page->Inherited('BannerImage');
        // $this->assertObjectHasAttribute('Name', $obj);
        $this->assertEquals('about-us-banner.jpg', $obj->Name);
        $this->assertEquals('About Us Page', $page->Inherited('MetaTitle'));
        $this->assertNull($page->Inherited('BogusProperty'));
    }

    public function testGetLogoFromSiteConfig()
    {
        $site_config = $this->objFromFixture('Skeletor\Tests\PageTestSiteConfig', 'site_config');
        // $site_config = SiteConfig::current_site_config();
        $logo = $this->objFromFixture('SilverStripe\Assets\Image', 'site_logo');
        $this->assertEquals($logo->Name, $this->page->getLogoFromSiteConfig($site_config)->Name);
    }

}