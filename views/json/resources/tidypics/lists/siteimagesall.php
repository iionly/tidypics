<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$images = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'owner_guid' => null,
	'limit' => $limit,
	'offset' => $offset,
]);

echo TidypicsTidypics::tidypics_slideshow_json_data($images);
