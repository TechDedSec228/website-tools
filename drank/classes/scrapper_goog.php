<?PHP

class goog_scrap {
 private $illegal_sites = array();
 private $goog_dom = '';
 private $goog_data_centers = array();
 private $per_page = 100;
 private $listing = 10;
 public $vb_output = '';
 private $html_grab = '';
 private $selected_domain = '';
 
  function __construct() {
	global $illegal_sites, $goog_data_centers;
	
	$this->illegal_sites = $illegal_sites;
	
	$this->goog_data_centers = array($goog_data_centers[0]);
	
	$this->selected_domain = $_POST['domain'];
	
  }
  
  function get_domains_list($cur_srch_val) {
	global $data_grab;
	
	// create HTML DOM
    $Url=$this->goog_dom.'q='.$cur_srch_val.'&num='.$this->per_page;
	$data_grab->attemp = 1;
	$data_grab->get_page_data($Url);
	$this->html_grab = $data_grab->html_grab;
    $html = str_get_html($this->html_grab);

	// get news block
	foreach($html->find('h3.r') as $article) {
		// get title
		$domain = $this->getdomain(trim($article->find('a', 0)->href));
		if(!in_array($domain,$this->illegal_sites)) {
		  $item['title'] = trim($article->find('a', 0)->plaintext);
		  $item['link'] = trim($article->find('a', 0)->href);
  
		  $ret[] = $item;
		}
	}
	
	// clean up memory
	$html->clear();
	unset($html);

  return $ret;
  }
  
  public function proc_domains($cur_srch_val) {
	  
	$ret = $this->get_domains_list($cur_srch_val);
  
	// check for domain return
	$anchor_find = 0;
	$anchor_find_arr = array();
	foreach($ret as $v) {
			
	  $anchor_find++;
	  if(strpos($v['link'],$this->selected_domain) > 0) {
		$anchor_find_arr[] = $anchor_find;
	  }
	
	}

  return $anchor_find_arr;
  }
  
  private function test_lnk($lnk) {
	
	$url = fsockopen($lnk, 80, $errno, $errstr, 30); 
	if(!$url) { 
	 $lnk_success = 0; 
	} else { 
	 $lnk_success = 1; 
	} 

  return $lnk_success;
  }
  
  public function proc_all_domains($search_terms) {
	
	$report_output = '';
	
	$run = 0;
	foreach($this->goog_data_centers as $cur_data_center) {
	  $run++;
		
	  $this->goog_dom = 'http://'.$cur_data_center.'/search?';
		
	  $report_output .= '<tr><td>'.$cur_data_center.'</td><td>'.$this->selected_domain.'</td><td><table>';
		
	  if($this->test_lnk($cur_data_center) == 1) {
		$headers_arr = array(
							 '#',
							 'Search Term',
							 'Found Locations',
							 );
		  
		foreach($headers_arr as $id => $hdr_val) {
		  $report_output .= '<th class="hdr_ste_'.$id.'">'.$hdr_val.'</th>';
		}	
	  
		$cur_ret = 0;
		foreach($search_terms as $cur_srch_val) {
		  $cur_ret++;
			
		  $row_array = array();
		
		  // current row value
		  $row_array[] = $cur_ret;
		
		  // current search term
		  $row_array[] = strip_tags(str_replace(array('%22','+'),array('"',' '),$cur_srch_val));
		  
		  // print current search term
		  $searchterms = $this->proc_domains($cur_srch_val);
		  $row_array[] = (count($searchterms) > 0 ? implode(', ',$searchterms) : 'Not In Top 100');
				  
		  $report_output .= '<tr><td>'.implode('</td><td>',$row_array).'</td></tr>';
		}
	  } else {
		  $report_output .= '<tr><td>Datacenter not accessible</td></tr>';
	  }
	  
	  $report_output .= '</table></td></tr>';
	  
	}

  return $report_output;
  }
  
  public function getdomain($url) {
	 $url = str_replace("http://", "", str_replace("https://", "", $url));
	 $url = substr($url, 0, strpos($url, "/"));
	 return $url;
  }
  
}

?>