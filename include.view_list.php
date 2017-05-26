<script language="javascript">
<!--
function  checkUserSelect() {
   var check_nums = document.signform.elements.length;
   for(var i = 0; i < check_nums; i++) {
      var checkbox_obj = eval("document.signform.elements[" + i + "]");
      if(checkbox_obj.checked == true) {
         break;
      }
   }
   if(i == check_nums) {
      alert("먼저 읽고자 하는 게시물을 선택하여 주십시오");
      return;
   } else {
      document.signform.submit();
   }
}
//-->
</script>

<?
##### 현재 게시판 테이블에 등록되어 있는 총 레코드의 개수를 구한다.
(isset($_POST["key"]) ? $key=$_POST["key"] : $key="");

(isset($_POST["keyfield"]) ? $keyfield=$_POST["keyfield"] : $keyfield="");


(isset($_POST["encoded_key"]) ? $encoded_key=$_POST["encoded_key"] : $encoded_key="");



if(!preg_match("([^[:space:]]+)",$key)) {
   $query = "SELECT count(*) FROM $code";
} else {
   $encoded_key = urlencode($key);
   $query = "SELECT count(*) FROM $code WHERE $keyfield LIKE '%$key%'";
}

$db = mysqli_select_db($dbc,"uptrans");

$result = mysqli_query($dbc, $query);
if (!$result) {
   error("QUERY_ERROR");
   exit;
}

#########################

##########################
//$total_record = mysql_result($result,0,0);
//mysql_free_result($result);
$total_record = mysqli_fetch_array($result);

//print_r($total_record);
$total_record = $total_record[0];

##### 전체 페이지수를 계산한다.
$total_page = ceil($total_record/$num_per_page);

##### 지정한 페이지에 대하여 출력할 레코드번호의 범위를 결정한다.
if($total_record == 0) {
   $first = 1;
   $last = 0;
} else {
   $first = $num_per_page*($page-1);
   $last = $num_per_page*$page;
}
?>

<form name="signform" method="post" action="read.php?code=<?echo("$code")?>&page=<?echo("$page")?>&keyfield=<?echo("$keyfield")?>&key=<?echo("$encoded_key")?>">

<table width="650" border="0" align="center" cellspacing="0" cellpadding="0">
<tr>
   <td width="80"><a href="admin.php?code=<?echo("$code")?>" onMouseOver="status='control this board';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/admin.gif" width=15 height=15 border=0 alt="관리자 로그인"></a></td>
   <td width="490" align="center">
<?
if(!preg_match("([^[:space:]]+)",$key)) {
   echo("총 게시물 : <b>$total_record</b> (Total <b>$total_record</b> Articles)");
} else {
   echo("검색된 게시물 : <b>$total_record</b> (Total <b>$total_record</b> Articles)");
}

##### 현재 관리자 모드이면 관리자모드임을 출력한다.
//(isset($_GET["key"]) ? $key=$_GET["key"] : $key="");

(isset($_SERVER["PHP_AUTH_USER"]) ? $PHP_AUTH_USER = $_SERVER["PHP_AUTH_USER"] : $PHP_AUTH_USER="");



if($PHP_AUTH_USER) {
   echo(" - [<font color=red>관리자 모드</font>]");
}
?>
   </td>
   <td width="80" align="right">( <font color="red"><? echo("$page") ?></font> / <font color="red"><? echo("$total_page") ?></font> )</td>
</tr>
</table>

<table border="0" width="650" align="center" cellspacing="1" cellpadding="3">
<tr>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=50><font color="black">번   호</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=340><font color="#ffffff">제  목</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=80><font color="#ffffff">글쓴이</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=80><font color="#ffffff">작성일</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=50><font color="#ffffff">조회수</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=50><font color="#ffffff">선 택</font></td>
</tr>

<?
$time_limit = 60*60*24*$notify_new_article;

##### 현재 페이지에 출력할 결과레코드 세트를 얻는다.
if(!preg_match("([^[:space:]]+)",$key)) {
   $query = "SELECT uid,fid,name,email,subject,comment,signdate,ref,thread FROM $code ORDER BY fid DESC, thread ASC LIMIT $first, $num_per_page ";
} else {
   $query = "SELECT uid,fid,name,email,subject,comment,signdate,ref,thread FROM $code WHERE $keyfield LIKE '%$key%' ORDER BY fid DESC, thread ASC LIMIT $first, $num_per_page";
}
$result= mysqli_query($dbc, $query);
if (!$result) {
   error("QUERY_ERROR");
   exit;
}

