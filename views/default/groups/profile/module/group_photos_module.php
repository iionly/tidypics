<?php
/**
 * Group album module
 */

$group = $vars['entity'];

if ($group->photos_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', [
	'href' => "photos/group/$group->guid/all",
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
]);
elgg_pop_context();

$new_link = '';
if ($group->canWriteToContainer(0, 'object', TidypicsAlbum::SUBTYPE)) {
	$new_link = elgg_view('output/url', [
		'href' => "photos/add/$group->guid",
		'text' => elgg_echo('photos:add'),
		'is_trusted' => true,
	]);
}

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo("tidypics:albums_mostrecent"),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
