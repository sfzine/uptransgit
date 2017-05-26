<?
session_start();?>
<?php
//$page_title = 'Welcome !!!';
require_once("function.user.php");

include ('includes/header.html');
echo "<br><br><br>";

@printTitleImage(main);
// welcome users....
echo '<h1>Welcome';
if (isset($_SESSION['first_name']))  {
  echo ", {$_SESSION['first_name']}";
}
echo '!</h1>';
?>
<p>This is my web project homepage <br>
  Here i can make a small php project and i'd like to
  connect this project to the Cloud system later...
</p>




<?php
include ('includes/footer.html');
?>
