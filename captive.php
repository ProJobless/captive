<?php
	include 'config.php';
	// connect to database
	$db = mysql_connect($cfg_dbhost, $cfg_dbuser, $cfg_dbpass);
	mysql_select_db($cfg_dbname, $db);
	// get config
	$cfg['title'] = "NEW";
	$cfg['logging'] = "0";
	$cfg_query = "select * from config";
	$res = mysql_query($cfg_query, $db);
	while ($row = mysql_fetch_assoc($res)) {
		$cfg[$row['k']] = $row['v'];
	}
	// log request if logging is enabled
	$log_id = 0;
	if ($cfg['logging'] == '1') {
		$ip = $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$r = exec('arp -an ' . $ip);
		$m = preg_split('/\s+/', $r);
		$mac = $m[3];
		$log_query = sprintf("insert into logs (ip, mac, agent) " .
			"values ('%s', '%s', '%s')", $ip, $mac, $agent);
		mysql_query($log_query, $db);
		$log_id = mysql_insert_id();
	}
?>
