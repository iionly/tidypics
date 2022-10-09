<?php
/**
 * Tidypics ajax upload form body
 *
 * @uses $vars['entity']
 */

$album = $vars['entity'];

$ts = time();
$batch = time();
$tidypics_token = elgg()->csrf->generateActionToken($ts);
$basic_uploader_url = elgg_get_current_url() . '/basic';

$maxfilesize = (float) elgg_get_plugin_setting('maxfilesize', 'tidypics');
$maxfilesize_int = (int) $maxfilesize;
$max_uploads = (int) elgg_get_plugin_setting('max_uploads', 'tidypics');
$client_resizing = (bool) elgg_get_plugin_setting('client_resizing', 'tidypics');
if ($client_resizing) {
	$client_resizing = "true";
} else {
	$client_resizing = "false";
}
$remove_exif = (bool) elgg_get_plugin_setting('remove_exif', 'tidypics');
if ($remove_exif) {
	$remove_exif = "true";
} else {
	$remove_exif = "false";
}
$client_image_width = (int) elgg_get_plugin_setting('client_image_width', 'tidypics');
$client_image_height = (int) elgg_get_plugin_setting('client_image_height', 'tidypics');

$imageLib = elgg_get_plugin_setting('image_lib', 'tidypics');
if ($imageLib == 'ImageMagick') {
	$allowed_extensions = "jpg,jpeg,gif,png,webp";
} else {
	$allowed_extensions = "jpg,jpeg,gif,png";
}

echo elgg_autop(elgg_echo('tidypics:uploader:instructs', [$max_uploads, $maxfilesize]));

$content = elgg_view_field([
	'#type' => 'hidden',
	'name' => 'album_guid',
	'value' => $album->getGUID(),
]);

$content .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'batch',
	'value' => $batch,
]);

$content .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'tidypics_token',
	'value' => $tidypics_token,
]);

$content .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'user_guid',
	'value' => elgg_get_logged_in_user_guid(),
]);

$content .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'Elgg',
	'value' => session_id(),
]);

$content .= elgg_view_field([
	'#type' => 'hidden',
	'name' => 'plupload_language',
	'value' => TidypicsTidypics::tidypics_get_plugload_language(),
]);

$content .= elgg_autop(elgg_echo('tidypics:uploader:no_flash'));

echo elgg_format_element('div', [
	'id' => 'uploader',
	'data-maxfilesize' => $maxfilesize_int,
	'data-maxnumber' => $max_uploads,
	'data-client-resizing' => $client_resizing,
	'data-remove-exif' => $remove_exif,
	'data-client-width' => $client_image_width,
	'data-client-height' => $client_image_height,
	'data-allext' => $allowed_extensions,
], $content);
