<?php

include 'auth.php';

if (!isset($_SERVER['PHP_AUTH_USER'])) {
	on_start:
    header('WWW-Authenticate: Basic realm="NSS IP-Update Service"');
    header('HTTP/1.1 401 Unauthorized');
    echo "<script>alert('Login incorrect.')</script>";
    die();
	}else{
	$hmysql = mysqli_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, "ipmap");
	$result = mysqli_query($hmysql, "SELECT * from m_global");
	while ($row = mysqli_fetch_assoc($result)){
		if ($row['sign'] == $_SERVER['PHP_AUTH_USER']){
			if	($row['passhash'] !== md5($_SERVER['PHP_AUTH_PW'])){
				echo "<script>alert('Login incorrect.')</script>";
				unset($_SERVER['PHP_AUTH_USER']);
				unset($_SERVER['PHP_AUTH_PW']);
				goto on_start;
			}else{
				$ip_str = $row['ip'];
				$ip_port = $row['port'];
				$ip_lastTimeUpdated = $row['lastUpdated'];
			}
		}
	}
}
if (!isset($ip_str)){
	echo "<script>alert('Login incorrect.')</script>";
	unset($_SERVER['PHP_AUTH_USER']);
	unset($_SERVER['PHP_AUTH_PW']);
	goto on_start;
}
printf("%s:%d\n%s", $ip_str, $ip_port, $ip_lastTimeUpdated);
mysqli_close($hmysql);
?>
