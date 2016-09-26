<?php
class Cider extends Page {
	
	//private static $can_be_root = false;
	
	public static $db = array(
		'ReserveRange' => 'Boolean',
		'Subtitle' => 'Varchar(128)',
		'See' => 'Varchar(128)',
		'Smell' => 'Varchar(128)',
		'Taste' => 'Varchar(128)',
		'TryMeWith' => 'Varchar(128)',
		'Dryness' => 'Decimal',
		'Tannin' => 'Decimal',
		'Alchohol' => 'Decimal',
		'SoldOut' => 'Boolean',
		'Availabilities' => 'Varchar(16)',
		'CiderColour' => 'Varchar(7)',
		'ProductStyle' => 'Varchar(100)',
		'ProudctVintage' => 'Varchar(16)'
	);
	
	function canView($member = false) {
		return true;
	}
	function canEdit($member = false) {
		return true;
	}
	function canDelete($member = false) {
		return true;
	}
	function canCreate($member = false) {
		return true;
	}
	function canPublish($member = false) {
		return true;
	}
	
	public static $has_one = array(
		'TitleImage'		=> 'Image',
		'ProductImage'		=> 'Image',
		'BorderImage'		=> 'Image',
		'ProductSignature'	=> 'Image'
	);
	
	public static $has_many = array(
		'CrossImageFiles'	=> 'Image'
	);
	
	public static $defaults = array(
		'ParentID'	=>	18
	);
	
	public static $summary_fields = array (
		'Sort', 'Title'
	);
	
	public static $field_labels = array (
		'Sort'		=>	'Order in list'
	);
	
	public function getCMSFields() {
		Requirements::javascript('themes/default/js/views/CMSUI.js');
		$fields = parent::getCMSFields();
		$fields->removeByName('URLSegment');
		$fields->removeByName('MenuTitle');
		$fields->addFieldToTab('Root.Main', new LiteralField('js', '<script>cider_cms_func();</script>'));
		$fields->addFieldToTab('Root.Main', new CheckboxField('ReserveRange','is Reserve Range?'),'Title');
		$fields->addFieldToTab('Root.Main', new CheckboxField('SoldOut','Sold out'),'Title');
		$fields->addFieldToTab('Root.Main', new UploadField('TitleImage', 'Title Image'),'Content');
		$fields->addFieldToTab('Root.Main', new UploadField('BorderImage', 'Border Image'),'Content');
		$fields->addFieldToTab('Root.Main', new TextField('Subtitle'),'Content');
		$fields->addFieldToTab('Root.Main', new UploadField('ProductImage', 'Product Image'),'Content');
		
		$fields->insertBefore(new Tab('ProductDetails','Product Details'),'OpenGraph');
		$fields->addFieldToTab('Root.ProductDetails',new TextField('ProductStyle', 'Style'));
		$fields->addFieldToTab('Root.ProductDetails',new TextField('ProudctVintage', 'Vintage'));
		$fields->addFieldToTab('Root.ProductDetails', new UploadField('ProductSignature', 'Signature'));
		
		$fields->addFieldToTab('Root.ProductDetails',new TextField('See'));
		$fields->addFieldToTab('Root.ProductDetails',new TextField('Smell'));
		$fields->addFieldToTab('Root.ProductDetails',new TextField('Taste'));
		$fields->addFieldToTab('Root.ProductDetails',new TextField('TryMeWith','Try me with'));
		$fields->addFieldToTab('Root.ProductDetails',new TextField('Dryness'));
		$fields->addFieldToTab('Root.ProductDetails',new TextField('Tannin'));
		$fields->addFieldToTab('Root.ProductDetails',new TextField('Alchohol','Alchohol %'));
		$fields->addFieldToTab('Root.ProductDetails', new CheckboxSetField('Availabilities', "Availabilities", array( "Keg" => "Keg", "500ML" => "500ML", "330ML" => "330ML")));
		$fields->addFieldToTab('Root.ProductDetails', new TextField('CiderColour','Cider Colour'));
		$fields->addFieldToTab('Root.ProductDetails', new UploadField('CrossImageFiles', 'Cross Image Files'));
		
		
		$fields->addFieldToTab('Root.Main', $sortField = new TextField('Sort','Order in list'));
		
		if ($ciders = Cider::get()->sort('Sort', 'DESC')) {
			$next_available = ((int) $ciders->first()->Sort) + 1;
			$sortField->setValue($next_available);
		}
		
		
		$sortField->setDescription('Smaller the number is, higher in the list');
		
		//$cifUploader->setAllowedMaxFileNumber(5);
		
		return $fields;
	}
	
	private function renderAvailability() {
		$avail_array = explode(',', $this->Availabilities);
		$html = '';
		foreach ($avail_array as $avail) {
			$html .= '<div class="rect">';
			$html .= $avail;
			$html .= '</div>';
		}
		return $html;
	}
	
	private function CrossJson() {
		$crosses = $this->CrossImageFiles();
		$arr = array();
		foreach ($crosses as $cross) {
			$arr[] = '/assets/Uploads/' . str_replace('assets/','',$cross->getFilename());
		}
		
		$urls = implode('+',$arr);
		return $urls;
	}
	
	public function forTemplate() {
		$data = array(
			'isReserved' => $this->ReserveRange,
			'Title' => $this->Title,
			'TitleImage' => $this->TitleImage(),
			'Subtitle' => $this->Subtitle,
			'Content' => $this->Content,
			'Image' => $this->ProductImage(),
			'See' => $this->See,
			'Smell' => $this->Smell,
			'Taste' => $this->Taste,
			'TryMeWith' => $this->TryMeWith,
			'Dryness' => $this->Dryness,
			'Tannin' => $this->Tannin,
			'Alchohol' => number_format($this->Alchohol, 1),
			'SoldOut' => ($this->SoldOut?' is-sold-out':''),
			'Availabilities' => $this->renderAvailability(),
			'CiderColour' => $this->CiderColour,
			'ProductStyle' => $this->ProductStyle,
			'ProudctVintage' => $this->ProudctVintage,
			'ProductSignature' => $this->ProductSignature(),
			'CrossJson' => $this->CrossJson(),
			'Id' => $this->IdHash()
		);
		return $this->customise(new ArrayData($data))->renderWith(array($this->ReserveRange?'ReservedCiderItem':'CiderItem'));
	}
	
	public function IdHash() {
		return preg_replace('/[^A-Za-z0-9]+/','-', strtolower($this->Title));
	}
	
}

class Cider_Controller extends Page_Controller {
	
	public static $allowed_actions = array (
	);
	
	public function init() {
		parent::init();
	}
	
}
