<?php
	// Make sure the webUI is accessing the file.
	if(!defined('Access')) die("You can't view this file");

	// Keep username?
	$user = isset($_REQUEST['username']) && !empty($_REQUEST['username']) ? $_REQUEST['username'] : 'username';	
?>

<form action="index.php" method="POST" id="login">

	<input name="username" type="text" onfocus="if (this.value=='username') this.value='';"
		value="<?php echo $user; ?>" />

	<input name="password" value="password" onfocus="if (this.value=='password') this.value=''; this.type='password'" />
				
	<input type="submit" value="Login"/>
	
</form>

<?php if ($status === ErrorType::InvalidLogin): ?>
	<label class="error_msg">Invalid account, please try again.</label>
<?php endif; ?>