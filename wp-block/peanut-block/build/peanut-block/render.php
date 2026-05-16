<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
if (!class_exists( '\Peanut\sys\ViewModelManager' ) ) {

	print '<p>Error: Peanut system is not initialized.</p>';
}
else  if ( ! empty( $attributes['viewmodel'] ) ) {
	\Peanut\sys\ViewModelManager::getViewModelSettings(
		$attributes['viewmodel'],
		$attributes['vmcontext'] ?? null,
		$attributes['debugMode'] ?? false
	);
	if (!empty($attributes['debugMode'])) {
		print('<p>VM: '.$attributes['viewmodel'].'</p>');
		var_dump($attributes['viewmodel']);
	}
	else {
		// \Peanut\sys\ViewModelManager::RenderViewModel($attributes['viewmodel']);
		print ('<p>View to be rendered here</p>');
	}
}
else {
	print '<p>No viewmodel specified</p>';
}

/*if ( is_admin() || is_customize_preview() ) {
	echo get_block_wrapper_attributes();
	$content = sprintf(
		'<p>Peanut block<br>VM: %s</p>',$attributes['viewmodel']
	);
}
else {
	$content = '<p>VIEW GOES HERE</p>';
}
print $content;*/
?>
