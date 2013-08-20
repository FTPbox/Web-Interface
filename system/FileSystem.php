<?php
	class FileSystem 
	{
		protected $directory;

		public function __construct($directory)
		{
			$this->directory = @realpath($directory) or die ("Access denied!");
		}

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
		        	'mtime'	   => $this->modtime($fpath)
	        		);
		    }
		    closedir($handle);

			return $list;
		}

		public function rename($oldname, $newname)
		{
			return rename($oldname, $newname);
		}

		public function delete($item)
		{
			if ($this->isDirectory($item))
				return rmdir($item);
			else
				return unlink($item);
		}

		public function read($item)
		{
			return file_get_contents($item);
		}

		public function exists($item)
		{
			return file_exists($item);
		}

		public function directoryExists($item)
		{
			return $this->exists($item) && is_dir($item);
		}

		public function isFile($item)
		{
			return (bool) is_file($item);
		}

		public function isDirectory($item)
		{
			return (bool) is_dir($item);
		}

		public function canRead($item)
		{
			return is_readable($item);
		}

		public function canWrite($item)
		{
			return is_writeable($item);
		}

		public function size($item)
		{
			if ($this->isDirectory($item))
				return false;

			return filesize ($item);
		}

		public function modtime($item)
		{
			return filemtime($item);
		}
	}
?>