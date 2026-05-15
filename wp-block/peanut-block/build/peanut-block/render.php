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

if ( ! empty( $attributes['viewmodel'] ) && class_exists( '\Peanut\sys\ViewModelManager' ) ) {
	\Peanut\sys\ViewModelManager::getViewModelSettings(
		$attributes['viewmodel'],
		$attributes['vmcontext'] ?? null
	);
}
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php esc_html_e( 'Peanut Block – hello from a dynamic block!', 'peanut-block' ); ?>
	<?php if ( ! empty( $attributes['viewmodel'] ) ) : ?>
		<br />
		<small>VM: <?php echo esc_html( $attributes['viewmodel'] ); ?></small>
	<?php endif; ?>
	<?php if ( ! empty( $attributes['vmcontext'] ) ) : ?>
		<?php if ( ! empty( $attributes['viewmodel'] ) ) echo ' | '; ?>
		<small>Context: <?php echo esc_html( $attributes['vmcontext'] ); ?></small>
	<?php endif; ?>
</p>
