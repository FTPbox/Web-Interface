<?php	
	$url_back = "?d=" . formatUrl ($fs->parent());
	$url_home = "?d=";
?>
<div id="controls">  
	<a href="<?php echo $url_back; ?>" data-icon="&#xe010;"></a>  
	<a href="<?php echo $url_home; ?>" data-icon="&#xe005;"></a>

	<a href="?logout" class="system"   data-icon="&#xe003;"></a>
</div>

<div id='file_list'>
	<div id='header'>
		<span class='name'>Name</span>
		<span class='modtime'>Last Change</span>
		<span class='size'>Size</span>		
	</div>

	<ul>
	<?php foreach ($fs->getList() as $item): ?>
		<?php 
			$url = $item['isdir'] ? "?d=" : "?f=";
			$url .= formatUrl($item['fpath']);
			$icon = $item['isdir'] ? '&#xe013;' : extensionIcon($item['ext']);
		?>
		<li data-name="<?php echo $item['name']; ?>" data-time="<?php echo $item['mtime']; ?>" data-size="<?php echo $item['size']; ?>">
			<div class="icon" data-icon="<?php echo $icon; ?>">
			</div>
			<div class='name'>
				<a href="<?php echo $url; ?>">
					<?php echo $item['name']; ?>
				</a>
			</div>
			<div class='modtime'>
				<?php echo formatModtime($item['mtime']); ?>
			</div>
			<div class='size'>
				<?php echo formatSize($item['size']); ?>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
</div>