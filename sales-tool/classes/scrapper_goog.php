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
  
  public function proc_allinanchor($cur_srch_val) {
	  
	$ret = $this->get_domains_list($cur_srch_val);
	$anchor_ret = $this->allinanchor($cur_srch_val);
  
  $cnt = 0;
	foreach($ret as $v) {
	  
	  $domain = $this->getdomain($v['link']);
	  
	  $cnt++;
//	  echo $cnt.' ';
//	  echo $v['link'].' ';
		  
	  // check for allinanchor returns
	  $anchor_find = 0;
	  $anchor_find_arr = array();
	  foreach($anchor_ret as $cur_ank){
		$anchor_domain = $this->getdomain($cur_ank['link']);
		$anchor_find++;
		if($anchor_domain == $domain) {
//		echo $anchor_find . ' ' . $anchor_domain . ' ' . $domain.'<br>';
			$anchor_find_arr[] = $anchor_find;
		}
	  }
	  
	  $allinanchcnt += (count($anchor_find_arr) > 0 ? $anchor_find_arr[0] : $this->per_page);
	}

  return $allinanchcnt;
  }
  
  public function proc_average_pr($cur_srch_val) {
	global $get_pagerank;
	
	$ret = $this->get_domains_list($cur_srch_val);
  
    $cur_val = 0;
	$page_rank_sum = 0;
	foreach($ret as $v) {
	  $cur_val++;
//	  $domain = $this->getdomain($v['link']);
	  $page_rank_sum += $get_pagerank->get_assigned_pagerank($v['link']);
	}

	$total_pr = $page_rank_sum/$cur_val;

  return $total_pr;
  }
  
  // search allinanchor
  public function allinanchor($cur_srch_val) {
	global $data_grab;
		
	//	sleep(5);

	// create HTML DOM
    $Url=$this->goog_dom.'q=allinanchor:'.$cur_srch_val.'&num='.$this->per_page;
//	echo $Url.' <br>';
	$data_grab->attemp = 1;
	$data_grab->get_page_data($Url);
//	echo $data_grab->html_grab;
	$this->html_grab = $data_grab->html_grab;
	
//	$html = new DOMDocument();
//	$html->loadHTML($this->html_grab);
	
    $html = str_get_html($this->html_grab);

    // get news block
	$previous_domain = '';
    foreach($html->find('h3.r') as $article) {
        // get title
 		$domain = $this->getdomain(trim($article->find('a', 0)->href));
		if(!in_array($domain,$this->illegal_sites)) {
		  $found_lnk = trim($article->find('a', 0)->href);
		  if($previous_domain != $domain) {
			$item['link'] = $found_lnk;
			$ret[] = $item;
		  }
		  $previous_domain = $domain;
		}
    }
    
//    // clean up memory
//    $html->clear();
//    unset($html);

  return $ret;
  }
  
  // search inanchor
  public function inanchor($cur_srch_val) {
	global $data_grab;
	
    $Url=$this->goog_dom.'q=inanchor:'.$cur_srch_val.'&num='.$this->listing;
	$data_grab->attemp = 1;
	$data_grab->get_page_data($Url);
	$this->html_grab = $data_grab->html_grab;
	$results = $this->results_cnt();

  return $results;
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