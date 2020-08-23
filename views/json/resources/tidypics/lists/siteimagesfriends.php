<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$owner = elgg_get_logged_in_user_entity();
if ($friends = $owner->getFriends(['limit' => false])) {
	$images = elgg_get_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'relationship' => 'friend',
		'relationship_guid' => $owner->guid,
		'relationship_join_on' => 'owner_guid',
		'limit' => $limit,
		'offset' => $offset,
	]);
	echo tidypics_slideshow_json_data($images);
} else {
	echo json_encode([]);
}
