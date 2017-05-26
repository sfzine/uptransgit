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

##### 작업대상 데이터베이스를 선택한다.
$db = mysql_select_db($dbName);
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### 삭제하고자 하는 글이 답변글을 하나라도 달고 있으면 삭제할 수 없도록 한다.
if(!$allow_delete_thread) {   
   $query = "SELECT thread FROM $code WHERE fid = $fid AND length(thread) = length('$thread')+1 AND locate('$thread',thread) = 1 ORDER BY thread DESC LIMIT 1";
   $result = mysql_query($query);
   if(!$result) {
      error("QUERY_ERROR");
      exit;
   }
   $rows = mysql_num_rows($result);         
   if($rows) {        
      error("NO_ACCESS_DELETE_THREAD");
      exit;
   }
}

##### 관리자로 인증된 경우 모든 글을 삭제할 수 있다.
if($PHP_AUTH_USER) {
   $query = "DELETE FROM $code WHERE fid = $fid AND thread = '$thread'";
   $result = mysql_query($query);
   if (!$result) {
      error("QUERY_ERROR");
      exit;
   }
   echo("<meta http-equiv='Refresh' content='0; URL=list.php?code=$code&page=$page&keyfield=$keyfield&key=$encoded_key'>");
   
} else {

   ###### 해당게시물의 암호값을 뽑아낸다.
   $result = mysql_query("SELECT passwd FROM $code WHERE fid = $fid AND thread = '$thread'");
   if(!$result) {
      error("QUERY_ERROR");
      exit;
   }
   $real_pass = mysql_result($result,0,0);
   mysql_free_result($result);
   
   ##### 사용자가 비밀번호란에 입력한 문자열을 crypt() 함수로 암호화한다.
   $user_pass = crypt($passwd,$real_pass);
   
   ##### 게시물의 암호와 사용자가 입력한 암호가 같으면 게시물을 삭제한다.
   if (!strcmp($real_pass,$user_pass)) {      
      $query = "DELETE FROM $code WHERE fid = $fid AND thread = '$thread'";
      $result = mysql_query($query);
      if (!$result) {
         error("QUERY_ERROR");
         exit;
      }
      
      ##### 리스트 출력화면으로 이동한다.
      $encoded_key = urlencode($key);
      echo("<meta http-equiv='Refresh' content='0; URL=list.php?code=$code&page=$page&keyfield=$keyfield&key=$encoded_key'>");   
   } else {
      error("NO_ACCESS_DELETE");
      exit;
   }
}   
?>
