<?php
	require_once('system/func.php'); 

	Load();
?><!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet/less" type="text/css" href="less/style.less" />
		<script src="js/less-1.4.1.min.js" type="text/javascript"></script>
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
			$fs = new FileSystem($current_path);
			include ('includes/list.php');
		}

	?>
	</body>
</html>