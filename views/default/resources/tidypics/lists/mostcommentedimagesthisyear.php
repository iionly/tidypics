<?php

/**
 * Most commented images of the current year
 *
 */
use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb(elgg_echo('tidypics:mostcommentedthisyear'));

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$start = mktime(0, 0, 0, 1, 1, date("Y"));
$end = time();

$db_prefix = elgg_get_config('dbprefix');
$result = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'selects' => ["count( * ) AS views"],
	'joins' => [
		new JoinClause('entities', 'ce', function(QueryBuilder $qb, $joined_alias, $main_alias) use ($user) {
			return $qb->merge([
				$qb->compare("$joined_alias.container_guid", '=', "$main_alias.guid"),
				$qb->compare("$joined_alias.subtype", '=', '"comment"'),
			], 'AND');
		}),
	],
	'wheres' => [
		function(QueryBuilder $qb, $alias) use ($start, $end) {
			return $qb->between($alias.'.time_created', $start, $end);
		},
	],
	'group_by' => 'e.guid',
	'order_by' => [new OrderByClause('views', 'DESC'),],
	'full_view' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'tidypics-gallery',
]);

$title = elgg_echo('tidypics:mostcommentedthisyear');

$logged_in_user = elgg_get_logged_in_user_entity();
if (tidypics_can_add_new_photos(null, $logged_in_user)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $logged_in_user->getGUID(),
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox elgg-lightbox',
	]);
}

// only show slideshow link if slideshow is enabled in plugin settings and there are images
if (elgg_get_plugin_setting('slideshow', 'tidypics') && !empty($result)) {
	elgg_require_js('tidypics/slideshow');
	elgg_register_menu_item('title', [
		'name' => 'slideshow',
		'id' => 'slideshow',
		'data-slideshowurl' => elgg_get_site_url() . "photos/mostcommentedthisyear",
		'data-limit' => $limit,
		'data-offset' => $offset,
		'href' => 'ajax/view/photos/galleria',
		'text' => '<i class="fa fa-fw fa-play"></i>',
		'title' => elgg_echo('album:slideshow'),
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox elgg-lightbox',
	]);
}

if (!empty($result)) {
	$content = $result;
} else {
	$content = elgg_echo('tidypics:mostcommentedthisyear:nosuccess');
}
$body = elgg_view_layout('content', [
	'filter_override' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'all']),
]);

// Draw it
echo elgg_view_page(elgg_echo('tidypics:mostcommentedthisyear'), $body);
