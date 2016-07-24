<?PHP

// sends emails
function send_email($email_data) {

	$message = new Mail_mime();
	
	$message->setHTMLBody($email_data['content']);
	
	if (!empty($email_data['file']['file_name'])) {
		// Add an attachment
        $file = $email_data['file']['file'];                                      // Content of the file
        $file_name = $email_data['file']['file_name'];                               // Name of the Attachment
        $content_type = $email_data['file']['content_type'];                                // Content type of the file
        $message->addAttachment ($file, $content_type, $file_name, 1);  // Add the attachment to the email		
	}
	
	$body = $message->get();
	$extraheaders = array("From"=>'do_not_reply@trycm.com', "Subject"=>$email_data['subject']);
	$headers = $message->headers($extraheaders);
	
	$mail = Mail::factory("mail");
	$mail->send($email_data['to_addresses'], $headers, $body);

}

// get current page url
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER['SERVER_PORT'] == 443) {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function write_srch_vals($type = '') {
	
  $File = SERV_ROOT."searches.xls";
  $Handle = fopen($File, 'a');
  
  $search_terms = explode("\n",$_POST['keywords']);
  foreach($search_terms as $cur_keyword) {
	$Data = date("m.d.y g:i a")."\t".$type."\t".$cur_keyword."\n";
	fwrite($Handle, $Data);
  }
  
  fclose($Handle); 

}

function save_search_values($type = '',$results = '') {
	global $dbh;
	
	$keywords = $_POST['keywords'];

	$sql_query = "INSERT INTO
					query_results
				 (
					search_type,
					search_domain,
					results,
					search_terms
				 )
				 VALUES
				 (
					?,
					?,
					?,
					?
				 );";
			 
	$update_vals = array(
						$type,
						$_POST['domain'],
						$results,
						$keywords
						);
						
	$stmt = $dbh->prepare($sql_query);
	$stmt->execute($update_vals);
	
}

function proc_rep_later($type = '') {
	global $dbh;
	
	$sql_query = "INSERT INTO
					delayed_searches
				 (
					search_type,
					search_vals
				 )
				 VALUES
				 (
					?,
					?
				 );";
			 
	$update_vals = array(
						$type,
						serialize($_POST),
						);
						
	$stmt = $dbh->prepare($sql_query);
	$stmt->execute($update_vals);

echo '<center><strong>Report scheduled to run overnight!</strong></center>';
}

?>