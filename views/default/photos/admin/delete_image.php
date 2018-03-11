<?php
/**
 * Deletion of a Tidypics image by GUID provided (if image entry does not get properly displayed on site and delete button can not be reached)
 *
 * iionly@gmx.de
 */

$plugin = elgg_get_plugin_from_id('tidypics');

$content = elgg_autop(elgg_echo('tidypics:delete_image_blurb'));

$form_vars = [
	'action' => 'action/photos/admin/delete_image',
	'class' => 'elgg-form-settings',
];
$body_vars = [
	'entity' => $plugin,
];
$content .= elgg_view_form('photos/admin/delete_image', $form_vars, $body_vars);

echo elgg_view_module('inline', elgg_echo('tidypics:delete_image'), $content);


$content2 = elgg_autop(elgg_echo('tidypics:utilities:broken_images:blurb'));

$form_vars2 = [
	'action' => 'action/photos/admin/broken_images',
	'class' => 'elgg-form-settings',
];
$body_vars2 = [
	'entity' => $plugin,
];
$content2 .= elgg_view_form('photos/admin/broken_images', $form_vars2, $body_vars2);

$content2 .= '<div id="elgg-tidypics-broken-images-results"></div>';

echo elgg_view_module('inline', elgg_echo('tidypics:utilities:broken_images'), $content2);
