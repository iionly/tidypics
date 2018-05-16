<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$images = elgg_get_entities_from_annotations([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'annotation_name' => 'fivestar',
	'order_by_annotation' => "n_table.time_created desc",
]);

echo tidypics_slideshow_json_data($images);
