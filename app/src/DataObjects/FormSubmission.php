<?php

namespace Skeletor\DataObjects;

use SilverStripe\Dev\Debug;
use Psr\Log\LoggerInterface;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use SilverStripe\Control\Director;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\SiteConfig\SiteConfig;
use Skeletor\Admin\FormSubmissionAdmin;
use SilverStripe\Core\Injector\Injector;

class FormSubmission extends DataObject {

	private static $singular_name = 'Form submission';
	private static $plural_name = 'Form submissions';
	private static $description = 'The submission data record for all form submissions';
	private static $default_sort = 'Created DESC';
	private static $table_name = 'FormSubmission';

	private static $db = [
		'Payload' 	=> 'Text',
		'IPAddress' => 'Varchar(18)'
	];

	private static $has_one = [
		'Origin' => DataObject::class
	];

	private static $summary_fields = [
		'Created' 		=> 'Created',
		'OriginClass' 	=> 'Origin type',
		'Origin.Title' 	=> 'Origin',
		'Origin.Link' 	=> 'Origin link',
		'IPAddress' 	=> 'IP Address'
	];

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Main', ReadonlyField::create('OriginTitle','Origin',$this->Origin()->Title));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('OriginClass','Origin class'));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('OriginLink','Origin link', $this->Origin()->Link()));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('IPAddress','IPAddress'));
		$fields->addFieldToTab('Root.Main', TextareaField::create('Payload','Payload')->setReadOnly(true));

		return $fields;
	}

	/**
	 * Send emails responding to this submission
	 *
	 * @return Boolean
	 **/
	public function SendEmail($fields = null)
	{
		$recipients = $this->EmailRecipients();
		if (!$recipients) {
			Injector::inst()->get(LoggerInterface::class)->error('Cannot send email: no Email Recipients defined in settings.');
			return false;
		}

		// validate email addresses before attempting to send
		$to = [];
		foreach ($recipients as $recipient) {
			if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
				$to[] = $recipient;
			}
		}

		$config = $this->SiteConfig();

		$email = Email::create()
			->setHTMLTemplate('Email\\FormSubmission') 
			->setData([
				'Submission' => $this,
				'Payload' => $this->PayloadAsArray($fields)
			])
			->setTo($to)
			->setSubject($config->Title. ' ' . $this->Origin()->Title . ' form submission');
			

		if ($config->EmailFrom() && !empty($config->EmailFrom())) {
			$email->setFrom($config->EmailFrom());
		}

		if ($config->EmailReplyTo && !empty($config->EmailReplyTo)) {
			$email->setReplyTo($config->EmailReplyTo);
		}

		return $email->send();

	}

	/**
	 * Send a confirmation email to the form submitter
	 *
	 * @return Boolean
	 **/
	public function SendConfirmationEmail($fields = null)
	{
		
		$config = $this->SiteConfig();
		$payload = $this->Payload();
		$to = $payload['Email'];
		
		if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
			$email = Email::create()
				->setHTMLTemplate('Email\\FormSubmission_Confirmation') 
				->setData([
					'Submission' => $this,
					'Payload' => $this->PayloadAsArray($fields)
				])
				->setTo($to)
				->setSubject($config->Title. ' ' . $this->Origin()->Title . ' form submission');

			if ($config->EmailFrom() && !empty($config->EmailFrom())) {
				$email->setFrom($config->EmailFrom());
			}

			if ($config->EmailReplyTo && !empty($config->EmailReplyTo)) {
				$email->setReplyTo($config->EmailReplyTo);
			}

			return $email->send();

		} else {
			return false;
		}
		
	}

	public function EditLink()
	{
		$admin = Injector::inst()->get(FormSubmissionAdmin::class);
		$classname = str_replace('\\', '-', $this->ClassName);
		
		return Controller::join_links(
			Director::absoluteBaseURL(),
			$admin->Link($classname),
            "EditForm",
			'field',
			$classname,
			'item',
			$this->ID,
			'edit'
		);
	}

	public function SiteConfig()
	{
		return SiteConfig::current_site_config();
	}

	/**
	 * Convert our Payload JSON to an array
	 *
	 * @return Array
	 **/
	public function Payload()
	{
		return json_decode($this->Payload, true);
	}

	/**
	 * Convert our Payload to an ArrayList
	 * We use this to prepare data for template usage
	 *
	 * @return ArrayList
	 **/
	public function PayloadAsArray($fields = null)
	{
		$array = ArrayList::create();
		$payload = $this->Payload();

		// We've explicitly stated what fields we want to use (in the form action)
		if ($fields) {
			foreach ($fields as $field) {
				$array->push(ArrayData::create([
					'Name' => $field,
					'Value' => (isset($payload[$field]) ? $payload[$field] : null)
				]));
			}
		} else {
			// Loop over *all* form field data values
			foreach ($payload as $key => $value) {
				// Make sure these values are frontend-friendly. Exclude known system values.
				if ($key !== 'SecurityID' && substr($key, 0, 7) !== "action_"){
					$array->push(ArrayData::create([
						'Name' => $key,
						'Value' => $value
					]));
				}
			}
		}
		return $array;
	}

	/**
	 * Prepare the email recipient(s)
	 * This is an override function to use page dataobject over modeladmin
	 *
	 * @return Array
	 **/
	public function EmailRecipients()
	{
		if (isset($this->Origin()->Recipients) && !is_null($this->Origin()->Recipients)) {
			return str_getcsv($this->Origin()->Recipients, ',');
		} else {
			return SiteConfig::current_site_config()->EmailRecipients();
		}
	}

}
