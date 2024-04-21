<?php
/**
 * Tidypics Thumbnail Creation Test
 *
 * Called through ajax, but registered as an Elgg action.
 *
 */

$guid = (int) get_input('guid');
$image = get_entity($guid);

if (!($image instanceof TidypicsImage)) {
	return elgg_error_response(elgg_echo('tidypics:thumbnail_tool:unknown_image'), REFERRER);
}

$filename = $image->getFilename();
$container_guid = $image->container_guid;
$album = get_entity($container_guid);
if (!$filename || !($album instanceof TidypicsAlbum)) {
	return elgg_error_response(elgg_echo('tidypics:thumbnail_tool:invalid_image_info'), REFERRER);
}

$title = $image->getTitle();
$prefix = "image/$container_guid/";
$filestorename = substr($filename, strlen($prefix));

$image_lib = elgg_get_plugin_setting('image_lib', 'tidypics');

// ImageMagick command line
if ($image_lib == 'ImageMagick') {
	if (!TidypicsResize::tp_create_im_cmdline_thumbnails($image, $prefix, $filestorename)) {
		trigger_error('Tidypics warning: failed to create thumbnails - ImageMagick command line', E_USER_WARNING);
		return elgg_error_response(elgg_echo('tidypics:thumbnail_tool:create_failed'), REFERRER);
	}

// imagick PHP extension
} else if ($image_lib == 'ImageMagickPHP') {
	if (!TidypicsResize::tp_create_imagick_thumbnails($image, $prefix, $filestorename)) {
		trigger_error('Tidypics warning: failed to create thumbnails - ImageMagick PHP', E_USER_WARNING);
		return elgg_error_response(elgg_echo('tidypics:thumbnail_tool:create_failed'), REFERRER);
	}
// gd
} else {
	if (!TidypicsResize::tp_create_gd_thumbnails($image, $prefix, $filestorename)) {
		trigger_error('Tidypics warning: failed to create thumbnails - GD', E_USER_WARNING);
		return elgg_error_response(elgg_echo('tidypics:thumbnail_tool:create_failed'), REFERRER);
	}
}

// check if image is in album's image list and add it as first if not
$list_album_images = $album->getImageList();
$key = array_search($guid, $list_album_images);
if ($key === false) {
	$image_array = [];
	array_push($image_array, $guid);
	$album->prependImageList($image_array);
}

$url = elgg_normalize_url("photos/thumbnail/$guid/large");

$output = json_encode([
	'guid' => $guid,
	'title' => $title,
	'thumbnail_src' => $url,
]);

return elgg_ok_response($output, elgg_echo('tidypics:thumbnail_tool:created'), REFERRER);
