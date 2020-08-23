<?php
/**
 * Tidypics Plugin
 *
 * Groups page Latest Photos widget for Widget Manager plugin
 *
 */

elgg_require_js('tidypics/tidypics');

$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->tp_latest_photos_count ?: 12;

$group_guid = elgg_get_page_owner_guid();
$group = get_entity($group_guid);

elgg_push_context('groups');
echo elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'wheres' => function(\Elgg\Database\QueryBuilder $qb, $alias) use($group_guid) {
		$qb->innerJoin($alias, 'entities', 'u', "u.guid = e.container_guid");
		$qb->orderBy('e.time_created', 'DESC');
		return $qb->compare('u.container_guid', '=', $group_guid, ELGG_VALUE_INTEGER);
	},
	'limit' => $limit,
	'full_view' => false,
	'list_type_toggle' => false,
	'list_type' => 'gallery',
	'pagination' => false,
	'gallery_class' => 'tidypics-gallery-widget',
	'no_results' => elgg_echo('tidypics:widget:no_images'),
]);
elgg_pop_context();

if (tidypics_can_add_new_photos(null, $group)) {
	echo elgg_view('output/url', [
		'href' => "ajax/view/photos/selectalbum?owner_guid=" . $group->getGUID(),
		'text' => elgg_echo('photos:addphotos'),
		'class' => 'elgg-lightbox',
		'link_class' => 'tidypics-selectalbum-lightbox',
		'is_trusted' => true,
	]);
}
