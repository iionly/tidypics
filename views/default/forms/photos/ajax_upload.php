<?php
/**
 * Tidypics ajax upload form body
 *
 * @uses $vars['entity']
 */

$album = $vars['entity'];

$ts = time();
$batch = time();
$tidypics_token = generate_action_token($ts);
$basic_uploader_url = current_page_url() . '/basic';

$maxfilesize = (float) elgg_get_plugin_setting('maxfilesize', 'tidypics');
if (!$maxfilesize) {
	$maxfilesize = 5;
}
$maxfilesize_int = (int) $maxfilesize;
$max_uploads = (int) elgg_get_plugin_setting('max_uploads', 'tidypics');
if (!$max_uploads) {
	$max_uploads = 10;
}
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
if (!$client_image_width) {
	$client_image_width = 2000;
}
$client_image_height = (int) elgg_get_plugin_setting('client_image_height', 'tidypics');
if (!$client_image_height) {
	$client_image_height = 2000;
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

$content .= elgg_autop(elgg_echo('tidypics:uploader:no_flash'));

echo elgg_format_element('div', [
	'id' => 'uploader',
	'data-maxfilesize' => $maxfilesize_int,
	'data-maxnumber' => $max_uploads,
	'data-client-resizing' => $client_resizing,
	'data-remove-exif' => $remove_exif,
	'data-client-width' => $client_image_width,
	'data-client-height' => $client_image_height,
], $content);
