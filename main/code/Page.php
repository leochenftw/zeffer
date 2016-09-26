<?php
class Page extends SiteTree {

	public static $db = array(
		'OGTitle' => 'Varchar(255)',
		'OGDescription' => 'Varchar(1024)'
	);

	public static $has_one = array(
		'OGImage' => 'Image'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab("Root.OpenGraph", new TextField('OGTitle', 'Title'));
		$fields->addFieldToTab("Root.OpenGraph", new TextField('OGDescription', 'Description'));
		$fields->addFieldToTab("Root.OpenGraph", new UploadField('OGImage', 'Image (min 200px X 200px)'));
		return $fields;
	}
	
	public function onAfterWrite() {
		parent::onAfterWrite();
		
		$this->createCSS();
		$this->createJS();
		
		$template = ROOT . 'themes/' . SiteConfig::current_site_config()->Theme . '/templates/Layout/' . get_class($this) . '.ss';
		
		if(!file_exists($template)) {
			try {
				$this->__writeFILE($template, '');
			} catch(Exception $e) {
				user_error($e, E_USER_WARNING);
			}
		}
	}
	
	public function createCSS() {
		$dir = 'themes/' . SiteConfig::current_site_config()->Theme . '/scss/';
		$scss = strtolower(get_class($this)) . '.scss';
		$file = ROOT . $dir . $scss;
		
		// generate scss file.
		if(!file_exists($file)) {
			try {
				$contents = $this->__readFILE(ROOT . $dir . '.bootstrap.scss');
				$this->__writeFILE($file, $contents);
			} catch(Exception $e) {
				user_error($e, E_USER_WARNING);
			}
		}
	}
	
	public function createJS() {
		$dir = 'themes/' . SiteConfig::current_site_config()->Theme . '/js/';
		$js = strtolower(get_class($this)) . '.js';
		$file = ROOT . $dir . 'pagetypes/' . $js;
		
		// generate js file.
		if(!file_exists($file)) {
			try {				
				$contents = $this->__readFILE(ROOT . $dir . 'pagetypes/.bootstrap.js');
				
				// open config & add to new file.
				$require = $this->__readFILE(ROOT . $dir . 'config.js');
				
				$this->__writeFILE($file, str_replace('pagename', strtolower(get_class($this)) . ' page', str_replace('lib', '../lib', $require)) . $contents);
				
			} catch(Exception $e) {
				user_error($e, E_USER_WARNING);
			}
		} else {
			$contents = $this->__readFILE($file);
			
			$require = $this->__readFILE(ROOT . $dir . 'config.js');
			$this->__writeFILE($file, str_replace('pagename', strtolower(get_class($this)) . ' page', preg_replace('/require\.config\(.*\);/', str_replace('lib', '../lib', $require), $contents)));
		}
		
		// update Gruntfile.
		$this->updateGruntFile(ROOT . $dir . 'pagetypes/');	
	}
	
	private function updateGruntfile($directory) {
		$files = array();
		
		foreach (new DirectoryIterator($directory) as $fileInfo) {
		    if($fileInfo->isDot() || substr($fileInfo->getFilename(), 0, 1) === '.') continue;
		    array_push($files, array('name' => 'pagetypes/' . $fileInfo->getBasename('.js')));
		}
		
		$files = json_encode($files);
		
		try {			
			$contents = $this->__readFILE(ROOT . 'themes/' . SiteConfig::current_site_config()->Theme . '/.Gruntfile-bolierplate.js');
			
			$this->__writeFILE(ROOT . 'themes/' . SiteConfig::current_site_config()->Theme . '/Gruntfile.js', str_replace('modules: []', 'modules: ' . $files, $contents));
		} catch(Exception $e) {
			user_error($e, E_USER_WARNING);
		}
	}
	
	private function __readFILE($file) {
		$handle = fopen($file, 'r');
		$contents = fread($handle, filesize($file));
		fclose($handle);
		
		return $contents;
	}
	
	private function __writeFILE($file, $contents) {
		$handle = fopen($file, 'w');
		fwrite($handle, $contents);
		fclose($handle);
	}
}

class Page_Controller extends ContentController {
	
	public static $allowed_actions = array (
		'rebuild',
		'stockists'
	);
	
	protected function getGACode() {
		$GA = SiteConfig::current_site_config()->GoogleAnalyticsCode;
		
		if($GA) {
			return $GA;
		}
		return false;
	}
	
	protected function getSiteVersion() {
		if (defined('SITE_VERSION')) {
			return SITE_VERSION;
		}
		return false;
	}
	
