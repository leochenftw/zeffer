<?php
	class Region extends DataObject {
		public static $db = array(
			'Name' => 'Varchar(100)',
			'SortOrder' => 'Int'
		);
		
		public static $has_many = array(
			'Cities' => 'City'
		);
		
		public static $default_sort = 'SortOrder';
		
		public function getCMSFields() {
			$fields = parent::getCMSFields();
			$fields->removeByName('Cities');
			
			$fields->fieldByName('Root.Main.Name')->setTitle('Region Name');
			$fields->removeByName('SortOrder');
			
			$fields->addFieldtoTab('Root.Main', new GridField('Cities', 'Cities', $this->Cities(), GridFieldConfig_RelationEditor::create()));
			
			return $fields;
		}
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
	}
	
	class City extends DataObject {
		public static $db = array(
			'Name' => 'Varchar(100)',
			'SortOrder' => 'Int'
		);
		
		public static $has_one = array(
			'Region' => 'Region'
		);
		
		public static $summary_fields = array(
			'Name',
			'Region.Name'
		); 
		
		public static $has_many = array(
			'Suburbs' => 'Suburb',
			'Stockists' => 'Stockist'
		);
		
		public static $default_sort = 'SortOrder';
		
		public function getTitle() {
			return $this->Name;
		}
		
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
		
		public function getCMSFields() {
			$fields = parent::getCMSFields();
			//$fields->removeByName('RegionID');
			$fields->removeByName('Suburbs');
			$fields->removeByName('Stockists');
			$fields->removeByName('SortOrder');
			
			$fields->fieldByName('Root.Main.Name')->setTitle('City Name');
			
			
			//$fields->addFieldtoTab('Root.Main', new GridField('Suburbs', 'Suburbs', $this->Suburbs(), GridFieldConfig_RelationEditor::create()));
			
			return $fields;
		}
		
		public function hasStockists() {
			return Stockist::get()->filter(array('CityID' => $this->ID));
		}
	}
	
	class Suburb extends DataObject {
		public static $db = array(
			'Name' => 'Varchar(100)',
			'SortOrder' => 'Int'
		);
		
		public static $has_one = array(
			'City' => 'City'
		);
		
		public static $has_many = array(
			'Stockists' => 'Stockist'
		);
		
		public static $summary_fields = array(
			'Name',
			'City.Name'
		);
		
		public static $searchable_fields = array(
			'Name',
			'CityID' => 'City'
		);
		
		public static $default_sort = 'SortOrder';
		
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
		
		public function getCMSFields() {
			$fields = parent::getCMSFields();
			//$fields->removeByName('CityID');
			$fields->removeByName('Stockists');
			$fields->removeByName('SortOrder');
			
			return $fields;
		}
		
		public function hasStockists() {
			return Stockist::get()->filter(array('SuburbID' => $this->ID));
		}
	}

	class Stockist extends DataObject {
		public static $db = array(
			'Name' => 'Varchar(30)',
			'Address' => 'Varchar(100)',
			'Type' => 'Enum("Grocery,Off Trade,Trade")',
			'SearchString' => 'Varchar(255)',
			'SortOrder' => 'Int'
		);
		
		public static $has_one = array(
			'City' => 'City',
			'Suburb' => 'Suburb'
		);
		
		public static $summary_fields = array(
			'Name',
			'City' => 'City.Name',
			'Suburb' => 'Suburb.Name'
		);
		
		public static $allowed_actions = array(
			'all' => 'getAll'
		);
		
		public static $default_sort = 'SortOrder';
		
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
		
		protected function getAll() {
			json_encode(Stockist::get()->toArray());
		}
		
		public function getCityName() {
/* 			if($this->City) { */
			return $this->City()->ID;
/* 			} */
/* 			return false; */
		}
		
		public function onBeforeWrite() {
			parent::onBeforeWrite();
			
			$dir = ROOT . 'themes/' . SiteConfig::current_site_config()->Theme . '/json/';
			$data = array();
		
			foreach(Region::get() as $region) {
				
				foreach(City::get()->filter(array('RegionID' => $region->ID)) as $city) {
					
					$data[$city->Name] = array();
					
					foreach(Stockist::get()->filter(array('CityID' => $city->ID))->sort('SuburbID') as $stockist) {
						$suburb = 'default';
						
						if($stockist->suburb()->ID) {
							$suburb = $stockist->suburb()->Name;
						}
						
						$current = array(
							'Name' => $stockist->Name,
							'Address' => $stockist->Address,
							'Lat' => (float)$stockist->GMapLat,
							'Lon' => (float)$stockist->GMapLon,
							'City' => $stockist->City()->Name,
							'Suburb' => $suburb
						);
						
						
						if(array_key_exists($suburb, $data[$city->Name])) {
							array_push($data[$city->Name][$suburb]['Data'], $current);
						} else {
							$data[$city->Name][$suburb] = array();
							
							if($suburb != 'default') {
								$data[$city->Name][$suburb]['Lat'] = (float)$stockist->suburb()->GMapLat;
								$data[$city->Name][$suburb]['Lon'] = (float)$stockist->suburb()->GMapLon;
							} else {
								$data[$city->Name][$suburb]['Lat'] = (float)$city->GMapLat;
								$data[$city->Name][$suburb]['Lon'] = (float)$city->GMapLon;
							}
							
							$data[$city->Name][$suburb]['Data'] = array();
							array_push($data[$city->Name][$suburb]['Data'], $current);
						}
					}
				}
			}
			
			try {
				$handle = fopen($dir . 'stockists.json', 'w');
				fwrite($handle, json_encode($data));
				fclose($handle);
			} catch(Exception $e) {
				user_error($e, E_USER_WARNING);
			}
		}
		
		public function getCMSFields() {
			$fields = parent::getCMSFields();
			$fields->removeByName('SortOrder');
			$regions = array();
			foreach(Region::get() as $region) {
				$cities = array();
				foreach(City::get()->filter(array('RegionID' => $region->ID)) as $city) {
					$cities[$city->ID] = $city->Name;
				}
				$regions[$region->Name] = $cities;
			}

			$cities = array();
			foreach(City::get() as $city) {
				$suburbs = array();
				foreach(Suburb::get()->filter(array('CityID' => $city->ID)) as $suburb) {
					$suburbs[$suburb->ID] = $suburb->Name;
				}
				$cities[$city->Name] = $suburbs;
			}
			
			$fields->addFieldsToTab('Root.Main', array(
				$cityDD = new GroupedDropdownField('CityID', 'City', $regions),
				$subDD = new GroupedDropdownField('SuburbID', 'Suburb', $cities),
				$search = new TextField('SearchString', 'Search Text')
			));
			
			$cityDD->setHasEmptyDefault(true);
			$subDD->setHasEmptyDefault(true);
			
			$cityDD->setEmptyString('Select City');
			$subDD->setEmptyString('Select Optional Suburb');
			
			$fields->renameField('Name', 'Stockist / Store Name');
			$name = $fields->fieldByName('Root.Main.Name');
			$name->setRightTitle('The name of the stockist - e.g. New World. Don\'t include the location such as "New World Remuera"');
			
			$fields->fieldByName('Root.Main.Type')->setHasEmptyDefault(false);
			
			$search->setRightTitle('Text displayed to user in the search box. Leave empty for "Name, Suburb, City"');
			
			return $fields;	
		}
	}