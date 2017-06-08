<?php

$dbc = new mysqli('172.30.21.214','navyism','a1234', 'uptrans');





if($dbc->connect_error) {
  die('Connect Error:('.$dbc->connect_errno.') '.$dbc->connect_error);

}

print 'mysqli  클래스를 통해 접속이 성공하였습니다.';
?>
