<?php
/**
 * Edit properties on a batch of images
 *
 * @uses $vars['batch'] ElggObject
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$batch = $vars['batch'];
$album = $batch->getContainerEntity();

$images = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'relationship' => 'belongs_to_batch',
	'relationship_guid' => $batch->getGUID(),
	'inverse_relationship' => true,
	'limit' => false,
]);

$img_list = '';
foreach ($images as $image) {
	$img_list .= elgg_format_element('li', [], elgg_view('forms/photos/batch/edit/image', ['entity' => $image]));
}
echo elgg_format_element('ul', [], $img_list);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
