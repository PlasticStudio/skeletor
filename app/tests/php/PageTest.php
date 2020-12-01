<?php

use SilverStripe\Dev\Debug;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Core\Injector\Injector;

// vendor/bin/phpunit app/tests/php/PageTest.php

class PageTest extends SapphireTest
{
    protected static $fixture_file = 'fixtures.yml';
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

    public function testPageLink()
    {
        $obj = $this->objFromFixture('ContactPage', 'contact');
        $this->assertEquals($obj->Link(), $this->page->PageLink($obj->ClassName));
        $this->assertNull($this->page->PageLink('HomePage'));
        $this->assertNull($this->page->PageLink('BogusClassName'));
    }

    // public function testInherited()
    // {
    //     $obj = $this->objFromFixture('ContactPage', 'contact');
    //     $this->assertEquals($obj->Link(), $this->page->PageLink($obj->ClassName));
    // }

}