<?php
class HomePage extends Page {

	public static $db = array(
        'PopupTitle'        =>  'Varchar(1024)',
        'PopupOn'           =>  'Boolean',
        'PopupURL'          =>  'Varchar(1024)',
        'TargetBlank'       =>  'Boolean',
        'NotificationMode'  =>  'Boolean'
	);

	public static $has_one = array(
        'PopupAds'      =>  'Image'
	);

	public static $has_many = array(
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
        $fields->addFieldsToTab(
            'Root.PopUp',
            array(
                TextField::create(
                    'PopupTitle',
                    'Popup title'
                ),
                CheckboxField::create(
                    'PopupOn',
                    'Turn on pop-up'
                ),
                TextField::create(
                    'PopupURL',
                    'Pop-up links to'
                ),
                CheckboxField::create(
                    'TargetBlank',
                    'Pop-up opens on a new tab'
                ),
                UploadField::create(
                    'PopupAds',
                    'Popup image'
                ),
                CheckboxField::create(
                    'NotificationMode',
                    'Show as a notification bar'
                )
            )
        );
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
