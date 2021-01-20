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
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBHTMLText;

class ContactPageController extends PageController {

    private static $allowed_actions = [
		'Form',
		'submitted',
		'submissionerror'
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

		if ($this->FormSubmissionError()) {
			return DBHTMLText::create()->setValue('<p>Something went wrong and the form could not be submitted. Please try again later.</p>');
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

		if ($submission->SendEmail()) {
			if ($this->SendCustomerEmail == true ) {
				$submission->SendConfirmationEmail();
			}
			$this->redirect($this->Link('submitted'));
		} else {
			$this->redirect($this->Link('submissionerror'));
		}
        
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

    /**
     * checks if session has a form submission
     * @return  bool
     */
	public function FormSubmissionError()
	{
		$params = $this->getRequest()->params();
		return (isset($params['Action']) && $params['Action'] == 'submissionerror');
	}
}