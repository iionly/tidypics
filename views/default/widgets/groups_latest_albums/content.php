<?php
/**
 * Tidypics Plugin
 *
 * Groups page Latest Albums widget for Widget Manager plugin
 *
 */

$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->tp_latest_albums_count ?: 6;

$container_guid = elgg_get_page_owner_guid();
$group = get_entity($container_guid);

elgg_push_context('groups');
echo elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'container_guid' => $container_guid,
	'limit' => $limit,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('tidypics:widget:no_albums'),
	'distinct' => false,
]);
elgg_pop_context();

if ($group->canWriteToContainer(0, 'object', TidypicsAlbum::SUBTYPE)) {
	echo elgg_view('output/url', [
		'href' => elgg_generate_url('add:object:album', [
			'guid' => $group->guid,
		]),
		'text' => elgg_echo('add:object:album'),
		'is_trusted' => true,
	]);
}
