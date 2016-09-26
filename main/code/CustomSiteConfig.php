<?php 
class CustomSiteConfig extends DataExtension { 
	
	public static $db = array(
		'OGTitle' => 'Varchar(255)',
		'OGDescription' => 'Varchar(1024)',
		'GoogleSiteVerificationCode' => 'Varchar(128)',
		'GoogleAnalyticsCode' => 'Varchar(20)'
	);

	public static $has_one = array(
		'OGImage' => 'Image'
	);
	
	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab("Root.OpenGraph", new TextField('OGTitle', 'Open Graph Title'));
		$fields->addFieldToTab("Root.OpenGraph", new TextField('OGDescription', 'Open Graph Description'));
		$fields->addFieldToTab("Root.OpenGraph", new UploadField('OGImage', 'Open Graph Image (min 200px X 200px)'));
		
		$fields->addFieldToTab("Root.Google", new TextField('GoogleSiteVerificationCode', 'Google Site Verification Code'));
		$fields->addFieldToTab("Root.Google", new TextField('GoogleAnalyticsCode', 'Google Analytics Code'));
	}
	
}