##### 게시물의 가상번호(게시물의 개수에 따른 일련번호)
$article_num = $total_record - $num_per_page*($page-1);

while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {

   ##### 각 게시물 레코드의 필드값을 변수에 저장한다.
   $my_uid = $row['uid'];
   $my_fid = $row['fid'];
   $my_name = $row['name'];
   $my_email = $row['email'];
   $my_subject = $row['subject'];
   $my_comment = $row['comment'];
   $my_signdate = date("y-m-d",$row['signdate']);
   $my_ref = $row['ref'];
   $my_thread = $row['thread'];

   ##### 제목과 본문에 대하여 테이블에 저장할 때(post.php) addslashes() 함수로 escape시킨 문자열을 원래대로 되돌려 놓는다.
   $my_subject = stripslashes($my_subject);
   $my_comment = stripslashes($my_comment);

   echo("<tr>");

   ##### [컬럼 1 : 게시물의 번호를 출력한다.]
   echo("   <td bgColor=$LIST_TD_COLOR align=\"center\">$article_num</td>");
   echo("   <td bgColor=$LIST_TD_COLOR>");

   ##### 응답의 단계에 따라 출력할 제목의 문자열을 안쪽으로 indent를 시킨다.
   $spacer = strlen($my_thread)-1;
   //echo $spacer;
   ##### 원글에 대한 답변글이 $reply_indent 값 이상이 되면 답변글의 출력 indent를 고정시킨다.
   if($spacer > $reply_indent) $spacer = $reply_indent;

   //echo $spacer;

   if($my_thread == 'A')
   {

   }

   else {
   for($j = 0; $j < $spacer; $j++) {
      echo("&nbsp; ");
   }

}

   ##### 게시물의 작성시간으로부터 게시물이 최근에 작성된 글인지를 판별, 그에 따라 다른 아이콘 이미지를 출력한다.
   (isset($_POST["number"]) ? $number=$_POST["number"] : $number="");

   $date_diff = time() -  $row['signdate'];
   if ($number == $my_uid) {
      echo("<img src=\"$iconDir/reading.gif\" border=\"0\">");
   } else {
      if ($date_diff < $time_limit) {
         if(!strcmp($my_thread,"A")) {
            echo("<img src=\"$iconDir/main_new.gif\" border=\"0\">");
         } else {
            echo("<img src=\"$iconDir/thread_new.gif\" border=\"0\">");
         }
      } else {
         if(!strcmp($my_thread,"A")) {
            echo("<img src=\"$iconDir/main.gif\" border=\"0\">");
         } else {
            echo("<img src=\"$iconDir/thread.gif\" border=\"0\">");
         }
      }
   }

   ##### 원칙상 제목에는 HTML 태그를 허용하지 않는다.
   $my_subject = htmlspecialchars($my_subject);

   ##### 제목을 검색시에는 검색어를 붉은색으로 출력한다.
   (isset($_POST["keyfield"]) ? $keyfield=$_POST["keyfield"] : $keyfield="");

   (isset($_POST["key"]) ? $key=$_POST["key"] : $key="");



   if(!strcmp($keyfield,"subject") && $key) {
      $my_subject = preg_replace("($key)", "<font color=red><b>$key</b></font>", $my_subject);
   }

   ##### 본문의 총 라인수를 계산한다.
   $line = explode("\n",$my_comment);
   $line_of_comment = sizeof($line);

   ##### [컬럼 2 : 게시물의 제목을 출력한다.]
   echo("&nbsp;<a href=\"viewbody.php?code=$code&page=$page&number=$my_uid&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='Physical number $my_uid, Thread number $my_fid, Included $line_of_comment lines';return true;\" onMouseOut=\"status=''\">$my_subject</a></td>\n");

   ##### [컬럼 3 : 글쓴이의 이메일주소를 출력한다.]
   if (!$my_email) {
      echo("<td bgColor=$LIST_TD_COLOR align=\"center\">$my_name</td>");
   } else {
      echo("<td bgColor=$LIST_TD_COLOR align=\"center\"><a href=\"mailto:$my_email\">$my_name</a></td>");
   }

   ##### [컬럼 4 : 게시물이 작성된 날짜정보를 출력한다.]
   echo("<td bgColor=$LIST_TD_COLOR align=\"center\">$my_signdate</td>");

   ##### [컬럼 5 : 게시물의 조회수를 출력한다.]
   echo("<td bgColor=$LIST_TD_COLOR align=\"center\">$my_ref</td>");

   ##### [컬럼 6 : 여러게시물을 보기 위해 필요한 체크박스를 출력한다.]
   echo("<td bgColor=$LIST_TD_COLOR align=\"center\"><input type=\"checkbox\" name=\"check[]\" value=\"$my_uid\"></td>");

   echo("</tr>");

   $article_num--;
}

