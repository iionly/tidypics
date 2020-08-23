<?php
/**
 * Group images module
 */

elgg_require_js('tidypics/tidypics');

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if (!$group->isToolEnabled('tp_images')) {
	return;
} 

$group_guid = $group->getGUID();

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:image:group', [
		'guid' => $group_guid,
	]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

$new_link = null;
if (tidypics_can_add_new_photos(null, $group)) {
	$new_link .= elgg_view('output/url', [
		'href' => "ajax/view/photos/selectalbum?owner_guid=" . $group_guid,
		'text' => elgg_echo("photos:addphotos"),
		'class' => 'elgg-lightbox',
		'link_class' => 'tidypics-selectalbum-lightbox',
		'is_trusted' => true,
	]);
}

elgg_push_context('groups');
$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'wheres' => function(\Elgg\Database\QueryBuilder $qb, $alias) use($group_guid) {
		$qb->innerJoin($alias, 'entities', 'u', "u.guid = e.container_guid");
		$qb->orderBy('e.time_created', 'DESC');
		return $qb->compare('u.container_guid', '=', $group_guid, ELGG_VALUE_INTEGER);
	},
	'limit' => 12,
	'full_view' => false,
	'list_type_toggle' => false,
	'list_type' => 'gallery',
	'pagination' => false,
	'gallery_class' => 'tidypics-gallery-widget',
	'no_results' => elgg_echo('tidypics:widget:no_images'),
]);
elgg_pop_context();

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('collection:object:image:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
