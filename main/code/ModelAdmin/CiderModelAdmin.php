<?php
/**
 * @file TeammemberModelAdmin.php
 *
 * Left-hand-side tab : Admin Example
 * */

class CiderModelAdmin extends ModelAdmin {
	public static $managed_models = array('Cider');
	public static $url_segment = 'ciders';
	public static $menu_title = 'Ciders';
	public static $menu_priority = 100;
	
	public function getEditForm($id = null, $fields = null) {
		$form = parent::getEditForm($id, $fields);
		$gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
	        
		$gridField->getConfig()
			->removeComponentsByType('GridFieldDetailForm')
			->addComponents(	
				new PublishingForm()
			);
		return $form;
	}
}