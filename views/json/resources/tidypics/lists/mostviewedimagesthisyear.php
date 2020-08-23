<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$start = mktime(0, 0, 0, 1, 1, date("Y"));

$images = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'annotation_name' => 'tp_view',
	'annotation_sort_by_calculation' => 'count',
	'annotation_created_time_lower' => $start,
	'order_by' => [
		new \Elgg\Database\Clauses\OrderByClause('annotation_calculation', 'DESC'),
	],
]);

echo tidypics_slideshow_json_data($images);
