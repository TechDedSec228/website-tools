<?PHP

class rep_process {
  private $search_terms = '';
  private $email_ttl = '';
  
  function __construct() {
		
	$this->search_terms = explode("\n",$_POST['keywords']);
	$this->email_ttl = 'CM Goog DRANK Tool Results '.date("m-d-Y");
	
	// check for exact search settings and if enabled encapsulate search terms in double quotes
	$new_terms = array();
	if($_POST['exact_srch'] == 1) {
		foreach($this->search_terms as $cur_term) {
			if(!empty($cur_term)) $new_terms[] = '"'.urlencode(trim($cur_term)).'"';
		}
		$this->search_terms = $new_terms;
	} else {
		foreach($this->search_terms as $cur_term) {
			if(!empty($cur_term)) $new_terms[] = urlencode(trim($cur_term));
		}
		$this->search_terms = $new_terms;
	}
	
	$this->proc_rpt();
		
  }
  
  function proc_rpt() {
	global $goog_scrap, $data_grab;
	
	$headers_arr = array(
						 'Datacenter',
						 'Domain',
						 'Results',
						 );
	
  
	$report_output = '<table align="center" border="0" cellspacing="0" cellpadding="4" class="tbl_results"><thead><tr>';
  
	foreach($headers_arr as $id => $hdr_val) {
	  $report_output .= '<th class="hdr_ste_'.$id.'">'.$hdr_val.'</th>';
	}	
  
	$report_output .= '</tr></thead><tbody>';
	
	$report_output .= $goog_scrap->proc_all_domains($this->search_terms);

	$report_output .= '</tbody></table>';
	
//	$report_output .= $data_grab->vb_output;
	
	echo $report_output;
	echo '<center><a href="javascript:void(0);" onclick="printpop();"><img border="0" src="../printer.gif"></a> <a href="javascript:void(0);" onclick="emailresults();"><img border="0" src="../e-mail_icon.jpg"></a></center>';
	
	// catalog requested search terms
	
	// start output buffer
	ob_start();
		
		// load template
		require(SERV_ROOT.'drank/def.css');
		
		$styles = ob_get_contents();
		
	ob_end_clean();	  
	
	$save_op = '<style>'.$styles.'</style>'.$report_output;
	
	$report_type = 'drank';
	write_srch_vals($report_type);
	save_search_values($report_type,$save_op);
		
	if(!empty($_POST['email_address'])) {
	  
	  $email_data = array();
	  $email_data['content'] = $save_op;
	  $email_data['subject'] = $this->email_ttl;
	  $email_data['to_addresses'] = $_POST['email_address'];
	  
	  send_email($email_data);
	}
  }
	  
}

?>