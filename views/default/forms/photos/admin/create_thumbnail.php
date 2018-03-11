<?php
/**
 * Re-create the thumbnails of a single image
 *
 * iionly@gmx.de
 */

elgg_require_js('tidypics/create_thumbnail');

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('tidypics:settings:im_id'),
	'name' => 'guid',
	'required' => true,
	'min' => 1,
	'step' => 1,
]);

echo '<div id="elgg-tidypics-im-results"></div>';

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('submit'),
]);

elgg_set_form_footer($footer);
