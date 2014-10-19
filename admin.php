<?php include 'captive.php'; ?>
<html>
<head>
	<title><?= $cfg['title'] ?></title>
	<link rel="stylesheet" type="text/css" href="/style/captive.css">
</head>
<body>
<div class="content">

<div class="logo"><?= $cfg['title'] ?></div>
<br/>

<?php
	if (!$_POST['passwd'] || $cfg['passwd'] != $_POST['passwd']) {
?>

<form action="/admin.php" method="post">
Admin Password:<br/>
<input type="hidden" name="action" value="admin_login" />
<input type="password" size="32" maxlength="64" name="passwd" />
<input type="submit" value="Login" />
</form>

<?php
		mysql_close();
		exit();
	}
?>

<?php
	// admin is authenticated
	if ($_POST['action'] == 'delete') {
		$id = $_POST['delete_id'];
		$delete_query = sprintf("delete from posts where id = '%s'",
			mysql_real_escape_string($id));
		mysql_query($delete_query, $db);
	}
?>

Unique visitors:
<br/>

<?php
	$logs_query = "select * from logs order by ts desc";
	$res = mysql_query($logs_query, $db);
	if (mysql_num_rows($res) == 0) {
		echo "None.";
	}
	$ips = array();
	while ($row = mysql_fetch_assoc($res)) {
		if (in_array($row['ip'], $ips)) continue;
		array_push($ips, $row['ip']);
?>

<div class="post">
<span class="message"><?= strip_tags($row['agent']) ?></span><br/>
<span class="author"><?= strip_tags($row['ip']) ?> / <?= $row['mac'] ?> / <?= $row['ts'] ?></span><br/>
</div>

<?php
	}
?>

<br/><br/>

Recent messages:
<br/>

<?php
	$posts_query = "select * from posts where board != 'private' " .
		"order by ts desc";
	$res = mysql_query($posts_query, $db);
	if (mysql_num_rows($res) == 0) {
		echo "None.";
	}
	while ($row = mysql_fetch_assoc($res)) {
?>

<div class="post">
<form action="/admin.php" method="post">
<input type="hidden" name="passwd" value="<?= $cfg['passwd'] ?>" />
<input type="hidden" name="action" value="delete" />
<input type="hidden" name="delete_id" value="<?= $row['id'] ?>" />
<input type="submit" value="DELETE" />
</form>
<span class="message"><?= strip_tags($row['message']) ?></span><br/>
<span class="author"><?= strip_tags($row['name']) ?> @ <?= $row['ts'] ?></span><br/>
</div>

<?php
	}
?>

<?php
	mysql_close();
?>
</div>
</body>
</html>
