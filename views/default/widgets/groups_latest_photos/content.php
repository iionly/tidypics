<?php
use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;

/**
 * Tidypics Plugin
 *
 * Groups page Latest Photos widget for Widget Manager plugin
 *
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->tp_latest_photos_count;
if ($limit < 1) {
	$limit = 12;
}

$group = elgg_get_page_owner_entity();
$group_guid =  $group->getGUID();

$db_prefix = elgg_get_config('dbprefix');

$prev_context = elgg_get_context();
elgg_set_context('groups');
$image_html = elgg_list_entities([
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
	'limit' => $limit,
	'full_view' => false,
	'list_type_toggle' => false,
	'list_type' => 'gallery',
	'pagination' => false,
	'gallery_class' => 'tidypics-gallery-widget',
]);
elgg_set_context($prev_context);

if (tidypics_can_add_new_photos(null, $group)) {
	$image_html .= elgg_view('output/url', [
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $group_guid,
		'text' => elgg_echo("photos:addphotos"),
		'class' => 'elgg-lightbox',
		'link_class' => 'tidypics-selectalbum-lightbox',
		'is_trusted' => true,
	]);
}

echo $image_html;
