<?php

include 'auth.php';

function ripRisk($str, $str1){
	if (!strcasecmp($str, $str1))	return true;
	if (stripos($str, $str1))	return true;
	if (!strncasecmp($str, $str1, strlen($str1)))	return true;
	return false;
}

date_default_timezone_set('Asia/Shanghai');
@$select_data = $_GET['sign'];

if (!isset($_GET['ip']))	$ip = $_SERVER['REMOTE_ADDR'];
else	$ip = $_GET['ip'];

if (!isset($_GET['port']))	$select_port = 0;
else $select_port = $_GET['port'];

$time=date("Y-m-d H:i:s");
if (!isset($select_data))
	die("Query Failed! </br> You must specifies a valid magic name.</br>");
if (ripRisk($select_data, "select") || ripRisk($select_data, "drop") || ripRisk($select_data, "alter") ) {
	die("Why did you tries to fuck me? Now show your ass!");
}
$handle = mysqli_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, "ipmap");
if (!mysqli_query($handle, "update m_global set ip = \"$ip\" where sign = \"$select_data\"")){
	printf("Query Failed! </br> You must specifies a valid magic name.</br>");
	die();
}
if (!mysqli_query($handle, "update m_global set port = \"$select_port\" where sign = \"$select_data\"")){
	printf("Query Failed! </br> You must specifies a valid magic name.</br>");
	die();
}
if (!mysqli_query($handle, "update m_global set lastUpdated = \"$time\" where sign = \"$select_data\"")){
	printf("Query Failed! </br> You must specifies a valid magic name.</br>");
	die();
}
	printf("%s:%d\n%s", $ip, $select_port, $time);
mysqli_close($handle);
?>
