<?
##### 사용자 정의 함수 파일을 가져온다.
require_once("function.user.php");


require ('includes/config.inc.php');


require (MYSQL);

##### 환경설정 파일을 불러온다. 환경설정파일은 "config.테이블명.php"이어야 한다.

(isset($_GET["code"]) ? $code=$_GET["code"] : $code="");



$cfg_file = "config." . $code . ".php";
if(file_exists($cfg_file)) {
   require($cfg_file);
} else {
   error("NOT_FOUND_CONFIG_FILE");
   exit;
}

##### 사용자가 아무값도 입력하지 않았거나 입력한 값이 허용되지 않는 값일 경우 에러메시지를 출력하고 스크립트를 종료한다.

(isset($_POST["name"])  ? $name=$_POST["name"]  : $name="");
(isset($_POST["email"])  ? $email=$_POST["email"]  : $email="");

if(!preg_match("([^[:space:]]+)", $name)) {
   error("NOT_ALLOWED_NAME");
   exit;
}

if(preg_match("([^[:space:]]+)", $email) && (!preg_match("(^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)", $email))) {
   error("NOT_ALLOWED_EMAIL");
   exit;
}

(isset($_POST["homepage"])  ? $homepage=$_POST["homepage"]  : $homepage="");



if(preg_match("([^[:space:]]+)", $homepage) && (!preg_match("(http://([0-9a-zA-Z./@~?&=_]+))", $homepage))  ) {
   error("NOT_ALLOWED_HOMEPAGE");
   exit;
}
(isset($_POST["subject"])  ? $subject=$_POST["subject"]  : $subject="");



if(!preg_match("([^[:space:]]+)", $subject)) {
   error("NOT_ALLOWED_SUBJECT");
   exit;
}

(isset($_POST["passwd"])  ? $passwd=$_POST["passwd"]  : $passwd="");

if(!preg_match("(^[0-9a-zA-Z]{4,}$)", $passwd)) {
   error("NOT_ALLOWED_PASSWD");
   exit;
}


(isset($_POST["comment"])  ? $comment=$_POST["comment"]  : $comment="");

if(!preg_match("([^[:space:]]+)", $comment)) {
   error("NOT_ALLOWED_COMMENT");
   exit;
}

##### 작업대상 데이터베이스를 선택한다.
//$db = mysql_select_db($dbName);

$db = mysqli_select_db($dbc,"uptrans");
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### 제목과 본문의 문자열에 포함된 특수문자를 escape시킨다.
$subject = addslashes($subject);
$comment = addslashes($comment);

##### 관리자로 인증된 경우 모든 글을 수정할 수 있다.
if(isset($PHP_AUTH_USER)) {
   $query = "UPDATE $code SET name = '$name', subject = '$subject', email = '$email', homepage = '$homepage', comment = '$comment' WHERE uid  = $number";
   $result = mysql_query($query);
   if (!$result) {
      error("QUERY_ERROR");
      exit;
   }
   echo("<meta http-equiv='Refresh' content='0; URL=list.php?code=$code&page=$page&keyfield=$keyfield&key=$encoded_key'>");
} else {

   ##### 해당게시물의 암호값을 뽑아낸다.

	(isset($_GET["number"])  ? $number=$_GET["number"]  : $number="");

//echo "number ---> " . $number;

	$query = "SELECT passwd FROM $code WHERE uid = '$number'";


   //$result = mysql_query("SELECT passwd FROM $code WHERE uid = '$number'");
	$result = mysqli_query($dbc, $query);



   if(!$result) {
      error("QUERY_ERROR");
      exit;
   }

   if($result) {


   	$object = mysqli_fetch_assoc($result);

    $real_pass = $object['passwd'];
   //$real_pass = mysql_result($result,0,0);
   //mysql_free_result($result);

   ##### 사용자가 비밀번호란에 입력한 문자열을 crypt() 함수로 암호화한다.
   $user_pass = sha1($passwd);
   //echo "$user_pass";

   //echo "real_pass --> " . $real_pass;

   ##### 게시물의 암호와 사용자가 입력한 암호가 같으면 게시물을 수정한다.
   if (!strcmp($real_pass,$user_pass)) {
      $query = "UPDATE $code SET name = '$name', subject = '$subject', email = '$email', homepage = '$homepage', comment = '$comment' WHERE uid  = '$number'";
      //$result = mysql_query($query);
      	$result = mysqli_query($dbc, $query);
      if (!$result) {
         error("QUERY_ERROR");
         exit;
      }




      ##### 리스트 출력화면으로 이동한다.

(isset($_GET["key"])  ? $key=$_GET["key"]  : $key="");


      $encoded_key = urlencode($key);
      echo("<meta http-equiv='Refresh' content='0; URL=list.php?code=$code&page=$page&keyfield=$keyfield&key=$encoded_key'>");
   } else {
      error("NO_ACCESS_MODIFY");
      exit;
   }

   	}    // end of if($result).... 107줄...
}
?>
