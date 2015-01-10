<?php
/**
 * Tidypics Batch Thumbnail Re-Sizing
 *
 * Called through ajax, but registered as an Elgg action.
 *
 */

elgg_load_library('tidypics:resize');

set_time_limit(0);

$image_lib = elgg_get_plugin_setting('image_lib', 'tidypics');
if (!$image_lib) {
	$image_lib = "GD";
}

$total_images_processed = 0;
$error_invalid_image_info = 0;
$error_recreate_failed = 0;

// Make sure that images for disabled image entities also get re-sized
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$batch = new ElggBatch('elgg_get_entities', array(
	'type' => 'object',
	'subtype' => 'image',
	'limit' => false
));

foreach($batch as $image) {
	$total_images_processed++;

	$filename = $image->getFilename();
	$container_guid = $image->container_guid;

	if (!$filename || !$container_guid) {
		$error_invalid_image_info++;
	} else {
		$title = $image->getTitle();
		$prefix = "image/$container_guid/";
		$filestorename = substr($filename, strlen($prefix));

		switch ($image_lib) {
			case "ImageMagick":
				if (!tp_create_im_cmdline_thumbnails($image, $prefix, $filestorename)) {
					$error_recreate_failed++;
				}
				break;
			case "ImageMagickPHP":
				if (!tp_create_imagick_thumbnails($image, $prefix, $filestorename)) {
					$error_recreate_failed++;
				}
				break;
			default:
				if (!tp_create_gd_thumbnails($image, $prefix, $filestorename)) {
					$error_recreate_failed++;
				}
				break;
		}
	}
}

echo "<h3>" . elgg_echo('tidypics:resize_thumbnails:results') . "</h3>";

echo "<ul>";
echo "<li>" . elgg_echo('tidypics:resize_thumbnails:total_images_processed') . $total_images_processed . "</li>";
echo "<li>" . elgg_echo('tidypics:resize_thumbnails:error_invalid_image_info') . $error_invalid_image_info . "</li>";
echo "<li>" . elgg_echo('tidypics:resize_thumbnails:error_recreate_failed') . $error_recreate_failed . "</li>";
echo "</ul>";

echo "<br>";

access_show_hidden_entities($access_status);

exit;