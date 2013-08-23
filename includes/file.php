<?php
	$fd = fileData($file_path);
	// Our url variables
	$url_back = "?d=" . formatUrl ($fs->parent($file_path));
	$url_home = "?d=";
?>

<div id="controls">  
	<a href="<?php echo $url_back; ?>" data-icon="&#xe010;"></a>  
	<a href="<?php echo $url_home; ?>" data-icon="&#xe005;"></a>
</div>

<div id='file_preview'>
	<div id='header'>
		<span class='name'><?php echo $file_path; ?></span>
		<span class='filesize'><?php echo formatSize($fd['size']); ?></span>
	<?php if ($fd['type'] === 'text'): ?>
		<span class='lines'><?php echo $fd['lines']; ?> lines</span>		
	<?php endif; ?>
	</div>
	
	<div id="file_contents">
		<?php echo $fd['content']; ?>
	</div>
</div>