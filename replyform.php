<?
##### 사용자 정의 함수 파일을 가져온다.
require_once("function.user.php");

##### 환경설정 파일을 불러온다. 환경설정파일은 "config.테이블명.php"이어야 한다.
(isset($_GET["code"]) ? $code=$_GET["code"] : $code="");


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
   if(!form.name.value) {
      alert('이름을 입력하세요!');
      form.name.focus();
      return;
   }
   if(!form.subject.value) {
      alert('제목을 입력하세요!');
      form.subject.focus();
      return;
   }
   if(!form.passwd.value) {
      alert('비밀번호를 입력하세요!');
      form.passwd.focus();
      return;
   }
   if(!form.comment.value) {
      alert('메시지 본문을 입력하세요!');
      form.comment.focus();
      return;
   }      
            
   form.submit();
}
//-->
</script>

<body bgColor=<?echo("$BG_COLOR")?>>

<?
##### 페이지 상단에 방명록 타이틀 이미지를 출력한다.
printTitleImage($code);

##### 작업대상 데이터베이스를 선택한다.
$db = mysqli_select_db($conn, $dbName);
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### 원글의 입력값을 뽑아낸다.

(isset($_GET["number"]) ? $number=$_GET["number"] : $number="");




$query = "SELECT fid,name,subject,comment,thread FROM $code WHERE uid = $number";
$result = mysqli_query($conn, $query);
if(!$result) {
   error("QUERY_ERROR");
   exit;
}
$row = mysqli_fetch_row($result);

$my_fid = $row[0];
$my_name = $row[1];
$my_subject = $row[2];
$my_comment = $row[3];
$my_thread = $row[4];

##### 제목과 본문에 대하여 테이블에 저장할 때(post.php) addslashes() 함수로 escape시킨 문자열을 원래대로 되돌려 놓는다.
$my_subject = stripslashes($my_subject);
$my_comment = stripslashes($my_comment);

##### 원글자체가 다른 글의 응답글일 경우 문자열의 중복을 피하기 위해 "[RE]"를 없앤다.
$my_subject = preg_replace("(\[RE\])", "",$my_subject);

##### 원글과 답변글을 구분하기 위해 원글의 각 줄앞에 콜론(:)을 추가하여 출력한다.
$my_comment = ":" . $my_comment;
$my_comment = preg_replace("(\n)", "\n:", $my_comment);

$reply_comment = $my_name . "님의 글입니다.\n\n" . $my_comment;
?>

<form name="signform" method="post" action="reply.php?code=<?echo("$code")?>&page=<?echo("$page")?>&fid=<?echo("$my_fid")?>&thread=<?echo("$my_thread")?>">

<table width="602" border="0" cellspacing="1" cellpadding="0" align="center">
<tr>
   <td bgColor="#8080FF">

   <table width="600" border="0" cellspacing="1" cellpadding="5" align="center">
   <tr>
      <td width="120" align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>이 름</font></td>
      <td width="480" bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="text" name="name" size="20" maxlength="10"></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>전 자 우 편</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="text" name="email" size="30" maxlength="40"></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>홈 페 이 지</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="text" name="homepage" size="35" maxlength="60"></td>
   </tr>            
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>제 목</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="text" name="subject" size="40" maxlength="40" value='ㄴ<?echo("$my_subject")?>'></td>
   </tr>   
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>비 밀 번 호</font></td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="password" name="passwd" size="10" maxlength="10"> <font size="2">(최소 4자이상의 영문 또는 숫자)</font></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>"><font size=2>메 시 지 본 문</font><p>
<? 
##### 본문에 대한 HTML 태그의 허용여부 메시지를 출력한다.
printAllowTagMsg($isTagAllowed);
?>
      </td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><textarea name="comment" cols="50" rows="10"><?echo("$reply_comment")?></textarea>
   </tr>
   <tr>
      <td align="center" colspan="2" bgColor="<?echo("$BG_COLOR")?>">
      <font size=2>
      <input type="button" value="답변글 쓰기" onClick="checkIt(this.form)">
      <input type="reset" value="취   소">
      </font>
      </td>
   </tr>
   </table>

   </td>
</tr>
</table>

</form>

</body>
</html>
