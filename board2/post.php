<?
##### 사용자 정의 함수 파일을 가져온다.
require_once("function.user.php");

##### 환경설정 파일을 불러온다. 환경설정파일은 "config.테이블명.php"이어야 한다.
require ('includes/config.inc.php');


require (MYSQL);

(isset($_GET["code"]) ? $code=$_GET["code"] : $code="");

$cfg_file = "config." . $code . ".php";
if(file_exists($cfg_file)) {
   require($cfg_file);
} else {
   error("NOT_FOUND_CONFIG_FILE");
   exit;
}

##### 사용자가 아무값도 입력하지 않았거나 입력한 값이 허용되지 않는 값일 경우 에러메시지를 출력하고 스크립트를 종료한다.

(isset($_POST["name"]) ? $name=$_POST["name"] : $name="");
if(!preg_match("([^[:space:]]+)", $name)) {
   error("NOT_ALLOWED_NAME");
   exit;
}

(isset($_POST["email"]) ? $email=$_POST["email"] : $email="");

if(preg_match("([^[:space:]]+)", $email) && (!preg_match("(^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)", $email))) {
   error("NOT_ALLOWED_EMAIL");
   exit;
}

(isset($_POST["homepage"]) ? $homepage=$_POST["homepage"] : $homepage="");


if(preg_match("([^[:space:]]+)", $homepage) && (!preg_match("(http://([0-9a-zA-Z./@~?&=_]+))", $homepage))  ) {
   error("NOT_ALLOWED_HOMEPAGE");
   exit;
}


(isset($_POST["subject"]) ? $subject=$_POST["subject"] : $subject="");

if(!preg_match("([^[:space:]]+)", $subject)) {
   error("NOT_ALLOWED_SUBJECT");
   exit;
}


(isset($_POST["passwd"]) ? $passwd=$_POST["passwd"] : $passwd="");

if(!preg_match("(^[0-9a-zA-Z]{4,}$)", $passwd)) {
   error("NOT_ALLOWED_PASSWD");
   exit;
}

(isset($_POST["comment"]) ? $comment=$_POST["comment"] : $comment="");

if(!preg_match("([^[:space:]]+)", $comment)) {
   error("NOT_ALLOWED_COMMENT");
   exit;
}

##### 작업대상 데이터베이스를 선택한다.
$db = mysqlI_select_db($dbc, "uptrans");
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### 새로 작성된 게시물의 fid(family id), uid(unique id)값을 결정한다.
$result = mysqli_query($dbc, "SELECT max(uid), max(fid) FROM $code");
if (!$result) {
   error("QUERY_ERROR");
   exit;
}
$row = mysqli_fetch_row($result);
if($row[0]) {
   $new_uid = $row[0] + 1;
} else {
   $new_uid = 1;
}
if($row[1]) {
   $new_fid = $row[1] + 1;
} else {
   $new_fid = 1;
}

$signdate = time();

##### 제목과 본문의 문자열에 포함된 특수문자를 escape시킨다.
$subject = addslashes($subject);
$comment = addslashes($comment);

##### 비밀번호란에 입력한 문자열을 암호화한다.
//$encrypted_passwd = crypt($passwd, '1234');
$encrypted_passwd = sha1($passwd);

##### 더이상 입력값에 이상이 없으면 데이터베이스에 입력값을 삽입한다.
$query = "INSERT INTO $code (uid, fid, name, email, homepage, subject, comment, passwd, signdate, ref, thread) VALUES ($new_uid, $new_fid, '$name', '$email', '$homepage', '$subject', '$comment', '$encrypted_passwd', $signdate, 1,'A')";
$result = mysqli_query($dbc, $query);
if($result) {

   if($notify_admin) {

      ########## 새글이 등록되었을 때 보내는 메일이므로 $type은 "new"
      $type = "new";

      ########## 메일을 발송하는 스크립트를 불러온다.
    //  include "include.mail.php";
   }

   ##### 리스트 출력화면으로 이동한다.
   echo ("<meta http-equiv='Refresh' content='0; URL=list.php?code=$code'>");
} else {
   error("QUERY_ERROR");
   exit;
}
?>
