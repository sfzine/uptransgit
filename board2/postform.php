<?
##### 사용자 정의 함수 파일을 가져온다.
require_once("function.user.php");

##### 환경설정 파일을 불러온다. 환경설정파일은 "config.테이블명.php"이어야 한다.

//(isset($_GET["key"]) ? $key=$_GET["key"] : $key="");
(isset($_GET["code"]) ? $code=$_GET["code"] : $code="");

$cfg_file = "config." . $code . ".php";
if(file_exists($cfg_file)) {
   require($cfg_file);
} else {
   error("NOT_FOUND_CONFIG_FILE");
   exit;
}

##### HTML 상단 페이지 파일을 불러온다.
//require_once("include.header.php");
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

<body bgColor="<?echo("$BG_COLOR")?>">

<?
##### 페이지 상단에 방명록 타이틀 이미지를 출력한다.
printTitleImage($code);
?>

<form name="signform" method="post" action="post.php?code=<?echo("$code")?>">

<table width="602" border="0" cellspacing="1" cellpadding="0" align="center">
<tr>
   <td bgColor="#8080FF">

   <table width="600" border="0" cellspacing="1" cellpadding="5" align="center">
   <tr>
      <td width="120" align="center" bgColor="<?echo("$FORM_ITEM_BG")?>">이 름</td>
      <td width="480" bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="text" name="name" size="20" maxlength="10"></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>">전 자 우 편</td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="text" name="email" size="30" maxlength="40"></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>">홈 페 이 지</td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="text" name="homepage" size="35" maxlength="60"></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>">제 목</td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="text" name="subject" size="45" maxlength="40"></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>">비 밀 번 호</td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><input type="password" name="passwd" size="10" maxlength="10"> <font size="2">(최소 4자이상의 영문 또는 숫자)</font></td>
   </tr>
   <tr>
      <td align="center" bgColor="<?echo("$FORM_ITEM_BG")?>">메 시 지 본 문<p>
<?
##### 본문에 대한 HTML 태그의 허용여부 메시지를 출력한다.
printAllowTagMsg($isTagAllowed);
?>
      </td>
      <td bgColor="<?echo("$FORM_VALUE_BG")?>"><textarea name="comment" cols="50" rows="10"></textarea>
   </tr>
   <tr>
      <td align="center" colspan="2" bgColor="<?echo("$BG_COLOR")?>">
      <font size=2>
      <input type="button" value="글 쓰 기" onClick="checkIt(this.form)">
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
