<?PHP
//ini_set('display_errors',1);
//error_reporting(E_ALL|E_STRICT);

session_start();

$_SESSION = $_POST;

include_once('../../config.php');
include_once(SERV_ROOT.'functions/common.php');

if(!empty($_POST['process_later'])) {
  proc_rep_later('sales-tool-allintitle');
} else {
  include_once(SERV_ROOT.'classes/simple_html_dom.php');
  include_once(SERV_ROOT.'classes/fetch_html.php');
  $data_grab = new data_grab;
  include_once(SERV_ROOT.'sales-tool-allintitle/classes/scrapper_goog.php');
  $goog_scrap = new goog_scrap;
  include_once(SERV_ROOT.'classes/pagerank.php');
  $get_pagerank = new get_pagerank;
  
  echo '<script type="text/javascript">
  
  $(function() {
	$(".tbl_results").tablesorter();
  });
  
  </script>';
  
  include_once(SERV_ROOT.'sales-tool-allintitle/classes/process_rep.php');
  $rep_process = new rep_process;
}
?>