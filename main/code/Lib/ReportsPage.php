<?php

class ReportsData extends DataObject {
	
	static $db = array (
		'Name' => 'Varchar(255)',
		'Sql' => 'Text'
	);
	
	public static $has_one = array(
		'ReportsPage' => 'ReportsPage'
	);
	
	static $searchable_fields = array (
		'Name'
	);
	
	static $summary_fields = array(
		'ID',
		'Name'
	);
	
	public function getCMSFields() {
		return new FieldList(
			new TextField('Name', 'Name'),
			new TextareaField("Sql", "Sql"),
			new LiteralField('l1', '<h2>Parameters</h2>'),
			new LiteralField('l2', "<pre>
				{DATE1} - yyyy-mm-dd hh:nn:ss
				{DATE2} - yyyy-mm-dd hh:nn:ss
				{OTHER} - field1 IN (111,222,333)
				
				e.g.
				SELECT COUNT(*) AS cnt 
				FROM Table1
				WHERE date BETWEEN '{DATE1}' AND '{DATE2}'
					AND {OTHER}
				</pre>")
		);
	}
	
}

class ReportsPage extends Page {
	
	public static $db = array(
		'HtmlContent' => 'Text'
	);
	
	public static $has_many = array(
		'ReportsData' => 'ReportsData'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab("Root", "Content");
		$fields->addFieldToTab("Root.HtmlContent", new TextareaField('HtmlContent', 'HtmlContent'));
		$gridField = new GridField('ReportsData', 'ReportsData', $this->ReportsData(), GridFieldConfig_RecordEditor::create());
		$fields->addFieldToTab('Root.ReportsData', $gridField);
		return $fields;
	}
	
}
class ReportsPage_Controller extends Page_Controller {
	
	protected $report_data = null;
	
	public static $allowed_actions = array (
	);
	
	public function init() {
		parent::init();
	}
	
	public function index() {
		//security
		$html_content = $this->HtmlContent;
		$html_content = str_replace('$Host', $this->getHTTPProtocol().'://'.$_SERVER['HTTP_HOST'].'/', $html_content);
		if (!Permission::check('ADMIN')) {
			$content_data = 'you are not allowed to access this part of the cms';
			$html_content = str_replace('$Layout', $content_data, $html_content);
			return $html_content;
		}
		//export
		$csv = (isset($_REQUEST['csv'])?true:false);
		$report_name = (isset($_REQUEST['report'])?convert::raw2sql($_REQUEST['report']):'');
		$par_date1 = (isset($_REQUEST['date1'])?convert::raw2sql($_REQUEST['date1']):'');
		$par_date2 = (isset($_REQUEST['date2'])?convert::raw2sql($_REQUEST['date2']):'');
		$par_other = (isset($_REQUEST['other'])?convert::raw2sql($_REQUEST['other']):'');
		
		$data = '';
		$data_exist = false;
		if ($report_name != '') {
			$this->report_data = DataObject::get_one('ReportsData', "Name = '".$report_name."'");
			if ($this->report_data) {
				//apply filters to sql
				$sql = $this->report_data->Sql;
				if ($par_date1 != '') {
					$date1 = date('Y-m-d H:i:s', strtotime($par_date1));
					$sql = str_replace('{DATE1}', $date1, $sql);
				}
				if ($par_date2 != '') {
					$date2 = date('Y-m-d H:i:s', strtotime($par_date2));
					$sql = str_replace('{DATE2}', $date2, $sql);
				}
				if ($par_other != '') {
					$sql = str_replace('{OTHER}', $par_other, $sql);
				}
				$data_array = $this->getDataFromQuery($sql);
				$data = $this->exportData($data_array, $csv);
				if ($data != '')
					$data_exist = true;
			}
		}
		if ($csv && $data_exist) {
			$project_name = SiteConfig::current_site_config()->Title;
			$project_name = strtolower(trim($project_name));
			$project_name = str_replace(' ', '', $project_name);
			header("Content-type: text/csv");
			header("Cache-Control: no-store, no-cache");
			header('Content-Disposition: attachment; filename="'.$project_name.'_'.$report_name.'_'.date('YmdHis').'.csv"');
			echo $data;
		} else {
			$content_data = '<h2>'.SiteConfig::current_site_config()->Title.' - Reports</h2>';
			$content_data .= '<b>Reports</b>';
			$content_data .= '<form method="POST">';
			$content_data .= $this->getReports();
			$content_data .= $this->getParams();
			$content_data .= '<input type="submit" value="Submit" name="btnSubmit">';
			$content_data .= '</form>';
			if (isset($_REQUEST['report']) && $data_exist) {
				$content_data .= '<br>';
				$content_data .= '<b>Results</b> - <a href="'.$this->getCurrentPageUrl().'?report='.$report_name.'&csv=true&date1='.$_REQUEST['date1'].'&date2='.$_REQUEST['date2'].'&other='.$_REQUEST['other'].'">export csv</a>';
				$content_data .= $data;
			}
			$html_content = str_replace('$Layout', $content_data, $html_content);
			return $html_content;
		}
		
	}
	
