<?PHP
//ini_set('display_errors',1);
//error_reporting(E_ALL|E_STRICT);

include_once('../config.php');
include_once(SERV_ROOT.'previous-results/funcs.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Previous CM Tools Results</title>
<link rel="stylesheet" type="text/css" href="base.css" media="screen" />
<script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="../js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="basics.js"></script>
</head>

<body>
<?PHP
if(!empty($_POST['search_trm'])) {
  srch_listing();
} elseif(!isset($_GET['res_id'])) {
  prnt_listing();
} else {
  prnt_sel_id($_GET['page']);
}
?>
</body>
</html>