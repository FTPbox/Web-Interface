<?php
	require_once('system/func.php'); 

	Load();
?><!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet/less" type="text/css" href="less/style.less" />
		<script src="js/less-1.4.1.min.js" type="text/javascript"></script>
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script src="js/index.js" type="text/javascript"></script>
	</head>
	<body>
	<?php

		if ($viewMode === ViewMode::Setup)
		{
			include ('includes/setup.php');
		}
		else if ($viewMode === ViewMode::Login)
		{
			include ('includes/login.php');
		}
		else if ($viewMode === ViewMode::Browse)
		{
			include ('includes/list.php');
		}
		else if ($viewMode === ViewMode::SingleFile)
		{
			include ('includes/file.php');
		}

	?>
	</body>	
</html>