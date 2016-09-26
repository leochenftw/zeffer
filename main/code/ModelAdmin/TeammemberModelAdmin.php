<?php
/**
 * @file TeammemberModelAdmin.php
 *
 * Left-hand-side tab : Admin Example
 * */

class TeammemberModelAdmin extends ModelAdmin {
	public static $managed_models = array('Teammember');
	public static $url_segment = 'team-members';
	public static $menu_title = 'Team Members';
	
	public function getEditForm($id = null, $fields = null) {
		$form = parent::getEditForm($id, $fields);
		
		return $form;
	}
}