<?php
/**
 * Save album form body
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$title = elgg_extract('title', $vars, '');
$description = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, elgg_get_default_access());
$container_guid = elgg_extract('container_guid', $vars, elgg_get_page_owner_guid());
$guid = elgg_extract('guid', $vars, 0);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('album:title'),
	'name' => 'title',
	'value' => $title,
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('album:desc'),
	'name' => 'description',
	'value' => $description,
]);

echo elgg_view_field([
	'#type' => 'tags',
	'#label' => elgg_echo('tags'),
	'name' => 'tags',
	'value' => $tags,
]);

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

echo elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('access'),
	'name' => 'access_id',
	'value' => $access_id,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $guid,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'container_guid',
	'value' => $container_guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
