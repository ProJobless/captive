<?php include 'captive.php'; ?>
<html>
<head>
	<title><?= $cfg['title'] ?></title>
	<link rel="stylesheet" type="text/css" href="/style/captive.css">
</head>
<body>
<div class="content">

Setting up new site using database '<?= $cfg_dbname ?>' (see config.php).
<br/><br/>
<?php

	if ($_POST['action'] == "setup") {

		// create database
		$setup_query = "create database " . $cfg_dbname;
		mysql_query($setup_query, $db);
		mysql_select_db($cfg_dbname, $db);

		// create tables
		$setup_query = "create table config (" .
			"k varchar(128) unique, " .
			"v blob, " .
			"primary key (k))";
		mysql_query($setup_query, $db);

		$setup_query = "create table posts (" .
			"id int not null auto_increment, " .
			"board varchar(128), " .
			"name varchar(128), " .
			"message blob, " .
			"ts timestamp, " .
			"log_id int, " .
			"primary key (id))";
		mysql_query($setup_query, $db);

		$setup_query = "create table logs (" .
			"id int not null auto_increment, " .
			"ip varchar(64), " .
			"mac varchar(32), " .
			"agent varchar(255), " .
			"ts timestamp, " .
			"primary key (id))";
		mysql_query($setup_query, $db);

		// insert configuration
		$title = strip_tags($_POST['title']);
		$passwd = strip_tags($_POST['passwd']);
		$logging = strip_tags($_POST['logging']);

		$setup_query = sprintf("insert into config " .
			"values ('title', '%s')", mysql_real_escape_string($title));
		mysql_query($setup_query, $db);

		$setup_query = sprintf("insert into config " .
			"values ('passwd', '%s')", mysql_real_escape_string($passwd));
		mysql_query($setup_query, $db);

		$setup_query = sprintf("insert into config " .
			"values ('logging', '%s')", mysql_real_escape_string($logging));
		mysql_query($setup_query, $db);

		echo "Your site is now configured.<br/>";
		mysql_close();
		exit();

	}

	if ($cfg['passwd']) {
		echo "A site has already been setup with this database.";
		mysql_close();
		exit();
	}
?>

<form action="/setup.php" method="post">
<input type="hidden" name="action" value="setup" />

Set Admin Password:<br/>
<input type="password" size="32" maxlength="64" name="passwd" /><br/>

Set Site Title:<br/>
<input type="text" size="32" maxlength="64" name="title" /><br/>

Logging:<br/>
<select name="logging">
  <option value="1">YES</option>
  <option value="0">NO</option>
</select>

<input type="submit" value="COMPLETE SETUP" />
</form>

<?php
	mysql_close();
?>
</div>
</body>
</html>
