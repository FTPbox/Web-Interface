<?php
	class FileSystem 
	{
		protected $directory;

		public function __construct($directory)
		{
			global $config;

			if ($directory !== $config['root'])
				$directory = $config['root'] . DIRECTORY_SEPARATOR . $directory;

			$directory = @realpath($directory) or die ("Access denied!");
			$root = @realpath($config['root']) or die ("Access denied!");
			
			if (0 !== strpos($directory, $root))
				$directory = $root;

			$this->directory = $directory;
		}

		/**
		 * Returns the absolute path to the parent folder of the given path
		 * @param string $path when null then current directory will be used		 
		 * @return string
		 */
		public function parent($path = NULL)
		{
			global $config;

			if (is_null($path))
				$path = $config['root'];
			else 
				$path = $this->directory . DIRECTORY_SEPARATOR . $path;

			if ($this->directory == realpath($path))
				return null;

			return dirname ($path);
		}

		/**
		 * returns an array of all items in the give path and details for each one (name, full path etc)
		 * if no path is specified, $directory will be used.
		 * @param  string $dir
		 * @return array
		 */
		public function getList($dir = NULL)
		{
			if (is_null($dir)) $dir = $this->directory;

			$list = array();

			$handle = @opendir($dir) or die("Could not open directory $dir");
		    while (false !== ($entry = readdir($handle))) {
		        if ($entry == "." || $entry == ".." || $entry  == "webint") continue;
		        $fpath = $dir."/".$entry;
		        $list[] = array(
		        	'name'     => $entry,
		        	'fpath'    => $fpath,
		        	'size'	   => $this->size($fpath),
		        	'mtime'	   => $this->modtime($fpath),
		        	'isdir'	   => $this->isDirectory($fpath),
		        	'ext'	   => $this->getExtension($fpath)
	        		);
		    }
		    closedir($handle);

			return $list;
		}

		/**
		 * Rename a file or directory
		 * @param  string $oldname
		 * @param  string $newname
		 * @return Boolean
		 */
		public function rename($oldname, $newname)
		{
			return rename($oldname, $newname);
		}

		/**
		 * Delete the given item.
		 * @param  string $item
		 * @return Boolean
		 */
		public function delete($item)
		{
			if ($this->isDirectory($item))
				return rmdir($item);
			else
				return unlink($item);
		}

		/**
		 * Returns the contents of a file.
		 * @param  string $item
		 * @return Boolean 
		 */
		public function read($item)
		{
			return file_get_contents ($item);
		}

		/**
		 * Returns true if the given path exists
		 * @param  string $item
		 * @return Boolean
		 */
		public function exists($item)
		{
			return file_exists($item);
		}

		/**
		 * Returns true if the given path is an existing directory
		 * @param  string $item
		 * @return Boolean
		 */
		public function directoryExists($item)
		{
			return $this->exists($item) && is_dir($item);
		}

		/**
		 * Returns true if the given path is a file
		 * @param  string  $item
		 * @return Boolean
		 */
		public function isFile($item)
		{
			return (bool) is_file($item);
		}

		/**
		 * Returns true if the given path is a directory
		 * @param  string $item
		 * @return Boolean
		 */
		public function isDirectory($item)
		{
			return (bool) is_dir($item);
		}

		/**
		 * Returns true if the given path is an image
		 * @param  string  $item
		 * @return Boolean
		 */
		public function isImage($item)
		{
			$s = getimagesize($item);
		    $type = $s[2];
		     
		    return in_array($type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP));
		}

		/**
		 * Returns true if the given path is a text file
		 * @param  string  $item
		 * @return Boolean
		 */
		public function isText($item)
		{			
			$mime = mime_content_type($item);			
			return FALSE !== strpos($mime, 'text');
		}

		/**
		 * Returns true if the given path is readable
		 * @param  string  $item
		 * @return Boolean
		 */
		public function canRead($item)
		{
			return is_readable($item);
		}

		/**
		 * Returns true if the given path is writable
		 * @param  string  $item
		 * @return Boolean
		 */
		public function canWrite($item)
		{
			return is_writeable($item);
		}

		/**
		 * Returns the size of the given file, 
		 * or false if path is a directory
		 * @param  string $item
		 * @return mixed
		 */
		public function size($item)
		{
			if ($this->isDirectory($item))
				return false;

			return filesize ($item);
		}

		/**
		 * Returns the last write time of the given item.
		 * @param  string $item
		 * @return integer
		 */
		public function modtime($item)
		{
			return filemtime($item);
		}

		/**
		 * Returns the extension of the given item
		 * @param  string $item
		 * @return string
		 */
		public function getExtension($item)
		{
			return pathinfo($item, PATHINFO_EXTENSION);
		}
	}
?>