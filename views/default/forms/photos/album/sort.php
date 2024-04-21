<?php
/**
 * Album sorting view
 */

$album = $vars['album'];
$image_guids = $album->getImageList();

echo elgg_autop(elgg_echo('tidypics:sort:instruct'));

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guids',
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'album_guid',
	'value' => $album->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);

$img_list = '';
foreach ($image_guids as $image_guid) {
	$image = get_entity($image_guid);
	$img = elgg_view('output/img', [
		'src' => $image->getIconURL(),
	]);
	$img_list .= elgg_format_element('li', ['class' => 'elgg-mam', 'id' => $image_guid], $img);
}
$footer .= elgg_format_element('ul', ['class' => 'elgg-gallery', 'id' => 'tidypics-sort'], $img_list);

elgg_set_form_footer($footer);
