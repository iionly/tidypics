<?php
use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$db_prefix = elgg_get_config('dbprefix');
$images = elgg_get_entities([
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
	'group_by' => 'e.guid',
	'order_by' => [new OrderByClause('views', 'DESC'),],
]);

echo tidypics_slideshow_json_data($images);
