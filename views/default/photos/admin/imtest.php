<?php
/**
 * Test the location of ImageMagick
 */

$plugin = elgg_get_plugin_from_id('tidypics');

$content = elgg_autop(elgg_echo('tidypics:lib_tools:testing'));

$form_vars = [
	'action' => 'action/photos/admin/imtest',
	'class' => 'elgg-form-settings',
];
$body_vars = [
	'entity' => $plugin,
];
$content .= elgg_view_form('photos/admin/imtest', $form_vars, $body_vars);

echo elgg_view_module('inline', elgg_echo('tidypics:imtest'), $content);
