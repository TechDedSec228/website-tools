<?PHP

class goog_scrap {
 private $illegal_sites = array();
 private $goog_dom = '';
 private $per_page = 100;
 private $listing = 10;
 public $vb_output = '';
 private $html_grab = '';
 
  function __construct() {
	global $illegal_sites, $goog_data_centers;
	
	$this->illegal_sites = $illegal_sites;
	$this->goog_dom = 'http://'.$goog_data_centers[0].'/search?';
	
  }
    
  // search all in title
  public function intitle($cur_srch_val) {
	global $data_grab;
	
//	sleep(5);
	
    $Url=$this->goog_dom.'q=allintitle:'.$cur_srch_val.'&num='.$this->listing;
	$data_grab->attemp = 1;
	$data_grab->get_page_data($Url);
	$this->html_grab = $data_grab->html_grab;
	$results = $this->results_cnt();
//echo $Url.' ';
  return $results;
  }
  
  private function results_cnt() {
	
	if(strpos($this->html_grab,' of about ') > 0 || strpos($this->html_grab,' of ') > 0) {
		
	  if (strpos($this->html_grab,' of about ') > 0) $min = 0; else $min = 1;
	
	  $html = str_get_html($this->html_grab);
	  
	  $results = trim($html->find('p#resultStats', 0)->plaintext);
	
	  $results = explode(' ',$results);
	  $results = (int)str_replace(',', '', ($min == 0 ? $results[6] : $results[5]));
	  
	  unset($html);
	} else {
	  $results = 0;
	}

  return $results;
  }
  
  public function getdomain($url) {
	 $url = str_replace("http://", "", str_replace("https://", "", $url));
	 $url = substr($url, 0, strpos($url, "/"));
	 return $url;
  }
  
}

?>