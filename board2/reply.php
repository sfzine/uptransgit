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
//$db = mysql_select_db($dbName);


$db = mysqli_select_db($conn, $dbName);

if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### 원글의 입력값으로부터 답변글에 입력할 정보(정렬 및 indent에 필요한 thread필드값)를 뽑아낸다.

(isset($_GET["fid"]) ? $fid=$_GET["fid"] : $fid="");

##############################
(isset($_GET["thread"]) ? $thread=$_GET["thread"] : $thread="");
echo $thread;

$query = "SELECT thread,right(thread,1) FROM $code  WHERE fid = $fid AND length(thread) = length('$thread')+1 AND locate('$thread',thread) = 1 ORDER BY thread DESC LIMIT 1";

//$query = "select thread, right(thread,1) from board2 where fid = $fid";


$result = mysqli_query($conn, $query);
if(!$result) {
   error("QUERY_ERROR");
   exit;
}





$rows = mysqli_num_rows($result);
if($rows) {        
   $row = mysqli_fetch_row($result);	   
   $thread_head = substr($row[0],0,-1);
   $thread_foot = ++$row[1];
   $new_thread = $thread_head . $thread_foot;
} else {
   $new_thread = $thread . "A";
}

echo "--->>>" . $rows ;

echo "<<<-------------";


$signdate = time();

##### 제목과 본문의 문자열에 포함된 특수문자를 escape시킨다.
$subject = addslashes($subject);
$comment = addslashes($comment);

##### 비밀번호란에 입력한 문자열을 암호화한다. 
$encrypted_passwd = crypt($passwd, '1234');




##### 데이터베이스에 입력값을 삽입한다.
$query = "INSERT INTO $code (fid, name, email, homepage, subject, comment, passwd, signdate, ref, thread) VALUES ('$fid', '$name', '$email', '$homepage', '$subject', '$comment', '$encrypted_passwd', $signdate, 0, '$new_thread')";
$result = mysqli_query($conn, $query);
if ($result) {
   if($notify_admin) {
   
      ########## 답변글이 등록되었을 때 보내는 메일이므로 $type은 "reply"
      $type = "reply";

      ########## 메일을 발송하는 스크립트를 불러온다.
      include "include.mail.php";
   }

   ##### 리스트 출력화면으로 이동한다.
   echo ("<meta http-equiv='Refresh' content='0; URL=list.php?code=$code&page=$page'>");
} else {
   error("QUERY_ERROR");
   exit;
}
?>
