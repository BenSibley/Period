<?php

if ( ! is_archive() ) {
	return;
}

$icon_class = 'folder-open';

if ( is_tag() ) {
	$icon_class = 'tag';
} elseif ( is_author() ) {
	$icon_class = 'user';
} elseif ( is_date() ) {
	$icon_class = 'calendar';
}
?>

<div class='archive-header'>
	<h2>
		<i class="fa fa-<?php echo $icon_class; ?>"></i>
		<?php the_archive_title(); ?>
	</h2>
	<?php the_archive_description(); ?>
</div>