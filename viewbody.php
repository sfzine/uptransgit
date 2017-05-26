<?
session_start();
##### 사용자 정의 함수 파일을 가져온다.
require_once("function.user.php");

##### 환경설정 파일을 불러온다. 환경설정파일은 "config.테이블명.php"이어야 한다.

//$page_title = 'Welcome !!!';
$page_title = ':: 게시판입니다 ! ::';
include ('includes/header.html');




require ('includes/config.inc.php');


require (MYSQL);


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
//require_once("include.header.php");
?>

<body bgColor="<?echo("$BG_COLOR")?>">

<?
##### 작업대상 데이터베이스를 선택한다.
$db = mysqli_select_db($dbc, "uptrans");
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### 선택한 게시물의 입력값을 뽑아낸다.
(isset($_GET["number"]) ? $number=$_GET["number"] : $number="");



$query = "SELECT name,subject,email,homepage,signdate,ref,comment,fid FROM $code WHERE uid = $number";
//$result = mysql_query($query);

$result = mysqli_query($dbc, $query);
if(!$result) {
   error("QUERY_ERROR");
   exit;
}
$row = mysqli_fetch_row($result);

$my_name = $row[0];
$my_subject = $row[1];
$my_email = $row[2];
$my_homepage = $row[3];
$my_signdate = date("Y년 m월 d일 H시 i분 s초",$row[4]);
$my_ref = $row[5];
$my_comment = $row[6];
$my_fid = $row[7];

##### 제목과 본문에 대하여 테이블에 저장할 때(post.php) addslashes() 함수로 escape시킨 문자열을 원래대로 되돌려 놓는다.
$my_subject = stripslashes($my_subject);
$my_comment = stripslashes($my_comment);

##### 제목이나 본문중에 지정한 검색어가 포함되어 있을 경우 검색된 문자열을 red color 처리하여 출력한다.

(isset($_GET["keyfield"]) ? $keyfield=$_GET["keyfield"] : $keyfield="");

(isset($_GET["key"]) ? $key=$_GET["key"] : $key="");

if(!strcmp($keyfield,"subject") && $key) {
   $my_subject = preg_replace("($key)", "<font color=red><b>$key</b></font>", $my_subject);
}
if(!strcmp($keyfield,"comment") && $key) {
   $my_comment = preg_replace("($key)","<font color=red>$key</font>",$my_comment);
}

##### 태그사용 불가로 지정한 경우 태그문자열을 그대로 출력한다.
if(strcmp($isTagAllowed,'Y')) {
   $my_comment = htmlspecialchars($my_comment);
}

##### 본문의 문자열을 개행처리한다.
$my_comment = nl2br($my_comment);

##### 선택한 게시물의 조회수를 증가시킨다.
$result = mysqli_query($dbc, "UPDATE $code SET ref = $my_ref + 1 WHERE uid = $number");
if(!$result) {
   error("QUERY_ERROR");
   exit;
}

##### 페이지 상단에 방명록 타이틀 이미지를 출력한다.
printTitleImage($code);
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
   <td width="25%" align="center" bgColor="<?echo($VW_TH_COLOR)?>"><font color="<?echo("$VW_BODY_TITLE")?>">날 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 짜</font></td>
   <td width="75%" bgColor="<?echo($VW_TD_COLOR)?>"><?echo("$my_signdate")?></td>
</tr>
<tr>
   <td align="center" bgColor="<?echo($VW_TH_COLOR)?>"><font color="<?echo("$VW_BODY_TITLE")?>">본 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 문</font></td>
   <td bgColor="<?echo($VW_TD_COLOR)?>"><?echo("$my_comment")?></td>
</tr>
<tr>
   <td colspan="2" align="right" bgColor="<?echo($VW_TD_COLOR)?>">

<?

(isset($_GET["key"]) ? $key=$_GET["key"] : $key="");
(isset($_GET["page"]) ? $page=$_GET["page"] : $page="");


$encoded_key = urlencode($key);
echo("
   <A HREF=\"replyform.php?code=$code&page=$page&number=$number\" onMouseOver=\"status='reply to this article';return true;\" onMouseOut=\"status=''\"\"><img src=\"$iconDir/reply.gif\" width=35 height=35 border=0></A>
   <A HREF=\"modifyform.php?code=$code&page=$page&number=$number&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='modify this article';return true;\" onMouseOut=\"status=''\"\"><img src=\"$iconDir/modify.gif\" width=35 height=35 border=0></A>
   <A HREF=\"deleteform.php?code=$code&page=$page&number=$number&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='delete this article';return true;\" onMouseOut=\"status=''\"\"><img src=\"$iconDir/delete.gif\" width=35 height=35 border=0></A>"
);
?>

   </td>
</tr>
</table>

</td></tr>
</table>

<?
if(!strcmp($listType,"thread")) {

   ##### 조회하고 있는 게시물과 연관된 게시물의 목록만 출력한다.
   //include "include.view_thread.php";
} else {

   ##### 지정한 페이지의 전체 목록을 출력하는 파일을 불러온다.
   include "include.view_list.php";
}

##### HTML 하단 페이지 파일을 불러온다.
echo "<center>";
require_once("includes/footer.html");
echo "</center>";
?>
