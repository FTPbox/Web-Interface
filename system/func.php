<?php
	define('Access', TRUE);
	define('PATH_TO_CONFIG', 'data/config.php');

	require_once 'system/FileSystem.php';

	$config = array(
		 'username' => '',
		 'password' => '',
		 'root'		=> '..'
		);
	$viewMode = ViewMode::Setup;
	$status = ErrorType::None;

	$session = new Session();

	$current_path = null;

	function Load()
	{
		global $config, $viewMode, $status, $current_path;
		if (file_exists(PATH_TO_CONFIG))
			$config = include PATH_TO_CONFIG;

		$session = new Session();

		$viewMode = getViewMode();
		$status = getStatus();

		if (!empty($_REQUEST['d']))
			$current_path = $_REQUEST['d'];
		else
			$current_path = $config['root'];
	}

	function getViewMode()
	{
		global $config, $session;
		if ( $session->alreadySet() && isConfigSet() ) {
			// settings mode?

			return ViewMode::Browse;
		} 
		if ( validLogin() && isConfigSet() ) {
			$session->start();
			return ViewMode::Browse;
		}
		if ( isConfigSet() ) {
			return ViewMode::Login;
		}
		if ( validSetup() ) {
			$config = array(
				 'username' => $_REQUEST['username'],
				 'password' => sha1 ($_REQUEST['password']),
				 'root'		=> '..'
				);
			saveConfig();
			$session->start();
			return ViewMode::Browse;
		}
		return viewmode::Setup;
	}

	function getStatus()
	{
		if ( !isset($_REQUEST['username']) && !isset($_REQUEST['password']) )
			return ErrorType::None;
		if ( !isset($_REQUEST['username']) || !isset($_REQUEST['password']) )
			return ErrorType::InvalidInput;
		if ( isset($_REQUEST['repeat_password']) && $_REQUEST['repeat_password'] !== $_REQUEST['password'] )
			return ErrorType::PasswordsDontMatch;
		if ( !validLogin() )
			return ErrorType::InvalidLogin;

		return ErrorType::None;
	}

	function saveConfig()
	{
		if(!is_writable(dirname (PATH_TO_CONFIG) ))
			die (dirname (PATH_TO_CONFIG));

		global $config;
		$data = '<?php if(!defined("Access")) die("You cannot view this file"); ';
		$data .= 'return ' . var_export($config, true) . '; ?>';

		file_put_contents(PATH_TO_CONFIG, $data);
	}

	function isConfigSet()
	{
		global $config;
		return !empty($config['username']) || !empty($config['password']);
	}

	function validLogin()
	{
		if ( empty($_REQUEST)
		  || empty($_REQUEST['username']) 
		  || empty($_REQUEST['password']))
			return false;
		
		global $config;

		return $_REQUEST['username'] === $config['username']
			&& sha1($_REQUEST['password']) === $config['password'];
	}

	function validSetup()
	{
		if ( empty($_REQUEST['username']) 
		  || empty($_REQUEST['password']) 
		  || empty($_REQUEST['repeat_password']))
			return false;
			
		return $_REQUEST['password'] === $_REQUEST['repeat_password'];
	}

	function formatUrl($fpath)
	{
		global $config;
		$root = realpath($config['root']);
		if (0 !== strpos($fpath, $root)) return $fpath;

		$fpath_a = explode (DIRECTORY_SEPARATOR, $fpath);
		$root_a = explode (DIRECTORY_SEPARATOR, $root);
		$url_a = array_splice ($fpath_a, count($root_a));

		return implode(DIRECTORY_SEPARATOR, $url_a);
	}

	function formatSize($size)
	{		
		if (!$size)
			return ' ';
		
		if ($size < 1024)
			return $size." bytes";
		else if ($size < 1024*1024)
			return round(($size/1024), 1)." kb";
		else
			return round (($size/1024/1024), 1)." MB";
	}

	function formatModtime($mtime)
	{
		if (time() - $mtime < 60 * 60 * 12)
			return date('H:i', $mtime);
		else
			return date('d-m-Y', $mtime);
	}

	class Session 
	{
		public function __construct()
		{			
			session_start();
		}

		public function alreadySet()
		{			
			if ( !isset($_SESSION) 
				|| !isset($_SESSION['sid']) 
				|| !isset($_SESSION['password']) 
				|| !isset($_SESSION['username'])
				) return false;

			global $config;
			return $_SESSION['sid'] == session_id() 
				&& $_SESSION['username'] === $config['username']
				&& $_SESSION['password'] === $config['password'];
		}

		public function start()
		{		
			global $config;
			$_SESSION['sid'] = session_id();
			$_SESSION['username'] = $config['username'];
			$_SESSION['password'] = $config['password'];
		}

		public function destroy()
		{
			return session_destory();
		}
	}

	class ViewMode {
		const Setup = 0;
		const Login = 1;
		const Browse = 2;
		const Settings = 3;
	}

	class ErrorType{
		const None = 0;
		const InvalidInput = 1;
		const PasswordsDontMatch = 2;
		const PathInvalid = 3;
		const InvalidLogin = 4;
	}
?>