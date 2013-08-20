<div id='file_list'>
	<div id='header'>
		<span class='name'>Name</span>
		<span class='modtime'>Last Change</span>
		<span class='size'>Size</span>		
	</div>

	<ul>
	<?php foreach ($fs->getList() as $item): ?>
		<li>
			<div class='name'>
				<?php echo $item['name']; ?>
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