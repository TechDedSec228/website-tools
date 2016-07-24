<?PHP
session_start();
?>
<form action="" method="post" name="search_form">
<table border="0" align="center" cellpadding="4" cellspacing="0" class="srch_tbl">
  <tr>
    <th colspan="2" align="center" valign="top">CM Goog Keyword Search Tool</th>
    </tr>
  <tr>
    <th align="right" valign="top">Search Terms:&nbsp;</th>
    <td valign="top"><textarea name="keywords" id="keywords" cols="30" rows="6"><?PHP if($_SESSION['keywords'] != '') echo $_SESSION['keywords']; else echo 'blue thursday monkey
norfolk insurance
search engine optimization'; ?></textarea></td>
  </tr>
  <!--<tr>
    <th align="right" valign="top">Exact Search:</th>
    <td valign="top"><input type="checkbox" name="exact_srch" id="exact_srch" value="1" <?PHP if($_SESSION['exact_srch'] == 1) echo 'checked="checked"'; ?> /></td>
  </tr>
<tr>
  <th align="right" valign="top">Verbose Output:</th>
  <td valign="top"><input type="checkbox" name="verbose_opt" id="verbose_opt" value="1" <?PHP if($_SESSION['verbose_opt'] == 1) echo 'checked="checked"'; ?> /></td>
</tr>
--><tr>
  <th align="right" valign="top">Email Results:</th>
  <td valign="top"><input name="email_address" type="text" id="email_address" value="<?PHP if($_SESSION['email_address'] != '') echo $_SESSION['email_address']; ?>" size="40"></td>
</tr>
    <tr>
      <th align="right" valign="top">Run Overnight:</th>
      <td valign="top"><input type="checkbox" name="process_later" id="process_later" value="1" <?PHP if(!empty($_SESSION['process_later'])) if($_SESSION['process_later'] == 1) echo 'checked="checked"'; ?> />
      </td>
    </tr>
  <tr>
    <th colspan="2" align="center" valign="top"><input name="submit" value="Search" type="button" onclick="srch_frm_sbmt();" /></th>
  </tr>
</table>
</form>