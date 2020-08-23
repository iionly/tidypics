<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$start = strtotime("-1 months", mktime(0, 0, 0, date("m"), 1, date("Y")));
$end = mktime(0, 0, 0, date("m"), 0, date("Y"));

$images = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'annotation_name' => 'tp_view',
	'annotation_sort_by_calculation' => 'count',
	'annotation_created_time_lower' => $start,
	'annotation_created_time_upper' => $end,
	'order_by' => [
		new \Elgg\Database\Clauses\OrderByClause('annotation_calculation', 'DESC'),
	],
]);

echo tidypics_slideshow_json_data($images);
