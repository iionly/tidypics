<?php
/**
 * Form component for editing a single image
 *
 * @uses $vars['entity']
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$image = $vars['entity'];

$content = elgg_format_element('div', ['class' => 'elgg-image'], elgg_view_entity_icon($image, 'small', ['href' => false]));

$content .= elgg_format_element('div', ['class' => 'elgg-body'], elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'vertical',
	'fields' => [
		[
			'#type' => 'text',
			'#label' => elgg_echo('album:title'),
			'name' => 'title[]',
			'value' => $image->title,
		],
		[
			'#type' => 'longtext',
			'#label' => elgg_echo('caption'),
			'name' => 'caption[]',
		],
		[
			'#type' => 'tags',
			'#label' => elgg_echo("tags"),
			'name' => 'tags[]',
		],
		[
			'#type' => 'hidden',
			'name' => 'guid[]',
			'value' => $image->getGUID(),
		],
	],
]));

echo elgg_format_element('div', ['class' => 'elgg-image-block'], $content);
