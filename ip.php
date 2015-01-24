<?php

//  This file is part of IP-Update.

//  IP-Update is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.

//  IP-Update is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.

//  You should have received a copy of the GNU General Public License
//  along with IP-Update.  If not, see <http://www.gnu.org/licenses/>.

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
