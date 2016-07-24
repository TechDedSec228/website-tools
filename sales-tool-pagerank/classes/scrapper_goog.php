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
  
  function get_domains_list($cur_srch_val) {
	global $data_grab;
	
	// create HTML DOM
    $Url=$this->goog_dom.'q='.$cur_srch_val.'&num='.$this->listing;
//	echo $Url.' <br>';
	$data_grab->attemp = 1;
	$data_grab->get_page_data($Url);
//	echo $data_grab->html_grab;
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
  
  public function proc_average_pr($cur_srch_val) {
	global $get_pagerank;
	
	$ret = $this->get_domains_list($cur_srch_val);
  
    $cur_val = 0;
	$page_rank_sum = 0;
	foreach($ret as $v) {
	  $domain = $this->getdomain($v['link']);
	  if(!in_array($domain,$this->illegal_sites)) {
		$cur_val++;
		$page_rank_sum += $get_pagerank->get_assigned_pagerank($v['link']);
	  }
	}

	$total_pr = $page_rank_sum/$cur_val;

  return $total_pr;
  }
  
  public function backlinks($domain = '') {
	// create HTML DOM
	$search_string = urlencode(trim($domain));
	$html = file_get_html($this->goog_dom.'q=link%3A'.$search_string);

	// get news block
	$backlnks_txt = trim($html->find('#resultStats',0)->plaintext);

	$backlink_arr = explode(" ",$backlnks_txt);
	
	$backlinks_cnt = $backlink_arr[6];

	// clean up memory
	$html->clear();
	unset($html);

  return $backlink_arr[6];
  }
  
  public function getdomain($url) {
	 $url = str_replace("http://", "", str_replace("https://", "", $url));
	 $url = substr($url, 0, strpos($url, "/"));
	 return $url;
  }
  
}

?>