	protected function getSessionID() {
		return session_id();
	}
	
	protected function getHTTPProtocol() {
		$protocol = 'http';
		if (isset($_SERVER['SCRIPT_URI']) && substr($_SERVER['SCRIPT_URI'], 0, 5) == 'https') {
			$protocol = 'https';
		} else if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
			$protocol = 'https';
		}
		return $protocol;
	}
	
	protected function getCurrentPageURL() {
		return $this->getHTTPProtocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	public function MetaTags($includeTitle = true) {
		$tags = "";
		if($includeTitle === true || $includeTitle == 'true') {
			$tags .= "<title>" . Convert::raw2xml(($this->MetaTitle)
			? $this->MetaTitle
			: $this->Title) . "</title>\n";
		}
		
		$charset = ContentNegotiator::get_encoding();
		$tags .= "<meta http-equiv=\"Content-type\" content=\"text/html; charset=$charset\" />\n";
		if($this->MetaKeywords) {
			$tags .= "<meta name=\"keywords\" content=\"" . Convert::raw2att($this->MetaKeywords) . "\" />\n";
		}
		if($this->MetaDescription) {
			$tags .= "<meta name=\"description\" content=\"" . Convert::raw2att($this->MetaDescription) . "\" />\n";
		}
		if($this->ExtraMeta) { 
			$tags .= $this->ExtraMeta . "\n";
		} 
		
		if($this->URLSegment == 'home' && SiteConfig::current_site_config()->GoogleSiteVerificationCode) {
			$tags .= "<meta name=\"google-site-verification\" content=\"" . SiteConfig::current_site_config()->GoogleSiteVerificationCode . "\" />\n";
		}
		
		if(defined('SS_ENVIRONMENT_TYPE') && SS_ENVIRONMENT_TYPE != 'live') {
			$tags .= "<meta name=\"robots\" content=\"noindex, nofollow, noarchive\" />\n";
		}
		
		$this->extend('MetaTags', $tags);
		
		return $tags;
	}
	
	protected function getCurrentOGTitle() {
		if ($this->OGTitle)
			return $this->OGTitle;
		if (SiteConfig::current_site_config()->OGTitle)
			return SiteConfig::current_site_config()->OGTitle;
		return false;
	}
	
	protected function getCurrentOGDescription() {
		if ($this->OGDescription)
			return $this->OGDescription;
		if (SiteConfig::current_site_config()->OGDescription)
			return SiteConfig::current_site_config()->OGDescription;
		return false;
	}
	
	protected function getCurrentOGImageURL() {
		if ($this->OGImageID)
			return $this->getHTTPProtocol().'://'.$_SERVER['HTTP_HOST'].$this->OGImage()->URL;
		if (SiteConfig::current_site_config()->OGImageID)
			return $this->getHTTPProtocol().'://'.$_SERVER['HTTP_HOST'].SiteConfig::current_site_config()->OGImage()->URL;
		return false;
	}
	
	protected function getRequireJS() {
		$version = (defined('SITE_VERSION') ? SITE_VERSION : rand());
		$script = '<script type="text/javascript">var require = { urlArgs : "v=' . $version . '" };</script>';

		if(!$this->isMobile()) {
			$script .= '<script src="' . $this->ThemeDir() . '/js/lib/require.js" data-main="' . $this->ThemeDir() . '/js/pagetypes/' . strtolower($this->Page(0)->ClassName) . '"></script>';
		} else {
			$script .= '<script src="' . $this->ThemeDir() . '/js/lib/require.js" data-main="' . $this->ThemeDir() . '/js/mobile/mobile"></script>';
		}
		
		return $script;
	}
	
	protected function getCSS() {
		$css = strtolower($this->data()->class);
		if(!file_exists(ROOT . $this->ThemeDir() . '/css/' . $css . '.css')) {
			$css = 'styles';
		}
		
		$version = (defined('SITE_VERSION') ? SITE_VERSION : rand());
		
		return sprintf('<link rel="stylesheet" href="%s" />', $this->ThemeDir() . '/css/' . $css . '.css?v=' . $version);
	}
	
	public function rebuild() {
		$this->createCSS();
		$this->createJS();
	}
	
	public function isMobile() {
		$mobi = new Mobile_Detect();
		return $mobi->isMobile() && !$mobi->isTablet();
	}
	
	public function isTablet() {
		$mobi = new Mobile_Detect();
		return $mobi->isTablet();
	}
	
	public function stockists() {
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
		return json_encode($data);
	}
	
	public function getCities() {
		return Region::get();
	}
}