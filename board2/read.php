<?
##### 사용자 정의 함수 파일을 가져온다.
require_once("function.user.php");

##### 환경설정 파일을 불러온다. 환경설정파일은 "config.테이블명.php"이어야 한다.
$code = "";
$code = $_GET['code'];

$cfg_file = "config." . $code . ".php";
if(file_exists($cfg_file)) {
   require($cfg_file);
} else {
   error("NOT_FOUND_CONFIG_FILE");
   exit;
}

##### HTML 상단 페이지 파일을 불러온다.
require_once("include.header.php");

##### 작업대상 데이터베이스를 선택한다.
$db = mysql_select_db($dbName);
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}
?>

<body bgColor="<?echo("$BG_COLOR")?>">

<?
##### 페이지 상단에 방명록 타이틀 이미지를 출력한다.
printTitleImage($code);

##### list.php에서 사용자가 체크박스에 체크한 게시물의 내용을 출력하는 함수를 호출한다.
for($i = 0; $i < sizeof($check); $i++) {
   getContentsFromUid($check[$i]);
}

##### 검색문자열을 인코딩한다.
$encoded_key = urlencode($key);
?>

<table width=650 border=0 cellpadding=1 cellspacing=0 align=center>
<tr>
   <td align="center">
   <A HREF="list.php?code=<?echo("$code")?>&page=<?echo("$page")?>&keyfield=<?echo("$keyfield")?>&key=<?echo("$encoded_key")?>" onMouseOver="status='reload current list';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/list.gif" width=35 height=35 border=0></A>
   <A HREF="postform.php?code=<?echo("$code")?>" onMouseOver="status='post a new article';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/post.gif" width=35 height=35 border=0></A>
   </td>
</tr>
</table>

<?
##### HTML 하단 페이지 파일을 불러온다.
require_once("include.footer.php");
?>

