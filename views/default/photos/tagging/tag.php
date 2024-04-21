<?php
/**
 * Photo tag view
 *
 * @uses $vars['tag'] Tag object
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$tag = elgg_extract('tag', $vars);

$coords = json_decode('{' . $tag->coords . '}');

$annotation = elgg_get_annotation_from_id($tag->annotation_id);

if ($tag->type == 'user') {
	$user = get_entity($tag->value);
	$user_link = elgg_view('output/url', [
		'text' => $user->name,
		'href' => $user->getURL(),
	]);
	$tagger = get_entity($annotation->owner_guid);
	$tagger_link = elgg_view('output/url', [
		'text' => $tagger->name,
		'href' => $tagger->getURL(),
	]);
	$label = elgg_echo('tidypics:tags:membertag') . $user_link . elgg_echo('tidypics:tags:taggedby', [$tagger_link]);
} else {
	$label = elgg_echo('tidypics:tags:wordtags') . $tag->value;
}

$delete = '';
if ($annotation->canEdit()) {
	$url = elgg_http_add_url_query_elements('action/photos/image/untag', [
		'annotation_id' => $tag->annotation_id
	]);
	$delete = elgg_view('output/url', [
		'href' => $url,
		'text' => elgg_view_icon('delete', ['class' => 'float mas']),
		'confirm' => elgg_echo('tidypics:phototagging:delete:confirm')
	]);
}

$content = elgg_format_element('div', [
	'class' => 'tidypics-tag',
	'data-x1' => $coords->x1,
	'data-y1' => $coords->y1,
	'data-width' => $coords->width,
	'data-height' => $coords->height,
], $delete);
$content .= elgg_format_element('div', ['class' => 'elgg-module-popup tidypics-tag-label'], $label);

echo elgg_format_element('div', ['class' => 'tidypics-tag-wrapper'], $content);
