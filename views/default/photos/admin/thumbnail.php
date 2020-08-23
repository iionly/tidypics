<?php
/**
 * Tidypics thumbnail creation tool
 */

$plugin = elgg_get_plugin_from_id('tidypics');

$content = elgg_autop(elgg_echo('tidypics:thumbnail_tool_blurb'));

$form_vars = [
	'action' => 'action/photos/admin/create_thumbnail',
	'class' => 'elgg-form-settings',
];
$body_vars = [
	'entity' => $plugin,
];
$content .= elgg_view_form('photos/admin/create_thumbnail', $form_vars, $body_vars);

echo elgg_view_module('inline', elgg_echo('tidypics:thumbnail'), $content);

$count = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() {
	return elgg_get_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'count' => true,
	]);
});

$content2 = elgg_autop(elgg_echo('tidypics:settings:resize_thumbnails_instructions'));
$content2 .= elgg_autop(elgg_echo('tidypics:settings:resize_thumbnails_count', [$count]));

$form_vars2 = [
	'action' => 'action/photos/admin/resize_thumbnails',
	'class' => 'elgg-form-settings',
];
$body_vars2 = [
	'entity' => $plugin,
	'count' => $count,
];
$content2 .= elgg_view_form('photos/admin/resize_thumbnails', $form_vars2, $body_vars2);

echo elgg_view_module('inline', elgg_echo('tidypics:settings:resize_thumbnails_title'), $content2);
