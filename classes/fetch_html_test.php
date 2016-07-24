<?PHP

class data_grab {
  public $html_grab = '';
  private $proxies = '';
  public $attemp = 1;
  private $max_allowable_attemps = 3;
  public $link_checking = 1;
  
  function __construct() {
	
	$this->proxies = array(
						   '193.174.155.26:3127',
						   '132.170.3.32:3128',
						   '141.76.45.17:3127',
						   '141.76.45.17:3124',
						   '78.130.129.251:8080',
						   '208.117.131.116:3128',
						   '208.117.131.116:3124',
						   '208.117.131.115:3124',
						   'lnln.info',
						   'cmcforexunlock.info',
						   'money2cash.info',
						   'onlineschools.cz.cc',
						   'xinproxy.com',
						   'prettyside.com',
						   'securecode.info',
						   'unblockyoutube.us',
						   'szkolenie.c0.pl',
						   'firstmove.info',
						   'covermany.info',
						   'gproxy.info',
						   'proxy.clan-cdf.com',
						   'shallx.net',
						   'justany.info',
						   'veryhide.info',
						   'gursimran4.info',
						   'domor.cz.cc',
						   'secretmyip.com',
						   'sitesunblock.com',
						   'saymyname.in',
						   'datashelf.net',
						   'routeip.info',
						   'lproxy.net',
						   'fastuse.info',
						   'firstcloak.info',
						   'lovesports.info',
						   'jooper.info',
						   'howimetyourproxy.info',
						   'dephyr.com',
//						   '76.99.105.108',
//						   '130.37.198.244:3128',
//						   '192.41.135.219:3128',
//						   '192.33.90.67:3128',
//						   '192.41.135.219:3127',
//						   '68.115.61.188:8085',
//						   '24.247.120.189:8085',
//						   '209.159.214.94:8085',
//						   '24.7.33.255:9090',
//						   '24.7.33.255:9090',
//						   '174.36.1.100:3128', // cld server do not delete
						   '74.208.195.229:3128', // cmtools server do not delete
						   );
	
  }
  
  public function get_page_data($Url){

	set_time_limit(999);
	
	$random_proxy = array_rand($this->proxies);
	
	$this->html_grab = '';
	
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,$Url);
//	curl_setopt($curl_handle,CURLOPT_HEADER, 0); 
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
//	curl_setopt($curl_handle,CURLOPT_MAXREDIRS,10); 
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,TRUE);
//	curl_setopt($curl_handle,CURLOPT_HTTPPROXYTUNNEL,TRUE);
	curl_setopt($curl_handle,CURLOPT_PROXYTYPE, CURLPROXY_HTTP); 
	curl_setopt($curl_handle,CURLOPT_PROXY, $this->proxies[$random_proxy]); 
	curl_setopt($curl_handle,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8'); 
//	curl_setopt($s,CURLOPT_REFERER,''); 
	$this->html_grab = trim(curl_exec($curl_handle));
	$httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
	curl_close($curl_handle);
	
	// store vorbose output
	$this->vb_output .= $httpcode.' '.$Url.'<br>';
	$this->vb_output .= '<div class="vb_output">'.$this->html_grab.'</div>';
	
	if($this->link_checking == 1 && $this->attemp <= $this->max_allowable_attemps) {	
	  // if return info is empty run curl request again
	  if(($this->html_grab == 1 || $httpcode != 200 || empty($this->html_grab))) {
		$this->get_page_data($Url);
	  } else {
		// update attemp count
		$this->attemp++;
	  }
	} else {
	  // update attemp count
	  $this->attemp = 1;
	}
	
  }

}

?>