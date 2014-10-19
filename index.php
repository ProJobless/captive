<?php include 'captive.php'; ?>
<html>
<head>
	<title><?= $cfg['title'] ?></title>
	<link rel="stylesheet" type="text/css" href="/style/captive.css">
	<meta name="viewport" content="width=device-width, initial-scale=0.5">
</head>
<body>
<div class="content">

<div class="logo"><?= $cfg['title'] ?></div>
<br/>

<?php
	if ($_POST['action'] == "post") {
		$board = strip_tags($_POST['board']);
		$name = strip_tags($_POST['name']);
		$message = strip_tags($_POST['message']);
		$post_query = sprintf("insert into posts " .
			"(board, name, message, log_id) " .
			"values ('%s', '%s', '%s', '%s')",
			mysql_real_escape_string($board),
			mysql_real_escape_string($name),
			mysql_real_escape_string($message),
			$log_id);
		mysql_query($post_query, $db);
		echo "Your post has been saved.<br/>";
	}
?>

Post a message:<br/>
<form action="/" method="post">
<input type="hidden" name="action" value="post" />
<input type="hidden" name="board" value="public" />
<textarea rows="5" name="message"></textarea><br/>
Name:<br/>
<input type="text" size="32" maxlength="128" name="name" value="Anonymous" />
<input type="submit" value="POST" />
</form>
<br/>

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
