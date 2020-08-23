<?php
/**
 * Group album module
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if (!$group->isToolEnabled('photos')) {
	return;
} 

$group_guid = $group->getGUID();

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:album:group', [
		'guid' => $group_guid,
	]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');
$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' =>  elgg_echo('tidypics:none'),
	'distinct' => false,
]);
elgg_pop_context();

$new_link = null;
if ($group->canWriteToContainer(0, 'object', TidypicsAlbum::SUBTYPE)) {
	$new_link = elgg_view('output/url', [
		'href' => elgg_generate_url('add:object:album', [
			'guid' => $group_guid,
		]),
		'text' => elgg_echo('add:object:album'),
		'is_trusted' => true,
	]);
}

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('collection:object:album:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
