<?php
class StoryPage extends Page {
	
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
	
}
class StoryPage_Controller extends Page_Controller {
	
	public static $allowed_actions = array (
	);
	
	public function init() {
		parent::init();
	}
	
}
