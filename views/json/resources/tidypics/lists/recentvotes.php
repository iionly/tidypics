<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$images = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'annotation_name' => 'fivestar',
	'order_by' => [
		new \Elgg\Database\Clauses\OrderByClause('n_table.time_created', 'DESC'),
	],
]);

echo tidypics_slideshow_json_data($images);
