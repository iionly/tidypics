<?php
/**
 * Group images module
 */
use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;

$group = $vars['entity'];
$group_guid = $group->getGUID();

if ($group->tp_images_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', [
	'href' => "photos/siteimagesgroup/$group_guid",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

$new_link = '';
if (tidypics_can_add_new_photos(null, $group)) {
	$new_link .= elgg_view('output/url', [
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $group_guid,
		'text' => elgg_echo("photos:addphotos"),
		'class' => 'elgg-lightbox',
		'link_class' => 'tidypics-selectalbum-lightbox',
		'is_trusted' => true,
	]);
}

$db_prefix = elgg_get_config('dbprefix');
elgg_push_context('groups');
$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'joins' => [
		new JoinClause('entities', 'u', function(QueryBuilder $qb, $joined_alias, $main_alias) use ($user) {
			return $qb->compare("$joined_alias.guid", '=', "$main_alias.container_guid");
		}),
	],
	'wheres' => [
		function(QueryBuilder $qb) use ($group_guid) {
			return $qb->compare('u.container_guid', '=', $group_guid);
		},
	],
	'order_by' => [new OrderByClause('e.time_created', 'DESC'),],
	'limit' => 12,
	'full_view' => false,
	'list_type_toggle' => false,
	'list_type' => 'gallery',
	'pagination' => false,
	'gallery_class' => 'tidypics-gallery-widget',
	'no_results' =>  elgg_echo('tidypics:none'),
]);
elgg_pop_context();

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('tidypics:mostrecent'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
]);
