<?php
/**
 * Deletion of a Tidypics image by GUID provided (if image entry does not get properly displayed on site and delete button can not be reached)
 *
 * iionly@gmx.de
 */

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('tidypics:delete_image_id'),
	'name' => 'guid',
	'required' => true,
	'min' => 1,
	'step' => 1,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('delete'),
]);

elgg_set_form_footer($footer);
