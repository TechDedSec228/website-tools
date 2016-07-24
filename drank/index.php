<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Customer Magnetism Drank Tool</title>
<link rel="stylesheet" type="text/css" href="def.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../css/ui-lightness/jquery-ui-1.7.2.custom.css" />
<script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="../js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="basics.js"></script>
</head>

<body>
<div class="frm_area"> </div>
<div class="reports_rtn"> </div>
<div class="email_form" style="display:none;">
<table border="1" cellspacing="0" cellpadding="5" border="0">
  <tr>
    <td>To: 
    <input name="to_address" type="text" id="to_address" size="60" value="<?PHP if(!empty($_POST['to_address'])) echo $_POST['to_address']; ?>" /></td>
  </tr>
  <tr>
    <td><textarea class="ckeditor" cols="80" id="email_content" name="email_content" rows="10"></textarea>
</td>
  </tr>
  <tr>
    <td align="center"><input type="button" name="button" id="button" value="Submit" /></td>
  </tr>
</table>
</div>
</body>
</html>
