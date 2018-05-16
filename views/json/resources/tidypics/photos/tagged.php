<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$guid = (int) get_input('guid', false);
$user = get_entity($guid);

if ($user instanceof ElggUser) {
	$images = elgg_get_entities_from_relationship([
		'relationship' => 'phototag',
		'relationship_guid' => $user->guid,
		'inverse_relationship' => false,
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'limit' => $limit,
		'offset' => $offset,
	]);
	echo tidypics_slideshow_json_data($images);
} else {
	echo json_encode([]);
}
