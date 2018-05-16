<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$start = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$end = time();

$images = elgg_get_entities_from_annotation_calculation([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'annotation_name' => 'tp_view',
	'calculation' => 'count',
	'annotation_created_time_lower' => $start,
	'annotation_created_time_upper' => $end,
	'order_by' => 'annotation_calculation desc',
]);

echo tidypics_slideshow_json_data($images);
