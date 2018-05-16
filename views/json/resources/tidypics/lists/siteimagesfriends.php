<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$owner = elgg_get_logged_in_user_entity();
if ($friends = $owner->getFriends(['limit' => false])) {
	$friendguids = [];
	foreach ($friends as $friend) {
		$friendguids[] = $friend->getGUID();
	}
	$images = elgg_get_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'owner_guids' => $friendguids,
		'limit' => $limit,
		'offset' => $offset,
	]);
	echo tidypics_slideshow_json_data($images);
} else {
	echo json_encode([]);
}
