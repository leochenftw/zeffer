<?php
	class StockistAdmin extends ModelAdmin {
		public static $managed_models = array('Stockist', 'Region','City','Suburb'); // Can manage multiple models
		public static $url_segment = 'stockists'; // Linked as /admin/products/
		public static $menu_title = 'Stockists';
		
		public function getEditForm($id = null, $fields = null) {
	        $form = parent::getEditForm($id, $fields);
	        $gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
	        
	        $gridField->getConfig()->addComponents(	
				new GridFieldOrderableRows('SortOrder')
			);
			
	        return $form;
	    }
	}