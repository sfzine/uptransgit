<?php
session_start();
//$page_title = 'Welcome !!!';
$page_title = ':: 게시판입니다 ! ::';
include ('includes/header.html');

include ('function.user.php');
require ('includes/config.inc.php');


require (MYSQL);


echo "<br><br><br>";

########################################################
#####  간단한 게시판과 회원제 연동
#####
#########################################################

//require_once("function.user.php");


###########
(isset($_GET['code']) ? $code=$_GET['code'] : $code="");

$cfg_file = "config." . $code . ".php";
if(file_exists($cfg_file))
{
  require($cfg_file);
} else {
  error("NOT_FOUND_CONFIG_FILE");
  exit;
}
?>

<?php
##### 작업대상 데이터베이스를 선택한다.
//$db = mysql_select_db($dbName);

//$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$db = mysqli_select_db($dbc,"uptrans");
if(!$db) {
   error("FAILED_TO_SELECT_DB");
   exit;
}

##### 특별히 지정하지 않으면 리스트의 첫 페이지를 출력한다.
(isset($_GET["page"]) ? $page=$_GET["page"] : $page="");

if(!$page) {
   $page = 1;
}

##### 페이지 상단에 방명록 타이틀 이미지를 출력한다.
printTitleImage($code);


##### 지정한 페이지의 목록을 출력하는 파일을 불러온다.
include "include.view_list.php";



include ('includes/footer.html');
?>
