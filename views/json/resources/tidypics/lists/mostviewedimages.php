<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$images = elgg_get_entities_from_annotation_calculation([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'annotation_name' => 'tp_view',
	'calculation' => 'count',
	'order_by' => 'annotation_calculation desc',
]);

echo tidypics_slideshow_json_data($images);
