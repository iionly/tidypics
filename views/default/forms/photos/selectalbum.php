<?php

/* @var $owner ElggUser */
$owner = elgg_extract('owner', $vars);
/* @var $album_options Array */
$album_options = (array) elgg_extract('album_options', $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'owner_guid',
	'value' => $owner->guid,
]);
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('tidypics:album_select'),
	'#help' => elgg_echo('tidypics:album_select_help'),
	'name' => 'album_guid',
	'options_values' => $album_options,
	'value' => '',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('tidypics:continue'),
]);

elgg_set_form_footer($footer);
