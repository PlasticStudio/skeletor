<?php

namespace Skeletor\Pages;

use PageController;
use Skeletor\DataObjects\FormSubmission;
use SilverStripe\Dev\Debug;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\FieldType\DBHTMLText;

class ContactPageController extends PageController {

    private static $allowed_actions = [
		'Form',
		'submitted'
	];

	public function init()
	{
        parent::init();
    }

	public function Form()
	{
		if ($this->FormSubmitted()) {
			return DBHTMLText::create()->setValue($this->SuccessMessage);
		}

		$fields = FieldList::create(
			TextField::create('Name', 'Name'),
			EmailField::create('Email', 'Email'),
			TextField::create('Phone', 'Phone'),
			TextareaField::create('Message', 'Message')
		);

		$actions = FieldList::create(
			FormAction::create('doForm', 'Submit')
		);

		$validator = RequiredFields::create('Name', 'Email','Phone', 'Message');

		return Form::create($this, 'Form', $fields, $actions, $validator)->addExtraClass('contact-form');
	}

	public function doForm($data)
	{
		$submission = FormSubmission::create();
		$submission->Payload = json_encode($data);
		$submission->OriginID = $this->ID;
		$submission->OriginClass = $this->ClassName;
		$submission->write();
        $submission->SendEmail();

        // check if send confirmation email is set
		if ($this->SendCustomerEmail == true ) {
			$submission->SendConfirmationEmail();
		}

		$this->redirect($this->Link('submitted')); 
    }

    /**
     * checks if session has a form submission
     * @return  bool
     */
	public function FormSubmitted()
	{
		$params = $this->getRequest()->params();
		return (isset($params['Action']) && $params['Action'] == 'submitted');
	}
}