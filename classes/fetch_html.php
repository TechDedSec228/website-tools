<?PHP

class data_grab {
  public $html_grab = '';
  private $proxies = '';
  public $attemp = 1;
  private $max_allowable_attemps = 50;
  public $link_checking = 1;
  
  function __construct() {
	
	$this->proxies = array(
//						   '174.36.1.100:3128', // cld server do not delete
							'117.102.101.219:8080',
							'200.30.101.2:8080',
							'98.224.199.5:8085',
							'201.234.207.178:8080',
							'200.25.201.85:80',
							'200.25.201.102:80',
							'211.115.185.51:8080',
							'217.33.230.26:8080',
							'189.17.150.5:3128',
							'67.215.241.237:808',
							'195.56.44.125:3128',
							'217.33.230.27:8080',
							'190.24.216.22:8080',
							'88.191.65.27:8080',
							'200.110.13.27:3128',
							'200.25.201.111:80',
							'200.151.73.2:3128',
							'200.111.137.117:3128',
							'201.232.70.201:8080',
							'201.12.130.227:8080',
							'122.220.30.14:8080',
							'58.246.76.76:8080',
							'212.174.46.8:8085',
							'123.111.230.139:8080',
							'99.228.54.51:8085',
							'200.212.0.135:3128',
							'87.120.58.161:8080',
							'212.116.220.100:8081',
							'201.36.220.179:3128',
							'211.115.185.42:8080',
							'218.201.21.177:80',
							'189.19.73.101:3128',
							'24.47.137.250:8085',
							'74.208.195.229:3128', // cmtools server do not delete
						   );
	
  }
  
  public function get_page_data($Url){

	set_time_limit(0);
	
	$random_proxy = array_rand($this->proxies);
	
	$selected_proxy = $this->proxies[$random_proxy];
	
	$this->html_grab = '';
	
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,$Url);
//	curl_setopt($curl_handle,CURLOPT_HEADER, 0); 
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
//	curl_setopt($curl_handle,CURLOPT_MAXREDIRS,10); 
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,TRUE);
//	curl_setopt($curl_handle,CURLOPT_HTTPPROXYTUNNEL,TRUE);
	curl_setopt($curl_handle,CURLOPT_PROXYTYPE, CURLPROXY_HTTP); 
	curl_setopt($curl_handle,CURLOPT_PROXY, $selected_proxy); 
//	curl_setopt($curl_handle,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8'); 
//	curl_setopt($s,CURLOPT_REFERER,''); 
	$this->html_grab = trim(curl_exec($curl_handle));
	$httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
	curl_close($curl_handle);
	
	if($this->link_checking == 1) {	
	  // if return info is empty run curl request again
	  if($this->attemp <= $this->max_allowable_attemps && ($this->html_grab == 1 || $httpcode != 200 || empty($this->html_grab))) {
		// update attemp count
		$this->attemp++;
		// run page get request again
		$this->get_page_data($Url);
	  } else {
		
//		// store vorbose output
//		$this->vb_output .= 'Response Code: '.$httpcode.' Proxy Used: '.$selected_proxy.' Requested URL: '.$Url.'<br>';
//		$this->vb_output .= '<div class="vb_output">'.$this->html_grab.'</div>';
		// update attemp count
		$this->attemp = 1;
	  }
	}
	
  }

}

?>