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
else if (empty( $attributes['viewmodel'] ))  {
	print '<p>Error: View model code is missing.</p>';
}
else
{
	$debugMode = $attributes['debugMode'] ?? false;
	$vmCode = $attributes['viewmodel'];
	$vmContext = $attributes['vmcontext'] ?? null;
	$vmSettings = \Peanut\sys\ViewModelManager::getViewModelSettings(
		$vmCode,
		$vmContext
	);

	if (empty($vmSettings)) {
		if ($debugMode) {
			print "<p>No settings found for '$vmCode'</p>";
		}
	}
	else {
		if ($debugMode) {
			print "<p>VM: $vmCode</p><pre>";
			var_dump($vmSettings);
			print "</pre>";
		}
		else {
//			$viewContent = '<p>View to be rendered here</p>';
			$viewFile = DIR_ROOT . '/' . $vmSettings->view;
			$viewContent =  file_get_contents(DIR_ROOT . '/' . $vmSettings->view);
			print $viewContent;
		}
	}
	unset($debugMode);
	unset($vmCode);
	unset($viewContent);
	unset($vmSettings);
}
?>
