<?PHP
session_start();
?>
<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td>To: 
    <input name="to_address" type="text" id="to_address" size="60" value="<?PHP echo $_POST['to_address']; ?>" /></td>
  </tr>
  <tr>
    <td><textarea class="ckeditor" cols="80" id="email_content" name="email_content" rows="10"><?PHP echo $_POST['email_content']; ?></textarea>
</td>
  </tr>
  <tr>
    <td align="center"><input type="button" name="button" id="button" value="Submit" /></td>
  </tr>
</table>
