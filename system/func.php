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
	$fs = null;

	$current_path = null;
	$file_path = !empty($_REQUEST['f']) ? $_REQUEST['f'] : null;

	$logout = isset($_REQUEST['logout']);

	/**
	 * Load the configuration, current view mode, status etc.
	 */
	function Load()
	{
		global $config, $viewMode, $status, $current_path, $fs, $logout, $session;
		if (file_exists(PATH_TO_CONFIG))
			$config = include PATH_TO_CONFIG;
		
		if ($logout) 
			$session->destroy();		

		if (!empty($_REQUEST['d']))
			$current_path = $_REQUEST['d'];
		else
			$current_path = $config['root'];

		$viewMode = getViewMode();
		$status = getStatus();

		if ($viewMode == ViewMode::Browse 
		 || $viewMode == ViewMode::SingleFile)
			$fs = new FileSystem($current_path);
	}

	/**
	 * Returns the current view mode
	 * (the page that should be displayed)
	 * @return ViewMode
	 */
	function getViewMode()
	{
		global $config, $session, $file_path;
		if ( $session->alreadySet() && isConfigSet() ) {
			// settings mode?

			if (!is_null($file_path))
				return ViewMode::SingleFile;

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

	/**
	 * Returns the error type, if any, to be displayed to the user.
	 * @return ErrorType
	 */
	function getStatus()
	{
		if ( !isset($_REQUEST['username']) && !isset($_REQUEST['password']) )
			return ErrorType::None;
		if ( !isset($_REQUEST['username']) || !isset($_REQUEST['password']) )
			return ErrorType::InvalidInput;
		if ( !isValidUsername() && isset($_REQUEST['repeat_password']) )
			return ErrorType::InvalidUsername;
		if ( !isValidPassword() && isset($_REQUEST['repeat_password']) )
			return ErrorType::InvalidPassword;
		if ( isset($_REQUEST['repeat_password']) && $_REQUEST['repeat_password'] !== $_REQUEST['password'] )
			return ErrorType::PasswordsDontMatch;
		if ( !validLogin() )
			return ErrorType::InvalidLogin;

		return ErrorType::None;
	}

	/**
	 * Save $config to the configuration file.
	 * @return mixed
	 */
	function saveConfig()
	{
		if(!is_writable(dirname (PATH_TO_CONFIG) ))
			die (dirname (PATH_TO_CONFIG));

		global $config;
		$data = '<?php if(!defined("Access")) die("You cannot view this file"); ';
		$data .= 'return ' . var_export($config, true) . '; ?>';

		return file_put_contents(PATH_TO_CONFIG, $data);
	}

	/**
	 * Returns true if the configuration has already been set.
	 * @return boolean
	 */
	function isConfigSet()
	{
		global $config;
		return !empty($config['username']) || !empty($config['password']);
	}

	/**
	 * Returns true if a valid login request is made.
	 * @return Boolean
	 */
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

	/**
	 * Returns true if a valid setup request is made.
	 * @return Boolean
	 */
	function validSetup()
	{
		if ( empty($_REQUEST['username']) 
		  || empty($_REQUEST['password']) 
		  || empty($_REQUEST['repeat_password']))
			return false;

		// Regex-check the sent username-password
		if ( !isValidUsername() || !isValidPassword() ) 
			return false;
			
		return $_REQUEST['password'] === $_REQUEST['repeat_password'];
	}

	/**
	 * Returns true if the username matches our regex.
	 * @return boolean
	 */
	function isValidUsername ()
	{
		return preg_match('/^[a-z0-9_-]{4,16}$/i', $_REQUEST['username']);
	}

	/**
	 * Returns true if the password matches our regex.
	 * @return boolean
	 */
	function isValidPassword ()
	{
		return preg_match('/^[a-z0-9!@#$%-_]{6,18}$/', $_REQUEST['password']);
	}

	/**
	 * Returns an array of details for the given path (inc. content, size, type, line count)
	 * Currently only works for images and text files.
	 * @param  [type] $path
	 * @return [type]
	 */
	function fileData($path)
	{
		global $current_path, $fs;
		$path = $current_path . DIRECTORY_SEPARATOR . $path;

		if ($fs->isImage($path))
		{
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = $fs->read ($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

			return array(
				'content'	=> '<img src="'. $base64 .'"/>',
				'size'		=> $fs->size($path),
				'type'		=> 'image'
			);
		}
		if ($fs->isText($path))
		{
			$file = fopen($path, 'r');
			$lines = 0;

			if ($file) {
				$c = "";
				while (!feof($file)) {
					$filen = fgets($file, 4096);
					$c .= htmlspecialchars($filen) . "<br />";
					$lines++;
				}
				fclose($file);

				return array(
					'content'	=> $c,
					'size'		=> $fs->size($path),
					'lines'		=> $lines,
					'type'		=> 'text'
				);
			}
		}
		
		return array(
			'content'	=> 'cannot display file contents.',
			'size'		=> $fs->size($path),
			'type'		=> 'other'
		);
	}

	/**
	 * Returns the relative path to $fpath from the root folder set in $config.
	 * @param  string $fpath
	 * @return string
	 */
	function formatUrl($fpath)
	{
		if (is_null($fpath)) return $fpath;
		
		global $config;
		$root = realpath($config['root']);
		if (0 !== strpos($fpath, $root)) return $fpath;

		$fpath_a = explode (DIRECTORY_SEPARATOR, $fpath);
		$root_a = explode (DIRECTORY_SEPARATOR, $root);
		$url_a = array_splice ($fpath_a, count($root_a));

		return implode(DIRECTORY_SEPARATOR, $url_a);
	}

	/**
	 * Returns a formatted string from the given file length.
	 * @param  integer $size
	 * @return string
	 */
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

	/**
	 * Return date in hour format if the $mtime was within the day,
	 * or otherwise in day-month-year format.
	 * @param  integer $mtime
	 * @return string
	 */
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

		/**
		 * Returns true if the current session's values 
		 * are set and match the config.
		 * @return Boolean
		 */
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

		/**
		 * Start a new session, set session sid, username and password
		 */
		public function start()
		{		
			global $config;
			$_SESSION['sid'] = session_id();
			$_SESSION['username'] = $config['username'];
			$_SESSION['password'] = $config['password'];
		}

		/**
		 * Destroy the current session
		 */
		public function destroy()
		{
			return session_destroy();
		}
	}

	class ViewMode {
		const Setup = 0;
		const Login = 1;
		const Browse = 2;
		const Settings = 3;
		const SingleFile = 4;
	}

	class ErrorType{
		const None = 0;
		const InvalidInput = 1;
		const PasswordsDontMatch = 2;
		const PathInvalid = 3;
		const InvalidLogin = 4;
		const InvalidUsername = 5;
		const InvalidPassword = 6;
	}
?>