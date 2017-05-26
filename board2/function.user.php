<?php
##### 방명록 테이블명을 인자로 전달받아 해당하는 이미지를 출력한다.
function printTitleImage($code) {
   $title_image = $code . ".png";
   echo "<center><img src=\"" . $title_image . "\" border=0></center><p>";
}

##### 방명록 본문작성시 HTML 태그를 허용할 것인지를 나타내는 메시지를 출력한다.
function printAllowTagMsg($isAllowed) {
   if($isAllowed == "Y") {
      echo "(태그사용 <font color=red>가능</font>)";
   } else {
      echo "(태그사용 <font color=red>불가</font>)";
   }
}

##### 에러발생시 인자로 전달받은 에러 메시지를 팝업창에 띄워 출력한다.
function popup_msg($msg) {
   echo("<script language=\"javascript\">
   <!--
   alert('$msg');
   history.back();
   //-->
   </script>");
}

##### 에러발생시 에러코드를 인자로 전달받아 에러상황에 해당하는 메시지와 함께 popup_msg()함수를 호출한다.
function error($errcode) {
   switch ($errcode) {
      case ("NOT_FOUND_CONFIG_FILE") :
         popup_msg("현재 디렉토리에 참조할 환경설정 파일이 없습니다.");
         break;

      case ("ACCESS_DENIED_DB_CONNECTION") :
         popup_msg("데이터베이스 연결에 실패하였습니다.\\n\\n연결하고자 하는 서버명과 사용자명, 비밀번호를 확인하시기 바랍니다.");
         break;

      case ("FAILED_TO_SELECT_DB") :
         popup_msg("지정한 데이터베이스를 작업대상 데이터베이스로 할 수 없습니다.\\n\\n지정한 데이터베이스를 확인하시기 바랍니다.");
         break;

      case ("QUERY_ERROR") :
         $err_no = mysql_errno();
         $err_msg = mysql_error();
         $error_msg = "ERROR CODE " . $err_no . " : " . $err_msg;
         $error_msg = addslashes($error_msg);
         popup_msg($error_msg);
         break;

      case ("NOT_ALLOWED_NAME") :
         popup_msg("입력하신 이름은 허용되지 않는 값입니다.\\n\\n다시 입력하여 주십시오.");
         break;

      case ("NOT_ALLOWED_EMAIL") :
         popup_msg("입력하신 전자우편주소의 형식이 올바르지 않습니다.\\n\\n다시 입력하여 주십시오.");
         break;

      case ("NOT_ALLOWED_HOMEPAGE") :
         popup_msg("입력하신 홈페이지 주소의 형식이 올바르지 않습니다.\\n\\n다시 입력하여 주십시오.");
         break;

      case ("NOT_ALLOWED_SUBJECT") :
         popup_msg("입력하신 제목은 허용되지 않는 값입니다.\\n\\n다시 입력하여 주십시오.");
         break;

      case ("NOT_ALLOWED_PASSWD") :
         popup_msg("암호는 최소 4자이상의 영문자 또는 숫자여야 합니다.\\n\\n다시입력하여 주십시오.");
         break;

      case ("NOT_ALLOWED_COMMENT") :
         popup_msg("본문을 입력하지 않으셨습니다.\\n\\n다시 입력하여 주십시오.");
         break;

      case ("CANNOT_SEND_MAIL") :
         popup_msg("메일을 발송할 수 없습니다.\\n\\n발송메일의 형식을 확인하여 주십시오.");
         break;

      case ("NO_ACCESS_MODIFY") :
         popup_msg("입력하신 암호와 일치하지 않으므로 수정할 수 없습니다. \\n\\n다시 입력하여 주십시오.");
         break;

      case ("NO_ACCESS_DELETE") :
         popup_msg("입력하신 암호와 일치하지 않으므로 삭제할 수 없습니다. \\n\\n다시 입력하여 주십시오.");
         break;

      case ("NO_ACCESS_DELETE_THREAD") :
         popup_msg("답변이 있는 글은 삭제하실 수 없습니다. \\n\\n답변글을 모두 삭제하신 후 삭제하십시오.");
         break;

      default :
   }
}

##### 인증에 필요한 이름과 암호를 입력받는 인증창을 띄우는 함수
function authenticate() {
   Header("WWW-authenticate: basic realm=\"관리자 영역\"");
   Header("HTTP/1.0 401 Unauthorized");

   echo("
	<html>

	<body>
       	<script language=\"javascript\">
	<!--
           alert('관리자 인증에 실패하였습니다.');
           history.back();
	//-->
        </script>
	</body>
	</html>
   ");

   exit;
}
?>
