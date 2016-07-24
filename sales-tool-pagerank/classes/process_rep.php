<?PHP

class rep_process {
  private $search_terms = '';
  
  function __construct() {
		
	$this->search_terms = explode("\n",$_POST['keywords']);
	
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
						 '#',
						 'Search Terms',
						 'Average Page Rank',
						 );
	
  
	$report_output = '<table border="0" cellspacing="0" cellpadding="4" class="tbl_results" align="center"><thead><tr>';
  
	foreach($headers_arr as $id => $hdr_val) {
	  $report_output .= '<th class="hdr_ste_'.$id.'">'.$hdr_val.'</th>';
	}	
  
	$report_output .= '</tr></thead><tbody>';
	
	$cur_ret = 0;
	foreach($this->search_terms as $cur_srch_val) {
	  $cur_ret++;
		
	  $row_array = array();
	
	  // current row value
	  $row_array[] = $cur_ret;
	  	  
	  // print current search term
	  $row_array[] = strip_tags(str_replace(array('%22','+'),array('"',' '),$cur_srch_val));

	  // added to get average pagerank
	  $pr_average = $goog_scrap->proc_average_pr(urlencode(trim($cur_srch_val)));
	  $row_array[] = round($pr_average,1);
	  	  
	  $report_output .= '<tr><td>'.implode('</td><td>',$row_array).'</td></tr>';
	
	}
	  $report_output .= '</tbody></table>';
	  if($_POST['verbose_opt'] == 1) $report_output .= $data_grab->vb_output;
	  
	  echo $report_output;
	  echo '<center><a href="javascript:void(0);" onclick="printpop();">Click to Print Results</a></center>';
		
	  // start output buffer
	  ob_start();
		  
		  // load template
		  require(SERV_ROOT.'sales-tool-pagerank/def.css');
		  
		  $styles = ob_get_contents();
		  
	  ob_end_clean();	  
	  
	  $save_op = '<style>'.$styles.'</style>'.$report_output;
	  
	  $report_type = 'sales-tool-pagerank';
	  write_srch_vals($report_type);
	  save_search_values($report_type,$save_op);
	  
	  if(!empty($_POST['email_address'])) {
		
		$email_data = array();
		$email_data['content'] = $save_op;
		$email_data['subject'] = 'CM DRANK Search Tool Results '.date("m-d-Y");
		$email_data['to_addresses'] = $_POST['email_address'];
		
	  	send_email($email_data);
	  }
  }
	  
}

?>