<?php
session_start();

$_SESSION = $_POST;

include_once('../classes/simple_html_dom.php');

$not_in_arr = array(
					'booksearch.google.com',
					'books.google.com',
					'news.google.com',
					'blogsearch.google.com',
					);

function scraping_goog($cur_srch_val) {
	global $not_in_arr;
	
    // create HTML DOM
	$search_string = urlencode($cur_srch_val);
    $html = file_get_html('http://www.google.com/search?q='.$search_string.'&num='.$_POST['search_return']);

    // get news block
	foreach($html->find('h3.r') as $article) {
		// get title
		$domain = getdomain(trim($article->find('a', 0)->href));
		if(!in_array($domain,$not_in_arr)) {
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

function scraping_goog_inanchor($cur_srch_val) {
	global $not_in_arr;

// create HTML DOM
	$search_string = urlencode($cur_srch_val);
    $html = file_get_html('http://www.google.com/search?q=allinanchor%3A'.$search_string.'&num='.(100));

    // get news block
    foreach($html->find('h3.r') as $article) {
        // get title
 		$domain = getdomain(trim($article->find('a', 0)->href));
		if(!in_array($domain,$not_in_arr)) {
	       $item['link'] = trim($article->find('a', 0)->href);

	        $ret[] = $item;
		}
    }
    
    // clean up memory
    $html->clear();
    unset($html);

    return $ret;
}

function scraping_goog_intitle($cur_srch_val) {
	global $not_in_arr;

// create HTML DOM
	$search_string = urlencode($cur_srch_val);
    $html = file_get_html('http://www.google.com/search?q=allintitle%3A'.$search_string.'&num='.(100));

    // get news block
    foreach($html->find('h3.r') as $article) {
        // get title
  		$domain = getdomain(trim($article->find('a', 0)->href));
		if(!in_array($domain,$not_in_arr)) {
		 $item['link'] = trim($article->find('a', 0)->href);
  
		  $ret[] = $item;
		}
    }
    
    // clean up memory
    $html->clear();
    unset($html);

    return $ret;
}

function scraping_goog_backlinks($domain = '') {
    // create HTML DOM
	$search_string = urlencode(trim($domain));
    $html = file_get_html('http://www.google.com/search?q=link%3A'.$search_string);

    // get news block
	$backlnks_txt = trim($html->find('#resultStats',0)->plaintext);

	$backlink_arr = explode(" ",$backlnks_txt);
	
	$backlinks_cnt = $backlink_arr[6];

    // clean up memory
    $html->clear();
    unset($html);

    return $backlink_arr[6];
}

function getdomain($url) {
   $url = str_replace("http://", "", str_replace("https://", "", $url));
   $url = substr($url, 0, strpos($url, "/"));
   return $url;
}

// -----------------------------------------------------------------------------
// test it!

ini_set('user_agent', 'My-Application/2.5');

?>
<style>
.search_term {
	font-weight:700;
	font-size:24px;
	background-color:#EAEAEA
}
th {
	background-color:#D8D8D8;
}
</style>
<form action="" method="post" name="search_form">
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td valign="top">Search Terms:&nbsp;</td>
    <td valign="top"><textarea name="keywords" cols="30" rows="6">blue thursday monkey
norfolk insurance
search engine optimization</textarea>&nbsp;</td>
    <td valign="top">Results Per Page:&nbsp;</td>
    <td valign="top"><select name="search_return"><option>10</option>
  <option selected>20</option>
  <option>30</option>
  <option>50</option>
  <option>60</option>
  <option>100</option>
</select>&nbsp;</td>
    <td valign="top"><input name="submit" value="Search" type="submit">&nbsp;</td>
  </tr>
</table>
</form>
<?PHP
if($_POST['submit']){

$search_values = explode("\n",$_POST['keywords']);

foreach($search_values as $cur_srch_val) {
	
  $ret = scraping_goog($cur_srch_val);
  $title_ret = scraping_goog_intitle($cur_srch_val);
  $anchor_ret = scraping_goog_inanchor($cur_srch_val);
  
  $allintitlecnt = 0;
  $allinanchcnt = 0;
  
  $headers_arr = array(
					   '#',
/*					   'Anchor Text',
					   'Link',
*/					   'Address',
//					   'backlinks',
					   'allinanchor',
					   'allintitle'
					   );
  
  $cur_ret = 0;

  echo '<div class="search_term">'.$cur_srch_val.'</div>';
  echo '<table border="1" cellspacing="0" cellpadding="2"><tr>';

  echo '<th>'.implode('</th><th>',$headers_arr).'</th>';

  echo '</tr>';
  
  foreach($ret as $v) {
	  $cur_ret++;
	  $row_array = array();
	  
	  $domain = getdomain($v['link']);
	  
	  $row_array[] = $cur_ret;
//	  $row_array[] = $v['title'];
//	  $row_array[] = '<a target="_blank" href="'.$v['link'].'">'.$v['link'].'</a>';
	  $row_array[] = '<a target="_blank" href="'.$domain.'">'.$domain.'</a>';
	  
	  // get backlink counts
//	  $backlinks_tot = scraping_goog_backlinks($domain);
//	  $backlinks_tot = 'Disabled due to Goog auto query lock.';
//	  echo '<td>'.$backlinks_tot.'</td>';
	  
	  // check for allinanchor returns
	  $anchor_find = 0;
	  $anchor_find_arr = array();
	  foreach($anchor_ret as $cur_ank){
		  $anchor_find++;
		  if(strpos($cur_ank['link'],$domain) > 0) {
			  $anchor_find_arr[] = $anchor_find;
		  }
	  }
	  
	  $allinanchtxt = (count($anchor_find_arr) > 0 ? implode(', ',$anchor_find_arr) : (100));
	  $allinanchcnt += (count($anchor_find_arr) > 0 ? $anchor_find_arr[0] : (100));
	  $row_array[] = $allinanchtxt;
	  
	  // check for allintitle returns
	  $title_find = 0;
	  $title_find_arr = array();
	  foreach($title_ret as $cur_title){
		  $title_find++;
		  if(strpos($cur_title['link'],$domain) > 0) {
			  $title_find_arr[] = $title_find;
		  }
	  }
	  
	  $allintitletxt = (count($title_find_arr) > 0 ? implode(', ',$title_find_arr) : (100));
	  $allintitlecnt += (count($title_find_arr) > 0 ? $title_find_arr[0] : (100));
	  $row_array[] = $allintitletxt;
	  
	  echo '<tr><td>'.implode('</td><td>',$row_array).'</td></tr>';

  }
  
  $foot_arr = array(
					   '<strong>Totals:</strong>',
//					   '&nbsp;',
//					   '&nbsp;',
					   '&nbsp;',
					   $allinanchcnt,
					   $allintitlecnt
					   );

  echo '<tr><td>'.implode('</td><td>',$foot_arr).'</td></tr></table>';
}
  echo '<div align="center"><a href="googdownload.php">Download CSV</a></div>';
}
?>
