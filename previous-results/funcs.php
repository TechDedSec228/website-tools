<?PHP

// get vars from database
function results_list($page = '') {
	global $dbh;
	
	if($page > 1) {
		$limit = (($page-1)*40).','.(40); 
	} else {
		$limit = 40;
		$page = 1;
	}
	
	$sql_query = "SELECT
					id,
					query_date,
					search_type,
					search_domain,
					search_terms
				 FROM
					query_results
				 ORDER BY
				 	query_date DESC
				 LIMIT ".$limit.";";

	$stmt = $dbh->prepare($sql_query);					 
	$result = $stmt->execute();
	
	$results = '';
	$cur_num = (($page-1)*40);
	while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
      $cur_num++;
	  
	  $results .= '<tr>
        <td>'.$cur_num.'</a></td>
        <td><a target="_blank" href="http://74.208.195.229/previous-results/?res_id='.$row['id'].'">'.$row['query_date'].'</a></td>
        <td>'.$row['search_type'].'</td>
        <td>'.str_replace("\n","<br>",$row['search_terms']).'</td>
        <td>'.$row['search_domain'].'</td>
      </tr>';
		
	}
	
	// clear result set
	$result->free();
	
	// reset DB conn
	db_check_conn();
	
return $results;
}

// get vars from database
function srch_results_list() {
	global $dbh;
		
	$sql_query = "SELECT
					id,
					query_date,
					search_type,
					search_domain,
					search_terms
				 FROM
					query_results
				 WHERE
				 	search_domain LIKE ?
				 OR 
				 	search_terms LIKE ?
				 OR
				 	query_date LIKE ?
				 ORDER BY
				 	query_date DESC
				 ;";

	$values = array(
					'%'.$_POST['search_trm'].'%',
					'%'.$_POST['search_trm'].'%',
					'%'.$_POST['search_trm'].'%',
					);
	
	$stmt = $dbh->prepare($sql_query);					 
	$result = $stmt->execute($values);
	
	$results = '';
	
	while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
      
	  $results .= '<tr>
        <td><a target="_blank" href="http://74.208.195.229/previous-results/?res_id='.$row['id'].'">'.$row['query_date'].'</a></td>
        <td>'.$row['search_type'].'</td>
        <td>'.str_replace("\n","<br>",$row['search_terms']).'</td>
        <td>'.$row['search_domain'].'</td>
      </tr>';
		
	}
	
	// clear result set
	$result->free();
	
	// reset DB conn
	db_check_conn();
	
return $results;
}

function prnt_listing() {
	
$page_lnks = prnt_pg_lnks($_GET['page']);
	
echo '<table width="450" cellspacing="0" cellpadding="0" align="center" class="tool_prev">
  <tr>
    <th align="center">CM Tools Previous Results</th>
  </tr>
  <tr>
    <td align="center"><a href="http://74.208.195.229/">Generate Different Report</a></td>
  </tr>
  <tr>
    <td align="center">
    <form name="search_frm" action="" method="post">
    <input name="search_trm" type="text" size="50" maxlength="150" /><input name="Search" value="Search" type="submit" />
    </form>
    </td>
  </tr>
  <tr>
    <td align="center">'.$page_lnks.'</td>
  </tr>
  <tr>
    <td><table width="450" border="0" cellspacing="0" cellpadding="0" class="tool_prev_lst">
      <tr>
        <th>#</th>
        <th>Date</th>
        <th>Type</th>
        <th>Terms</th>
        <th>Domain</th>
      </tr>' . results_list($_GET['page']).
    '</table></td>
  </tr>
  <tr>
    <td align="center">'.$page_lnks.'</td>
  </tr>
</table>';
}

function srch_listing(){
echo '<table width="450" border="0" cellspacing="0" cellpadding="0" align="center" class="tool_prev">
  <tr>
    <th align="center">CM Tools Previous Results</th>
  </tr>
  <tr>
    <td align="center"><a href="http://74.208.195.229/">Generate Different Report</a></td>
  </tr>
  <tr>
    <td align="center">
    <form name="search_frm" action="" method="post">
    <input name="search_trm" type="text" size="50" maxlength="150" /><input name="Search" value="Search" type="submit" />
    </form>
    </td>
  </tr>
  <tr>
    <td><table width="450" border="0" cellspacing="0" cellpadding="0" class="tool_prev_lst">
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Terms</th>
        <th>Domain</th>
      </tr>' . 
	  srch_results_list().
    '</table></td>
  </tr>
</table>';
}

function prnt_sel_id() {
	global $dbh;
	
	$sql_query = "SELECT
					results
				 FROM
					query_results
				 WHERE
				 	id = ?
				 LIMIT 1;";

	$values = array(
					(int)$_GET['res_id'],
					);
	
	$stmt = $dbh->prepare($sql_query);					 
	$result = $stmt->execute($values);
	
	$results = '';
	
	$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
	
	echo '<div align="center">'.$row['results'].'</div>';
	
}

function prnt_pg_lnks($page='') {
	global $dbh;
	
	$sql_query = "SELECT
					count(*) as cnt
				 FROM
					query_results
				 ;";
	
	$stmt = $dbh->prepare($sql_query);					 
	$result = $stmt->execute();
	$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
	
	$row_cnt = $row['cnt'];
	
	$pages = array();
	
	for($i = 0;$i <= $row_cnt; $i+=40) {
		$cur_page = ($i/40)+1;
		$pages[] = '<a href="http://74.208.195.229/previous-results/?page='.$cur_page.'">'.$cur_page.'</a>';
	}
	
return 'Pages: '.implode(" | ",$pages);
}

?>