<?
##### 인자로 넘겨받은 게시물 레코드의 uid 필드값으로 부터 해당 게시물을 출력하는 함수를 정의한다.
function getContentsFromUid($value) {
   
   ##### 게시판 테이블명과 아이콘 디렉토리명등을 저장한 변수를 전역변수로 선언한다.
   GLOBAL $code, $iconDir, $page, $key, $keyfield;
   
   ##### 데이터베이스 연결에 필요한 변수를 전역변수로 선언한다.
   GLOBAL $db;
   
   ##### 환경설정 파일에서 태그허용 여부에 대한 변수를 전역변수로 선언한다.
   GLOBAL $isTagAllowed;   
   
   ##### 게시판의 환경설정 파일에서 설정한 테이블 색상값을 함수내에서 쓸 수 있도록 이들 변수를 전역변수로 선언한다.
   GLOBAL $VW_TH_COLOR,$VW_TD_COLOR,$VW_FRAME_BG,$VW_BODY_TITLE,$VW_BODY_SUBJ,$VW_BG_SUBJ;
   
   ##### 선택한 특정 게시물 레코드의 필드값을 변수에 저장한다.      
   $query = "SELECT name,subject,email,homepage,signdate,ref,comment FROM $code WHERE uid = $value";   
   $result = mysql_query($query);
   if(!$result) {
      error("QUERY_ERROR");
      exit;
   }
   $row = mysql_fetch_row($result);

   $my_name = $row[0];
   $my_subject = $row[1];
   $my_email = $row[2];	
   $my_homepage = $row[3];
   $my_signdate = date("Y년 m월 d일 H시 i분 s초",$row[4]);	
   $my_ref = $row[5];
   $my_comment = $row[6];

   ##### 제목과 본문에 대하여 테이블에 저장할 때(post.php) addslashes() 함수로 escape시킨 문자열을 원래대로 되돌려 놓는다.   
   $my_subject = stripslashes($my_subject);
   $my_comment = stripslashes($my_comment);

   ##### 원칙상 제목에는 HTML 태그를 허용하지 않는다.
   $my_subject = htmlspecialchars($my_subject);

   ##### 태그사용 불가로 지정한 경우 태그문자열을 그대로 출력한다.
   if(strcmp($isTagAllowed,'Y')) {
      $my_comment = htmlspecialchars($my_comment);
   }
      
   ##### 제목이나 본문중에 지정한 검색어가 포함되어 있을 경우 검색된 문자열을 red color 처리하여 출력한다.
   if(!strcmp($keyfield,"subject") && $key) {
      $my_subject = eregi_replace("($key)", "<font color=red>\\1</font>", $my_subject);
   }
   if(!strcmp($keyfield,"comment") && $key) { 
      $my_comment = eregi_replace("($key)","<font color=red>\\1</font>",$my_comment);
   }   

   ##### 본문의 문자열을 개행처리한다.
   $my_comment = nl2br($my_comment);

   ##### 선택한 게시물의 조회수를 증가시킨다.
   $result = mysql_query("UPDATE $code SET ref = $my_ref + 1 WHERE uid = $value");
   if(!$result) {
      error("QUERY_ERROR");
      exit;
   }	
   echo("<br>");

?>

<table width=650 border=0 cellpadding=1 cellspacing="0" align="center">
<tr><td bgColor="<?echo("$VW_FRAME_BG")?>">

<table width=648 border=0 cellpadding=5 cellspacing="1" align="center">
<tr>
   <td colspan="2" align="center" bgColor="<?echo($VW_BG_SUBJ)?>"><font color="<?echo("$VW_BODY_SUBJ")?>"><b><?echo("$my_subject")?></b></font></td>
</tr>	
<tr>
   <td width="25%" align="center" bgColor="<?echo($VW_TH_COLOR)?>"><font color="<?echo("$VW_BODY_TITLE")?>">글 &nbsp; 쓴 &nbsp; 이</font></td>	
   
<?	   
if (!$my_email) {
   echo("<td width=\"75%\" bgColor=\"$VW_TD_COLOR\">$my_name</td>");
} else {
   echo("<td width=\"75%\" bgColor=\"$VW_TD_COLOR\">$my_name &nbsp; (<A HREF=\"mailto:$my_email\">$my_email</A>)</td>");
}	
?>
</tr>	
<tr>
   <td width="25%" align="center" bgColor="<?echo($VW_TH_COLOR)?>"><font color="<?echo("$VW_BODY_TITLE")?>">홈&nbsp;페&nbsp;이&nbsp;지</font></td>	
   
<?	   
if (!$my_homepage) {
   echo("<td width=\"75%\" bgColor=\"$VW_TD_COLOR\">-</td>");
} else {
   echo("<td width=\"75%\" bgColor=\"$VW_TD_COLOR\"><A HREF=\"$my_homepage\" target=\"_blank\">$my_homepage</A></td>");
}	
?>

</tr>
<tr>
   <td align="center" bgColor="<?echo($VW_TH_COLOR)?>"><font color="<?echo("$VW_BODY_TITLE")?>">날 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 짜</font></td>
   <td bgColor="<?echo($VW_TD_COLOR)?>"><?echo("$my_signdate")?></td>
</tr>
<tr>
   <td align="center" bgColor="<?echo($VW_TH_COLOR)?>"><font color="<?echo("$VW_BODY_TITLE")?>">본 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 문</font></td>
   <td bgColor="<?echo($VW_TD_COLOR)?>"><?echo("$my_comment")?></td>
</tr>
<tr>
   <td colspan="2" align="right" bgColor="<?echo($VW_TD_COLOR)?>">
   
<?
$encoded_key = urlencode($key);
echo("
   <A HREF=\"replyform.php?code=$code&page=$page&number=$value\" onMouseOver=\"status='reply to this article';return true;\" onMouseOut=\"status=''\"\"><img src=\"$iconDir/reply.gif\" width=35 height=35 border=0></A>
   <A HREF=\"modifyform.php?code=$code&page=$page&number=$value&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='modify this article';return true;\" onMouseOut=\"status=''\"\"><img src=\"$iconDir/modify.gif\" width=35 height=35 border=0></A>
   <A HREF=\"deleteform.php?code=$code&page=$page&number=$value&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='delete this article';return true;\" onMouseOut=\"status=''\"\"><img src=\"$iconDir/delete.gif\" width=35 height=35 border=0></A>"
);
?>

   </td>
</tr>	
</table>

</td></tr>
</table>
	  
<?	      
}
?>
