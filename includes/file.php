<?php
	$fd = fileData($file_path);
?>
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