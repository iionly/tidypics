<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$images = elgg_get_entities_from_annotations([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'annotation_name' => 'tp_view',
	'order_by_annotation' => "n_table.time_created desc",
]);

$images = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'annotation_name' => 'tp_view',
	'order_by' => [
		new \Elgg\Database\Clauses\OrderByClause('n_table.time_created', 'DESC'),
	],
]);

echo tidypics_slideshow_json_data($images);
