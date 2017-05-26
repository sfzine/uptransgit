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

<?php
##### 현재 조회하고 있는 글과 관련된 글 목록에 대한 결과레코드 세트를 얻는다.
$db = mysqli_select_db($conn,"uptrans");


$query = "SELECT uid,fid,name,email,subject,comment,signdate,ref,thread FROM $code WHERE fid = $my_fid ORDER BY thread";
$result = mysqli_query($conn, $query);
if (!$result) {
   error("QUERY_ERROR");
   exit;
}

##### 관련 게시물의 총 개수를 구한다.
$threaded_rows = mysqli_num_rows($result);

if($threaded_rows > 0) {

   ##### 관련 게시물의 일련번호중 첫번째 번호
   $article_num = $threaded_rows;
?>

<form name="signform" method="post" action="read.php?code=<?echo("$code")?>&page=<?echo("$page")?>&keyfield=<?echo("$keyfield")?>&key=<?echo("$encoded_key")?>">

<table width="650" border="0" align="center" cellspacing="0" cellpadding="0">
<tr>
   <td align="center">
<?
echo "관련 게시물 : <b>" . $threaded_rows . "</b> (Total <b>" . $threaded_rows . "</b> Articles)";
?>
   </td>
</tr>
</table>

<table border="0" width="650" align="center" cellspacing="1" cellpadding="3">
<tr>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=50><font color="#ffffff">번   호</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=340><font color="#ffffff">제  목</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=80><font color="#ffffff">글쓴이</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=80><font color="#ffffff">작성일</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=50><font color="#ffffff">조회수</font></td>
   <td align="center" bgColor=<?echo("$LIST_TH_COLOR")?> width=50><font color="#ffffff">선 택</font></td>
</tr>

<?
   $time_limit = 60*60*24*$notify_new_article;

   while($threaded_row = mysqli_fetch_array($result,MYSQL_ASSOC)) {

      ##### 각 게시물 레코드의 필드값을 변수에 저장한다.
      $my_uid = $threaded_row['uid'];
      $my_fid = $threaded_row['fid'];
      $my_name = $threaded_row['name'];
      $my_email = $threaded_row['email'];
      $my_subject = $threaded_row['subject'];
      $my_comment = $threaded_row['comment'];
      $my_signdate = date("y-m-d",$threaded_row['signdate']);
      $my_ref = $threaded_row['ref'];
      $my_thread = $threaded_row['thread'];

      ##### 제목과 본문에 대하여 테이블에 저장할 때(post.php) addslashes() 함수로 escape시킨 문자열을 원래대로 되돌려 놓는다.
      $my_subject = stripslashes($my_subject);
      $my_comment = stripslashes($my_comment);

      echo("<tr>");

      ##### [컬럼 1 : 게시물의 번호를 출력한다.]
      echo("   <td bgColor=$LIST_TD_COLOR align=\"center\">$article_num</td>");
      echo("   <td bgColor=$LIST_TD_COLOR>");

      ##### 응답의 단계에 따라 출력할 제목의 문자열을 안쪽으로 indent를 시킨다.
      $spacer = strlen($my_thread)-1;

      ##### 원글에 대한 답변글이 $reply_indent 값 이상이 되면 답변글의 출력 indent를 고정시킨다.
      if($spacer > $reply_indent) $spacer = $reply_indent;
      for($j = 0; $j < $spacer; $j++) {
         echo "&nbsp; ";
      }

      ##### 게시물의 작성시간으로부터 게시물이 최근에 작성된 글인지를 판별, 그에 따라 다른 아이콘 이미지를 출력한다.




      $date_diff = time() -  $threaded_row['signdate'];
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
}
?>

<table width="650" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
   <td align="center">
   <a href="list.php?code=<?echo("$code")?>" onMouseOver="status='reload articles';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/list.gif" width=35 height=35 border=0></a>
   <a href="postform.php?code=<?echo("$code")?>" onMouseOver="status='post a new article';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/post.gif" width=35 height=35 border=0></a>
   <a href="javascript:checkUserSelect()" onMouseOver="status='read selected articles';return true;" onMouseOut="status=''"><img src="<?echo("$iconDir")?>/read.gif" width=35 height=35 border=0></a>
   </td>
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