echo("</table>");
?>

<table width="650" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
   <td colspan="6" align="center">
<?
##### 게시물 목록 하단의 각 페이지로 직접 이동할 수 있는 페이지 링크에 대한 설정을 한다.
$total_block = ceil($total_page/$page_per_block);
$block = ceil($page/$page_per_block);

$first_page = ($block-1)*$page_per_block;
$last_page = $block*$page_per_block;

if($total_block <= $block) {
   $last_page = $total_page;
}

##### 이전페이지블록에 대한 페이지 링크
if($block > 1) {
   $my_page = $first_page;
   echo("<a href=\"list.php?code=$code&page=$my_page&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='load previous $page_per_block pages';return true;\" onMouseOut=\"status=''\">[이전 ${page_per_block}개]</a>");
}

##### 현재의 페이지 블록범위내에서 각 페이지로 바로 이동할 수 있는 하이퍼링크를 출력한다.
for($direct_page = $first_page+1; $direct_page <= $last_page; $direct_page++) {
   if($page == $direct_page) {
      echo("<b>[$direct_page]</b>");
   } else {
      echo("<a href=\"list.php?code=$code&page=$direct_page&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='jump to page $direct_page';return true;\" onMouseOut=\"status=''\">[$direct_page]</a>");
   }
}

##### 다음페이지블록에 대한 페이지 링크
if($block < $total_block) {
   $my_page = $last_page+1;
   echo("<a href=\"list.php?code=$code&page=$my_page&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='load next $page_per_block pages';return true;\" onMouseOut=\"status=''\">[다음 ${page_per_block}개]</a>");
}
?>
   </td>
</tr>
</table>

<table width="650" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
   <td align="left">
   <a href="<?echo("$mainpage")?>" onMouseOver="status='go to main page';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/home.gif" width=35 height=35 border=0></a>
   <a href="list.php?code=<?echo("$code")?>" onMouseOver="status='reload articles';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/list.gif" width=35 height=35 border=0></a>
   </td>
   <td align="right">
   <a href="javascript:checkUserSelect()" onMouseOver="status='read selected articles';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/read.gif" width=35 height=35 border=0></a>
   <a href="postform.php?code=<?echo("$code")?>" onMouseOver="status='post a new article';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/post.gif" width=35 height=35 border=0></a>

<?
##### 이전페이지가 존재할 경우 이전페이지로 가는 링크를 활성화시킨다.
if ($page > 1) {
   $page_num = $page - 1;
   echo("<a href=\"list.php?code=$code&page=$page_num&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='previous page';return true;\" onMouseOut=\"status=''\"><img src=\"$iconDir/prev.gif\" width=35 height=35 border=0></a>");
} else {
   echo("<img src=\"$iconDir/prev.gif\" width=35 height=35 border=0>");
}

##### 게시물이 다음페이지에도 존재할 경우 다음페이지로 가는 링크를 활성화시킨다.
if ($total_record > $last) {
   $page_num = $page + 1;
   echo("<a href=\"list.php?code=$code&page=$page_num&keyfield=$keyfield&key=$encoded_key\" onMouseOver=\"status='next page';return true;\" onMouseOut=\"status=''\"><img src=\"$iconDir/next.gif\" width=35 height=35 border=0></a></td>");
} else {
   echo("<img src=\"$iconDir/next.gif\" width=35 hegiht=35 border=0></td>");
}
?>

</tr>
</table>

</form>

<center>
<form method="post" action="list.php?code=<?echo("$code")?>">
<font size=-1>
<select name="keyfield" size="1">
   <option value="subject">제목</option>
   <option value="name">이름</option>
   <option value="comment">내용</option>
</select>
</font>
<input type="text" size="20" maxlength="30" name="key">
<font size=2><input type="submit" value="검색"></font>
</form>
</center>
