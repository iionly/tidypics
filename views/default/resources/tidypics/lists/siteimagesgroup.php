<?php

/**
 * Group images
 *
 */

elgg_require_js('tidypics/tidypics');

$group_guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($group_guid, 'group');

elgg_group_tool_gatekeeper('tp_images', $group_guid);

$group = get_entity($group_guid);

elgg_push_collection_breadcrumbs('object', TidypicsImage::SUBTYPE, $group);

$title = elgg_echo('collection:object:image:group');

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

// grab the html to display the most recent images
$result = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'owner_guid' => null,
	'wheres' => function(\Elgg\Database\QueryBuilder $qb, $alias) use($group_guid) {
		$qb->innerJoin($alias, 'entities', 'u', "u.guid = e.container_guid");
		$qb->orderBy('e.time_created', 'DESC');
		return $qb->compare('u.container_guid', '=', $group_guid, ELGG_VALUE_INTEGER);
	},
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'gallery_class' => 'tidypics-gallery',
]);

if (TidypicsTidypics::tidypics_can_add_new_photos(null, $group)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $group_guid,
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

// only show slideshow link if slideshow is enabled in plugin settings and there are images
if (elgg_get_plugin_setting('slideshow', 'tidypics') && !empty($result)) {
	elgg_require_js('tidypics/slideshow');
	elgg_register_menu_item('title', [
		'name' => 'slideshow',
		'id' => 'slideshow',
		'data-slideshowurl' => elgg_get_site_url() . "photos/siteimagesgroup/$group_guid",
		'data-limit' => $limit,
		'data-offset' => $offset,
		'href' => 'ajax/view/photos/galleria',
		'text' => '<i class="far fa-images"></i>',
		'title' => elgg_echo('album:slideshow'),
		'item_class' => 'tidypics-slideshow-button',
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox',
	]);
}

if (!empty($result)) {
	$content = $result;
} else {
	$content = elgg_echo('tidypics:siteimagesgroup:nosuccess');
}
$body = elgg_view_layout('default', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'owner']),
]);

// Draw it
echo elgg_view_page($title, $body);
