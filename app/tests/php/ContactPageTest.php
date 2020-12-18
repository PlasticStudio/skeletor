<?php

namespace Skeletor\Tests;

use Page;
use Skeletor\Pages\ContactPage;
use SilverStripe\Dev\FunctionalTest;
use Skeletor\Tests\PageTestSiteConfig;

// vendor/bin/phpunit app/tests/php/ContactPageTest.php

class ContactPageTest extends FunctionalTest
{
    protected static $fixture_file = 'fixtures.yml';
    // Even thogh we're not using PageTestSiteConfig in this file, 
    // we still need to declare it as an extra dataobject, because we are
    // sharing the fixture file with  PageTest, which does use it
    protected static $extra_dataobjects = [
        PageTestSiteConfig::class 
    ]; 
    private $page;

    protected function setUp()
    {
        parent::setUp();
        $parent_obj = $this->objFromFixture(Page::class, 'about_us');
        $parent_obj->publishRecursive();
        $obj = $this->objFromFixture(ContactPage::class, 'contact');
        $obj->publishRecursive();
        $this->page = $obj;
    }

    protected function tearDown()
    {
        $this->page = null;
        parent::tearDown();
    }

    public function testViewContactPage() 
    {
        $page = $this->get($this->page->absoluteLink());
        $this->assertEquals(200, $page->getStatusCode());
    }

    public function testContactForm() 
    {
        $this->get($this->page->absoluteLink());
        $this->submitForm(
            'Form_Form', 
            null, 
            [
                'Name' => 'Mariah Carey',
                'Email' => 'test_example_com', // invalid email address, should give an error message
                'Phone' => '04 1234567',
                'Message' => 'All I want for Christmas is you.'
            ]
        );
        $this->assertExactHTMLMatchBySelector(
            "#Form_Form_Email_Holder span.validation", 
            ['<span class="message validation">Please enter an email address</span>']
        );

        $this->submitForm(
            'Form_Form', 
            null, 
            [
                'Name' => 'Mariah Carey',
                'Email' => 'test@example.com', // valid email address
                'Phone' => '04 1234567',
                'Message' => 'All I want for Christmas is you.'
            ]
        );
        $this->assertExactHTMLMatchBySelector(
            "p.contact-form-success", 
            ['<p class="contact-form-success">Thanks for your submission. We\'ll be in touch soon.</p>']
        );
    }

}