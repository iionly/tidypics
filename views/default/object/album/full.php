<?php
/**
 * Full view of an album
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

$owner = $album->getOwnerEntity();

$owner_icon = elgg_view_entity_icon($owner, 'tiny');

$params = [
	'entity' => $album,
	'title' => false,
];
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

$body = '';
if ($album->description) {
	$body = elgg_view('output/longtext', [
		'value' => $album->description,
		'class' => 'mbm',
	]);
}

$album_content = $album->viewImages();
if ($album_content) {
	$body .=  $album_content;
} else {
	$body .= elgg_echo('tidypics:album:nosuccess');
}

echo elgg_view('object/elements/full', [
	'entity' => $album,
	'icon' => $owner_icon,
	'summary' => $summary,
	'body' => $body,
]);
