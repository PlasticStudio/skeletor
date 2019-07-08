<?php

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\Backtrace;
use SilverStripe\Dev\Debug;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use UncleCheese\DisplayLogic\DisplayLogic;
use UncleCheese\DisplayLogic\Forms\Wrapper;

class Resource extends DataObject {

	private static $db = [
		'Type' => 'Varchar',
		'Title' => 'Varchar',
		'Content' => 'HTMLText',
		'VideoSource' => "Enum('YouTube, Vimeo')",
		'VideoURL' => 'Text',
		'ExternalLink' => 'Text',
		'SortID' => 'Int'
	];

	private static $has_one = [
		'ResourceHolderPage' => 'ResourceHolderPage',
		'HomePage' => 'HomePage',
		'ResourceImage' => Image::class,
		'ArticleLink' => 'Article',
		'RelatedPage' => SiteTree::class
	];

	private static $owns = [
		'ResourceImage'
	];

	private static $many_many = [
		'Categories' => 'ResourceCategory',
		'Tags' => 'ResourceTag'
	];

	private static $many_many_extraFields = [
        'Tags' => [
        	'TagSortOrder' => 'Int'
        ]
    ];

    private static $summary_fields = [
    	'Title' => 'Title',
    	'Type' => 'Type'
    ];

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName([
			'SortID',
			'ResourceHolderPageID',
			'HomePageID',
			'RelatedPageID'
		]);

		$fields->addFieldsToTab('Root.Main', [
			DropdownField::create(
				'Type', 
				'Type', 
				$this->ResourceHolderPage()->ResourceTypes())->setEmptyString('(Select one)'),
			TextField::create('Title', 'Title'),			
			$articleLink = 	DropdownField::create(
								'ArticleLinkID', 
								'Article', 
								Article::get()->map('ID', 'Title')
							)->setEmptyString('(Select one)'),
			$internalLink = Wrapper::create(
							TreeDropdownField::create(
								'RelatedPageID', 
								'Choose a page to link to:',
								SiteTree::class
							)
						),
			$externalLink = TextField::create(
								'ExternalLink', 
								'External link'
							)->setDescription('Include http://'),			
			$videoSource = 	OptionsetField::create(
								'VideoSource', 
								'Video source', 
								singleton('Resource')->dbObject('VideoSource')->enumValues()
							),
			$videoURL = TextField::create(
								'VideoURL', 
								'Video embed URL'
							)->setDescription('Embed link'),
			$summary = HTMLEditorField::create('Content', 'Summary'),
			$image = UploadField::create('ResourceImage', 'Image')->setFolderName('Resources')
		]);
		
		$articleLink->displayIf("Type")->isEqualTo("Article");
		$internalLink->displayIf("Type")->isEqualTo("InternalLink");
		$externalLink->displayIf("Type")->isEqualTo("ExternalLink");
		$image->displayIf("Type")->isNotEqualTo("Placeholder");
		$videoURL->displayIf("Type")->isEqualTo("Video");
		$videoSource->displayIf("Type")->isEqualTo("Video");
		$summary->displayIf("Type")->isNotEqualTo("Image");

		return $fields;
	}

	public function ShowResourceImage() {		
		if( $this->ResourceImage->exists() ) {
			return $this->ResourceImage->URL;
		} else {
			if( $this->RelatedPageID ) {
				$page = Page::get()->byID($this->RelatedPageID);
				if($page) {
					if($page->Inherited('BannerImage')->exists()) {
						return $page->Inherited('BannerImage')->URL;
					}
				}
			}
			if( $this->ArticleLinkID ) {
				$page = Page::get()->byID($this->ArticleLinkID);
				if($page) return $page->BannerImage->URL;
			}
		}
	}

	public function FirstCategory() {
		return $this->Categories()->First();
	}

	public function ShortSummary() {
		if($this->Content) {
			return DBHTMLText::create()->setValue($this->Content);
		} else {
			if( $this->RelatedPageID ) {
				$page = Page::get()->byID($this->RelatedPageID);
				if($page) return DBHTMLText::create()->setValue($page->Introduction);
			}
			if( $this->ArticleLinkID ) {
				$page = Page::get()->byID($this->ArticleLinkID);
				if($page) return DBHTMLText::create()->setValue($page->Introduction);
			}
		}
	}

	public function ShareLink() {
		return $this->ResourceHolderPage()->AbsoluteLink() . '?video=' . $this->ID;
	}

	public function ResourceLink() {
		if( $this->RelatedPageID ) {
			$page = SiteTree::get()->byID($this->RelatedPageID);
			if($page) return $page->Link();
		}
		if( $this->ArticleLinkID ) {
			$page = SiteTree::get()->byID($this->ArticleLinkID);
			if($page) return $page->Link();
		}
	}

}