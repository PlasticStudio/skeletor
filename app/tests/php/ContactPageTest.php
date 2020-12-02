<?php

use SilverStripe\Dev\Debug;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Control\Controller;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;

// vendor/bin/phpunit app/tests/php/ContactPageTest.php

class ContactPageTest extends FunctionalTest
{
    protected static $fixture_file = 'fixtures.yml';
    private $params;
    private $page;
    private $form;

    protected function setUp()
    {
        parent::setUp();
        $parent_obj = $this->objFromFixture(Page::class, 'about_us');
        $parent_obj->publishRecursive();
        $obj = $this->objFromFixture(ContactPage::class, 'contact');
        $obj->publishRecursive();
        $this->page = $obj;
        $this->form = Injector::inst()->get(ContactPageController::class);
        // parent::setUp();
        //$this->dummy_submission_data = file_get_contents(BASE_PATH . '/app/tests/php/CIW/dummy_submission_data.json');
        // $request = Injector::inst()->get(
        //     HTTPRequest::class,
        //     true,
        //     [
        //         'GET',
        //         $this->objFromFixture(ContactPage::class, 'contact')->absoluteLink(),
        //         ['ID' => 123]
        //     ]
        // );
        // $this->params = $request->getVars();
        
        // $this->form = singleton(ContactPageController::class);
    }

    protected function tearDown()
    {
        $this->page = null;
        $this->form = null;
        parent::tearDown();
    }

    public function testViewContactPage() 
    {
        $page = $this->get($this->page->absoluteLink());
        $this->assertEquals(200, $page->getStatusCode());
    }

    public function testContactForm() 
    {
        $page = $this->get($this->page->absoluteLink());
        $this->assertEquals(200, $page->getStatusCode());

        // We should see a contact form
        $this->submitForm(
            'Form_Form', 
            null, 
            [
                'Name' => 'Mariah Carey',
                'Email' => 'test_example_com',
                'Phone' => '04 1234567',
                'Message' => 'All I want for Christmas is you.'
            ]
        );
        // invalid email address, should now see an error message
        $this->assertExactHTMLMatchBySelector(
            "#Form_Form_Email_Holder span.validation", 
            ['<span class="message validation">Please enter an email address</span>']
        );

        $this->submitForm(
            'Form_Form', 
            null, 
            [
                'Name' => 'Mariah Carey',
                'Email' => 'test@example.com',
                'Phone' => '04 1234567',
                'Message' => 'All I want for Christmas is you.'
            ]
        );
        $this->assertExactHTMLMatchBySelector(
            ".contact-form p", 
            ["<p>Thanks for your submission. We'll be in touch soon.</p>"]
        );

    }

}