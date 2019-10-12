<?php
/**
 * Tidypics Batch Thumbnail Re-Sizing
 *
 * Called through ajax, but registered as an Elgg action.
 *
 */

$output = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() {

global $START_MICROTIME;
$batch_run_time_in_secs = 5;

// Offset is the total amount of images processed so far.
$offset = (int) get_input("offset", 0);
$limit = 5;

$image_lib = elgg_get_plugin_setting('image_lib', 'tidypics');
if (!$image_lib) {
	$image_lib = "GD";
}

_elgg_services()->db->disableQueryCache();

$success_count = 0;
$error_count_invalid_image = 0;
$error_count_recreate_failed = 0;

while ((microtime(true) - $START_MICROTIME) < $batch_run_time_in_secs) {
	$batch = elgg_get_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'limit' => $limit,
		'offset' => $offset,
	]);

	foreach ($batch as $image) {
		$filename = $image->getFilename();
		$container_guid = $image->container_guid;

		if (!$filename || !$container_guid) {
			$error_count_invalid_image++;
		} else {
			$prefix = "image/$container_guid/";
			$filestorename = substr($filename, strlen($prefix));

			switch ($image_lib) {
				case "ImageMagick":
					if (!tp_create_im_cmdline_thumbnails($image, $prefix, $filestorename)) {
						$error_count_recreate_failed++;
					} else {
						$success_count++;
					}
					break;
				case "ImageMagickPHP":
					if (!tp_create_imagick_thumbnails($image, $prefix, $filestorename)) {
						$error_count_recreate_failed++;
					} else {
						$success_count++;
					}
					break;
				default:
					if (!tp_create_gd_thumbnails($image, $prefix, $filestorename)) {
						$error_count_recreate_failed++;
					} else {
						$success_count++;
					}
					break;
			}
		}
	}
	$offset += $limit;
}


_elgg_services()->db->enableQueryCache();

$output = json_encode([
	'numSuccess' => $success_count,
	'numErrorsInvalidImage' => $error_count_invalid_image,
	'numErrorsRecreateFailed' => $error_count_recreate_failed,
]);
return $output;
});

return elgg_ok_response($output, '');
