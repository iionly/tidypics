<?php
/**
 * Find and delete any images that don't have an image file
 *
 * iionly@gmx.de
 */

elgg_require_js('tidypics/broken_images');

echo elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('search'),
	'id' => 'elgg-tidypics-broken-images',
]);
