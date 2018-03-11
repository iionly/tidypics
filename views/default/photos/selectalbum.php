<?php

/**
 * Tidypics plugin
 *
 * Selection of album to upload new images to
 *
 * (c) iionly 2013-2015
 * Contact: iionly@gmx.de
 * Website: https://github.com/iionly
 * License: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 *
 */
$owner_guid = get_input('owner_guid', elgg_get_logged_in_user_guid());
$owner = get_entity($owner_guid);
if (!($owner instanceof ElggUser || $owner instanceof ElggGroup)) {
	$owner = elgg_get_logged_in_user_entity();
}

if (!tidypics_can_add_new_photos(null, $owner)) {
	echo elgg_format_element('p', [
			'class' => 'elgg-help-text tidypics-selectalbum',
		],
		elgg_echo('tidypics:album_select:no_results')
	);
	return;
}

$albums = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'container_guid' => $owner->getGUID(),
	'limit' => false,
	'batch' => true
]);

$album_options = [];
if ($owner->canWriteToContainer(0, 'object', TidypicsAlbum::SUBTYPE)) {
	$album_options[-1] = elgg_echo('album:create');
}

foreach ($albums as $album) {
	$album_title = $album->getTitle();
	if (strlen($album_title) > 50) {
		$album_title = mb_substr($album_title, 0, 47, "utf-8") . "...";
	}
	$album_options[$album->guid] = $album_title;
}

$form_vars = [
	'action' => 'action/photos/image/selectalbum',
	'class' => 'tidypics-selectalbum',
];
$body_vars = [
	'owner' => $owner,
	'album_options' => $album_options,
];
echo elgg_view_form('photos/selectalbum', $form_vars, $body_vars);
