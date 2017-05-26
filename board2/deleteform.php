<?
##### 사용자 정의 함수 파일을 가져온다.
require_once("function.user.php");

##### 환경설정 파일을 불러온다. 환경설정파일은 "config.테이블명.php"이어야 한다.
$cfg_file = "config." . $code . ".php";
if(file_exists($cfg_file)) {
   require($cfg_file);
} else {
   error("NOT_FOUND_CONFIG_FILE");
   exit;
}

##### HTML 상단 페이지 파일을 불러온다.
require_once("include.header.php");
?>

<script language="javascript">
<!--
function checkIt(form) {      
   if(!form.passwd.value) {
      alert('비밀번호를 입력하세요!');
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
##### 페이지 상단에 방명록 타이틀 이미지를 출력한다.
printTitleImage($code);

##### 작업대상 데이터베이스를 선택한다.
$db = mysql_select_db($dbName);
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### 삭제하고자 하는 글의 내용을 가져와 각각의 변수에 저장한다.
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

##### 제목에 대하여 테이블에 저장할 때(post.php) addslashes() 함수로 escape시킨 문자열을 원래대로 되돌려 놓는다.
$my_subject = stripslashes($my_subject);

##### 검색문자열을 인코딩한다.
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
      <td width="120" align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>이 름</font></td>
      <td width="480" bgColor="<?echo("$FORM_VALUE_BG")?>"><font size=2><?echo ("$my_name")?></font></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>전 자 우 편</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><font size=2><?echo ("<a href=mailto:$my_email>$my_email</a>")?></font></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>홈 페 이 지</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><font size=2><?echo ("<a href=$my_homepage>$my_homepage</a>")?></font></td>
   </tr>            
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>제 목</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><font size=2><?echo ("$my_subject")?></font></td>
   </tr>   
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>비 밀 번 호</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="password" name="passwd" size="10" maxlength="10"></td>
   </tr>
   <tr>
      <td align="center" colspan="2" bgColor="<?echo("$BG_COLOR")?>">
      <font size=2>
      <input type="button" value="글 삭 제" onClick="checkIt(this.form)">
      <input type="reset" value="취   소">
      </font>
      </td>
   </tr>
   </table>

</td></tr>
</table>

</form>

</body>
</html>
