<?php

class teammember extends DataObject {
	public static $db = array (
		'Name'		=>	'Varchar(16)',
		'Blurb'		=>	'HTMLText'
	);
	
	public static $has_one = array (
		'Portrait'	=>	'Image'
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
	
	public function getTitle() {
		return $this->Name;
	}
}