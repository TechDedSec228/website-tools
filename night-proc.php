<?PHP
//ini_set('display_errors',1);
//error_reporting(E_ALL|E_STRICT);

set_time_limit(0);

include_once('config.php');
include_once(SERV_ROOT.'functions/common.php');

$sql_query = "SELECT
				id,
				search_type,
				search_vals
			 FROM
				delayed_searches
			 WHERE
			 	completed = 0
			 ORDER BY
				search_date ASC
			 ;";

$stmt = $dbh->prepare($sql_query);					 
$result = $stmt->execute();

while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
  
  // reassign post vars
  $post_vars = unserialize($row['search_vals']);

  $report_str = $row['search_type'].'/ajx/process.php';

  switch($row['search_type']) {
	  case 'drank':
		  request_reoport($post_vars,$report_str);
	  break;
	  case 'drank-dcenter':
		  request_reoport($post_vars,$report_str);
	  break;
	  case 'sales-tool':
		  request_reoport($post_vars,$report_str);
	  break;
	  case 'sales-tool-allintitle':
		  request_reoport($post_vars,$report_str);
	  break;
	  case 'sales-tool-pagerank':
		  request_reoport($post_vars,$report_str);
	  break;
  }

$sql_query = "UPDATE
				delayed_searches
			 SET
			 	completed = 1
			 WHERE
			 	id = ?
			 ;";

$values = array(
				$row['id'],
				);

$stmt = $dbh->prepare($sql_query);					 
$stmt->execute($values);

}

function request_reoport($post_vars,$file_loc) {
	  
  // start output buffer
  ob_start();
  
  //url-ify the data for the POST
  foreach($post_vars as $key=>$value) { 
	if($key != 'process_later') $fields_string .= $key.'='.urlencode($value).'&'; 
  }
  rtrim($fields_string,'&');
  
  $domain = 'http://74.208.195.229/';
  $url = trim($domain.$file_loc);
  
  //set the url, number of POST vars, POST data
  $ch=curl_init();
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_USERPWD, "cmuser:n07Ap455W3rd");
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
  curl_setopt($ch,CURLOPT_POST,(count($post_vars)-1));
  curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
  
  //execute post
  $result = curl_exec($ch);
  
  //close connection
  curl_close($ch);
	
  // clear output buffer
  ob_end_clean();
  
}

?>