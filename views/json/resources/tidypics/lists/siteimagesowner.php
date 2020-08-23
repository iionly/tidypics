<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$owner_guid = elgg_extract('guid', $vars);
$owner = get_entity($owner_guid);
if (!($owner instanceof ElggUser)) {
	if ($owner = elgg_get_logged_in_user_entity()) {
		$images = elgg_get_entities([
			'type' => 'object',
			'subtype' => TidypicsImage::SUBTYPE,
			'owner_guid' => $owner->guid,
			'limit' => $limit,
			'offset' => $offset,
		]);
		echo tidypics_slideshow_json_data($images);
	} else {
		echo json_encode([]);
	}
} else {
	echo json_encode([]);
}
