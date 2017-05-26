<?php
##### ������ ���� �Լ� ������ �����´�.
require_once("function.user.php");

##### ȯ�漳�� ������ �ҷ��´�. ȯ�漳�������� "config.���̺���.php"�̾��� �Ѵ�.
$cfg_file = "config." . $code . ".php";
if(file_exists($cfg_file)) {
   require($cfg_file);
} else {
   error("NOT_FOUND_CONFIG_FILE");
   exit;
}

##### HTML ���� ������ ������ �ҷ��´�.
require_once("include.header.php");
?>

<script language="javascript">
<!--
function checkIt(form) {
   if(!form.passwd.value) {
      alert('���й�ȣ�� �Է��ϼ���!');
      form.passwd.focus();
      return;
   }
   form.submit();
}
function focusIt() {
   document.signform.passwd.focus();
}
//-->
</script>

<body bgColor=<?echo("$BG_COLOR")?> onLoad="focusIt()">

<?
##### ������ ���ܿ� ������ Ÿ��Ʋ �̹����� �����Ѵ�.
printTitleImage($code);

##### �۾����� �����ͺ��̽��� �����Ѵ�.
$db = mysql_select_db($dbName);
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### �����ϰ��� �ϴ� ���� ������ ������ ������ ������ �����Ѵ�.
$query = "SELECT fid,name,subject,email,homepage,thread FROM $code WHERE uid = $number";
$result = mysql_query($query);
if(!$result) {
   error("QUERY_ERROR");
   exit;
}

$row = mysql_fetch_object($result);

$my_fid = $row->fid;
$my_name = $row->name;
$my_subject = $row->subject;
$my_email = $row->email;
$my_homepage = $row->homepage;
$my_thread = $row->thread;

##### ������ ���Ͽ� ���̺��� ������ ��(post.php) addslashes() �Լ��� escape��Ų ���ڿ��� �������� �ǵ��� ���´�.
$my_subject = stripslashes($my_subject);

##### �˻����ڿ��� ���ڵ��Ѵ�.
$encoded_key = urlencode($key);

if(!$my_email) {
   $my_email = "&nbsp;";
}
if(!$my_homepage) {
   $my_homepage = "&nbsp;";
}
?>

<form name="signform" method="post" action="delete.php?code=<?echo("$code")?>&page=<?echo("$page")?>&fid=<?echo("$my_fid")?>&thread=<?echo("$my_thread")?>&keyfield=<?echo("$keyfield")?>&key=<?echo("$encoded_key")?>">

<table width="602" border="0" cellspacing="1" cellpadding="0" align="center">
<tr><td bgColor="#8080FF">

   <table width="600" border="0" cellspacing="1" cellpadding="5" align="center">
   <tr>
      <td width="120" align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>�� ��</font></td>
      <td width="480" bgColor="<?echo("$FORM_VALUE_BG")?>"><font size=2><?echo ("$my_name")?></font></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>�� �� �� ��</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><font size=2><?echo ("<a href=mailto:$my_email>$my_email</a>")?></font></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>Ȩ �� �� ��</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><font size=2><?echo ("<a href=$my_homepage>$my_homepage</a>")?></font></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>�� ��</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><font size=2><?echo ("$my_subject")?></font></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>�� �� �� ȣ</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="password" name="passwd" size="10" maxlength="10"></td>
   </tr>
   <tr>
      <td align="center" colspan="2" bgColor="<?echo("$BG_COLOR")?>">
      <font size=2>
      <input type="button" value="�� �� ��" onClick="checkIt(this.form)">
      <input type="reset" value="��   ��">
      </font>
      </td>
   </tr>
   </table>

</td></tr>
</table>

</form>

</body>
</html>
