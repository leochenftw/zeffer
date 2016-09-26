<?php
class HomePage extends Page {
	
	public static $db = array(
	);
	
	public static $has_one = array(
	);
	
	public static $has_many = array(
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		return $fields;
	}
	
	public function ListCiders() {
		return Versioned::get_by_stage('Cider', 'Live');//Cider::get();
	}
	
	public function NumCiders() {
		$english = array(
			"",
			"One",
			"two",
			"Three",
			"Four",
			"Five",
			"Six",
			"Seven",
			"Eight",
			"Nine",
			"Ten",
			"Eleven",
			"Twelve",
			"Thirteen",
			"Fourteen",
			"Fifteen",
			"Sixteen",
			"Seventeen",
			"Eighteen",
			"Nineteen",
			"Twenty"
		);
		
		return $english[Cider::get()->count()];

	}
	
}
class HomePage_Controller extends Page_Controller {
	
	public static $allowed_actions = array (
	);
	
	public function init() {
		parent::init();
	}
	
	public function getTeamMembers() {
		return TeamMember::get();
	}
	
	public function getCidersPage() {
		/*
$ciders = CidersPage::get();
		
		if($ciders) {
		
		}
*/
	}
	
}