	protected function getDataFromQuery($sql='') {
		$array = array();
		$result = false;
		if (strpos(strtoupper($sql), 'UPDATE') !== false) {
			return array();
		}
		if (strpos(strtoupper($sql), 'DELETE') !== false) {
			return array();
		}
		if (strpos(strtoupper($sql), 'INSERT') !== false) {
			return array();
		}
		try {
			$result = DB::Query($sql);
			if (!$result)
				return array();
			foreach($result as $row) {
				$row_array = array();
				foreach($row as $var => $val) {
					$row_array[$var] = $val;
				}
				$array[] = $row_array;
			}
			return $array;
		} catch(Exception $e) {
			
		}
		return array();
	}
	
	protected function getReports() {
		$reports_data = DataObject::get('ReportsData');
		if (!$reports_data) {
			return '<select name="report"><option>Please choose</select>';
		}
		
		$checkbox = '';
		$checkbox .= '<select name="report" onchange="this.form.submit();"><option>Please choose';
		foreach($reports_data as $data) {
			$data->Name;
			$checkbox .= '<option '.(isset($_REQUEST['report']) && $_REQUEST['report'] == $data->Name?'selected="selected"':'').'>'.$data->Name;
		}
		$checkbox .= '</select>';
		return $checkbox;
	}
	
	protected function getParams() {
		$params = '<p>';
		$params .= 'Date 1: <input type="text" name="date1" value="'.(isset($_REQUEST['date1'])?$_REQUEST['date1']:'').'"><br>';
		$params .= 'Date 2: <input type="text" name="date2" value="'.(isset($_REQUEST['date2'])?$_REQUEST['date2']:'').'"><br>';
		$params .= 'Other: <input type="text" name="other" value="'.(isset($_REQUEST['other'])?$_REQUEST['other']:'').'"><br>';
		$params .= '</p>';
		return $params;
		
		return '<input type="text" name="date1">';
	}
	
	protected function exportData($data_array=array(), $csv=false) {

		if (count($data_array) <= 0)
			return '<br><br>no results';

		$data_string = '';

		//get field count
		$column_count = count($data_array[0]);

		//get column names
		$idx = 0;
		if ($csv) {
		} else {
			$data_string .= "\n".'<table><tr class="header">';
		}
		foreach($data_array[0] as $var => $val) {
			$idx++;
			if ($csv) {
				if ($idx > 1)
					$data_string .= ',';
				if ($idx == 1)
					$data_string .= '"nr",';
				$data_string .= '"'.$var.'"';
			} else {
				if ($idx == 1)
					$data_string .= '<td><strong>nr</strong></td>';
				$data_string .= '<td><strong>'.$var.'</strong></td>';
			}
		}
		if ($csv) {
			$data_string .= "\n";
		} else {
			$data_string .= '</tr>'."\n";
		}

		//loop through rows
		$idx = 0;
		foreach($data_array as $data_row) {
			$idx++;
			if ($csv) {
			} else {
				$data_string .= '<tr class="datarow '.(($idx%2)==0?'even':'odd').'">';
			}
			$idx2 = 0;
			foreach($data_row as $var => $val) {
				$idx2++;
				if ($csv) {
					if ($idx2 > 1)
						$data_string .= ',';
					if ($idx2 == 1)
						$data_string .= '"'.$idx.'",';
					$data_string .= '"'.$val.'"';
				} else {
					if ($idx2 == 1)
						$data_string .= '<td>'.$idx.'</td>';
					$data_string .= '<td>'.$val.'</td>';
				}
			}
			if ($csv) {
				$data_string .= "\n";
			} else {
				$data_string .= '</tr>'."\n";
			}
		}
		if ($csv) {
		} else {
			$data_string .= '</table>'."\n";
		}
		return $data_string;
	}
	
}
