<?php

namespace Skeletor\Pages;

use Page;
use Skeletor\DataObjects\FormSubmission;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

class ContactPage extends Page {
	
	private static $description = 'Standard page with a contact form';
	private static $icon_class = 'font-icon-p-book';
	private static $table_name = 'ContactPage';

    private static $db = [
        'Recipients'		=> 'Varchar(1024)',
		'FromEmail'			=> 'Varchar(255)',
		'FromName'			=> 'Varchar(255)',
		'SendCustomerEmail'	=> 'Boolean(false)',
		'SuccessMessage'	=> 'HTMLText'
    ];

    private static $has_many = [
        'Submissions' => FormSubmission::class
    ];

	public function getCMSFields()
	{
        $fields = parent::getCMSFields();
		
		$fields->addFieldToTab(
			'Root.Delivery', 
			TextField::create(
				'Recipients', 
				'Recipients'
			)->setDescription('Comma-separated list of email addresses to send this form to')
		);
		$fields->addFieldToTab('Root.Delivery', TextField::create('FromEmail', '"From" & "Reply-to" email')->setDescription('Displayed in form submission email.')
		);
		$fields->addFieldToTab(
			'Root.Delivery', 
			TextField::create(
				'FromName', 
				'From name'
			)->setDescription('Displayed in form submission email. Defaults to "'.SiteConfig::current_site_config()->Title.' contact form."')
		);
		$fields->addFieldToTab('Root.Delivery', CheckboxField::create('SendCustomerEmail', 'Send confirmation email to customer?'));

        $fields->addFieldToTab(
			'Root.Submissions', 
			GridField::create(
				'Submissions',
				'Submissions',
				$this->Submissions(),
				GridFieldConfig_RecordEditor::create()
			)
		);
		
		$fields->addFieldToTab('Root.Delivery', HTMLEditorField::create('SuccessMessage', 'Message to show after form submission'));
		
        return $fields;		
	}
	
}