<?php
use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$container_guid = elgg_extract('guid', $vars);
elgg_set_page_owner_guid($container_guid);
$container = get_entity($container_guid);

if ($container instanceof ElggGroup) {
	$db_prefix = elgg_get_config('dbprefix');
	$images = elgg_get_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'owner_guid' => null,
		'joins' => [
			new JoinClause('entities', 'u', function(QueryBuilder $qb, $joined_alias, $main_alias) use ($user) {
				return $qb->compare("$joined_alias.guid", '=', "$main_alias.container_guid");
			}),
		],
		'wheres' => [
			function(QueryBuilder $qb) use ($container_guid) {
				return $qb->compare('u.container_guid', '=', $container_guid);
			},
		],
		'order_by' => [new OrderByClause('e.time_created', 'DESC'),],
		'limit' => $limit,
		'offset' => $offset,
	]);
	echo tidypics_slideshow_json_data($images);
} else {
	echo json_encode([]);
}
