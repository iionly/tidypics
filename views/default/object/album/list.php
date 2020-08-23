<?php
/**
 * Display an album as an item in a list
 *
 * @uses $vars['entity'] TidypicsAlbum
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$album = elgg_extract('entity', $vars);

if (!($album instanceof TidypicsAlbum)) {
	return true;
}

$title = elgg_view('output/url', [
	'text' => $album->getTitle(),
	'href' => $album->getURL(),
]);

$params = [
	'entity' => $album,
	'title' => $title,
	'metadata' => null,
];
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

$icon = elgg_view_entity_icon($album, 'tiny');

echo $header = elgg_view_image_block($icon, $summary);
