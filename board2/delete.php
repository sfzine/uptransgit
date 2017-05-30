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





##### �۾����� �����ͺ��̽��� �����Ѵ�.
$db = mysql_select_db($dbName);
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### �����ϰ��� �ϴ� ���� �亯���� �ϳ����� �ް� ������ ������ �� ������ �Ѵ�.
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

##### �����ڷ� ������ ���� ���� ���� ������ �� �ִ�.
if($PHP_AUTH_USER) {
   $query = "DELETE FROM $code WHERE fid = $fid AND thread = '$thread'";
   $result = mysql_query($query);
   if (!$result) {
      error("QUERY_ERROR");
      exit;
   }
   echo("<meta http-equiv='Refresh' content='0; URL=list.php?code=$code&page=$page&keyfield=$keyfield&key=$encoded_key'>");

} else {

   ###### �ش��Խù��� ��ȣ���� �̾Ƴ���.
   $result = mysql_query("SELECT passwd FROM $code WHERE fid = $fid AND thread = '$thread'");
   if(!$result) {
      error("QUERY_ERROR");
      exit;
   }
   $real_pass = mysql_result($result,0,0);
   mysql_free_result($result);

   ##### �����ڰ� ���й�ȣ���� �Է��� ���ڿ��� crypt() �Լ��� ��ȣȭ�Ѵ�.
   $user_pass = crypt($passwd,$real_pass);

   ##### �Խù��� ��ȣ�� �����ڰ� �Է��� ��ȣ�� ������ �Խù��� �����Ѵ�.
   if (!strcmp($real_pass,$user_pass)) {
      $query = "DELETE FROM $code WHERE fid = $fid AND thread = '$thread'";
      $result = mysql_query($query);
      if (!$result) {
         error("QUERY_ERROR");
         exit;
      }

      ##### ����Ʈ ����ȭ������ �̵��Ѵ�.
      $encoded_key = urlencode($key);
      echo("<meta http-equiv='Refresh' content='0; URL=list.php?code=$code&page=$page&keyfield=$keyfield&key=$encoded_key'>");
   } else {
      error("NO_ACCESS_DELETE");
      exit;
   }
}
?>
