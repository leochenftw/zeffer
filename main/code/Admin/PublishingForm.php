<?php
/**
 * @file PublishingForm.php
 *
 * Action Form : Approval Action Form
 * */

class PublishingForm extends GridFieldDetailForm {
	
}

class PublishingForm_ItemRequest extends GridFieldDetailForm_ItemRequest {
	
	public static $allowed_actions = array(
		'edit',
		'view',
		'publish',
		'ItemEditForm'
	);
	
	public function ItemEditForm() {
		$form = parent::ItemEditForm();
		
		if ($form instanceof Form) {
			$actions = $form->Actions();
			
			
			if ($this->record->ID) {
				$actions = new FieldList();
				//$btnSave = FormAction::create('doSave', 'Save');
				$btnSave = FormAction::create('doPublish', 'Save');
				$btnSave->addExtraClass('ss-ui-action-constructive');
				$actions->push($btnSave);
				
				/*if ($this->record->isPublished()) {
					$btnUnpublish = FormAction::create('undoPublish', 'Unpublish');
					$actions->push($btnUnpublish);
				}else{
					$btnPublish = FormAction::create('doPublish', 'Save & Publish');
					$btnPublish->addExtraClass('ss-ui-action-constructive');
					$actions->push($btnPublish);
				}*/
				
				$btnDelete = FormAction::create('doDelete', 'Delete');
				$btnDelete->addExtraClass('ss-ui-action-destructive');
				$actions->push($btnDelete);
				
			}
			$form->setActions($actions);
		}
		
		return $form;
	}
	
	public function doDelete( $data, $form ) {
		$this->record->doUnpublish();
		parent::doDelete($data, $form);
	}
	
	public function undoPublish($data, $form) {
		$this->record->doUnpublish();
		$controller = Controller::curr();
		return $this->edit($controller->getRequest());
	}
	
	public function doPublish($data, $form) {
		$record = $this->record;
		$form->saveInto($record);
		$record->write();
		$record->publish('Stage','Live');
		$controller = Controller::curr();
		$form->sessionMessage('Changes have been saved and published', 'good', false);
		return $this->edit($controller->getRequest());
	}
}
