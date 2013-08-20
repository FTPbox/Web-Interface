<?php
	// Make sure the webUI is accessing the file.
	if(!defined('Access')) die("You can't view this file");

	// Keep username?
	$user = isset($_REQUEST['username']) && !empty($_REQUEST['username']) ? $_REQUEST['username'] : 'username';
	
	// Any errors to show?
	$error_msg = null;
	switch($status)
	{
		case ErrorType::InvalidInput:
			$error_msg = "Input is invalid.";
			break;
		case ErrorType::PasswordsDontMatch:
			$error_msg = "Passwords do not match.";
			break;
		case ErrorType::PathInvalid:
			$error_msg = "The given path is invalid.";
			break;
	}
?>
<form action="index.php" method="POST" id="config">
	
	<input name="username" value="<?php echo $user; ?>" type="text" onfocus="if (this.value=='username') this.value='';" />
	<input name="password" value="password" onfocus="if (this.value=='password') this.value=''; this.type='password'" />
	<input name="repeat_password" value="repeat password" onfocus="if (this.value=='repeat password') this.value=''; this.type='password'" />		

	<input type="submit" value="Create Account"/>
	
</form>

<?php if (!is_null($error_msg)): ?>
	<label class="error_msg"><?php echo $error_msg; ?></label>
<?php endif; ?>