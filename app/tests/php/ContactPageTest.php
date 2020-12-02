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
    private $form;

    protected function setUp()
    {
        // $this->form = Injector::inst()->get(ContactPageController::class);
        parent::setUp();
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
        parent::tearDown();
    }

    public function testContactForm() 
    {
        $obj = $this->objFromFixture(ContactPage::class, 'contact');
        // Debug::dump($obj->absoluteLink());
        $obj->publishRecursive();
        $page = $this->get($obj->absoluteLink());

        $this->assertEquals(200, $page->getStatusCode());

        // We should see a login form
        // $login = $this->submitForm("LoginFormID", null, [
        //     'Email' => 'test@example.com',
        //     'Password' => 'wrongpassword'
        // ]);

        // // wrong details, should now see an error message
        // $this->assertExactHTMLMatchBySelector("#LoginForm p.error", [
        //     "That email address is invalid."
        // ]);

        // // If we login as a user we should see a welcome message
        // $me = Member::get()->first();

        // $this->logInAs($me);
        // $page = $this->get('home/');

        // $this->assertExactHTMLMatchBySelector("#Welcome", [
        //     'Welcome back'
        // ]);
    }

    // public function testViewGetConnectedForm() 
    // {
    //     $obj = $this->objFromFixture(GetConnectedForm::class, 'get-connected');
    //     $obj->publishRecursive();
    //     $page = $this->get($obj->absoluteLink());
    //     $this->assertEquals(200, $page->getStatusCode());
    //     $this->assertPartialMatchBySelector(
    //         ".online-form__footer p.button__text", 
    //         "Let's get started"
    //     );
        
        // $this->assertPartialHTMLMatchBySelector(
        //     "#step-1", 
        //     [
        //         '<p>Once we receive your request and confirm what is needed, it can take up to two weeks to provide a quote to you.*</p>',
        //         // 'The process to install a point of supply can take up to 6-8 weeks once payment is received.',
        //         // 'Before the final livening of your connection, you will need to sign up with an electricity retailer.',
        //         // 'You should consult a qualified electrician before making a request, as we will have some technical requirements',
        //         // 'Vegetation may need to be cut back or removed to allow this work to proceed',
        //         // "FAQ's"
        //     ]
        // );
        // $this->assertPartialHTMLMatchBySelector(
        //     "#step-2 .profile_type .radio",
        //     [
        //         '<input id="Form_Form_ProfileType_StreetNumberName" class="radio" name="ProfileType" type="radio" value="StreetNumberName"/>',
        //         '<input id="Form_Form_ProfileType_LotDP" class="radio" name="ProfileType" type="radio" value="LotDP"/>'
        //     ]
        // );
        // $this->assertPartialHTMLMatchBySelector(
        //     "#step-2 #Form_Form_Street_HomeAddress",
        //     [
        //         '<input type="text" name="Street_HomeAddress" class="addressautocomplete address-autocomplete-field " id="Form_Form_Street_HomeAddress" required="required" placeholder="Start typing..."/>'
        //     ]
        // );

        // We should see a login form
        // $submit = $this->submitForm("Form_Form", null, [
        //     // step 1: empty (informational only)

        //     // step 2
        //     'Street_HomeAddress' => '123 test street',
        //     'street_number' => '123',
        //     'route' => 'test street',
        //     'sublocality_level_1' => 'testington',
        //     'postal_code' => '6011',
        //     'somekindofboosheet' => 'something',

        //     // step 3
        //     'FirstName' => 'Test',
        //     'Surname' => 'Tester',
        //     'Company' => 'PS/digital',
        //     //'EmailAddress' => 'testing@psdigital.co.nz',
        //     'MobilePhone' => '021 0000000',
        //     'Phone' => '04 0000000',

        //     // step 4
        //     'Form_Form_SupplyTypeOptions[Residential]' => 'Residential',
        //     'Form_Form_NumberPhases[Single]' => 'Single',
        //     'Form_Form_FuseSize[60amp]' => '60amp',
        //     'Form_Form_TemporarySupplyOptions[No]' => 'No'

        // ]);
    // }


}