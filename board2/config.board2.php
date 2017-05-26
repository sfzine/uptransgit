<?php
##### 데이터베이스 연결설정 인자 (서버명, 사용자명, 비밀번호, 작업대상 데이터베이스명)
$hostName = "localhost";
$userName = "navyism";
$userPassword = "1234";
$dbName = "uptrans";

##### 데이터베이스에 연결한다.
//$conn = @mysql_connect($hostName,$userName,$userPassword);

//$conn = mysqli_connect("localhost", "navyism", "1234");

//$db = mysqli_select_db($connect,"uptrans");

//if(!$conn) {
//   error("ACCESS_DENIED_DB_CONNECTION");
//   exit;
//}


//include "./db_info.php";

##### 방명록의 아이콘 디렉토리
$iconDir = $code . "_icon";

##### 한 페이지당 출력할 방명록 게시물의 개수
$num_per_page = 10;

##### 한 블록당 출력할 방명록 직접이동 링크의 개수
$page_per_block = 10;

##### 본문에 대한 태그허용 여부 (허용: 'Y', 불가: 'N')
$isTagAllowed = 'Y';

##### 게시물의 내용을 조회할 때 하단에 출력되는 리스트의 형식을 결정 (관련글 출력: "thread", 전체 리스트 출력: "list")
$listType = "thread";

##### 새로운 글이 등록되었을 경우 관리자에게 이메일로 통보할 것인지의 여부 (전송: 1, 전송안함 : 0, Windows 운영체제일 경우 전송안함으로 설정)
$notify_admin = 1;

##### 방명록 관리자의 전자우편주소
$admin = "younicom@puh.co.kr";

##### 메인페이지 주소
$mainpage = "http://jcafe.puh.co.kr";

##### 답변글에 대한 indentation 한계치
$reply_indent = 3;

##### 답변이 달린 글을 삭제 허용할 것인지를 결정 (삭제허용: 1, 삭제불가: 0)
$allow_delete_thread = 0;

##### 최근 게시물 설정시간 (day)
$notify_new_article = 5;

##### 게시판 배경색(Background Color)
$BG_COLOR = "#FFFFFF";

//$BG_COLOR = "lightgray";


##### 게시물 출력목록의 배경색(include.view_list.php)
$LIST_TH_COLOR = "#f7b357";

//$LIST_TH_COLOR = "brown";
$LIST_TD_COLOR = "#E6FCE6";


//$LIST_TD_COLOR = "#559898";

##### 입력양식 파일의 출력색상 지정(postform.php, modifyform.php, deleteform.php, replyform.php)
$FORM_ITEM_BG = "#CFD0ED";
$FORM_VALUE_BG = "#FAFAEE";

##### 메일발송시 출력색상 지정(include.mail.php)
$MAIL_SUBJECT_BG = "#E6FCE6";
$MAIL_ITEM_BG = "#EFEFEE";
$MAIL_VALUE_BG = "#E3E4FB";

##### 선택한 게시물에 대한 출력색 지정(viewbody.php, read.php)
$VW_TH_COLOR = "#F6FCE6";
$VW_TD_COLOR = "#FFFFFF";
$VW_FRAME_BG = "#000000";
$VW_BODY_TITLE = "#487648";
$VW_BODY_SUBJ = "#000000";
$VW_BG_SUBJ = "#F6FCE6";
?>
