<?php
/**
 * Display an album in a gallery
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

$album_cover = elgg_view_entity_icon($album, 'small');

$album_title = $album->getTitle();
if (strlen($album_title) > 20) {
	$album_title = mb_substr($album_title, 0, 29, "utf-8") . "...";
}

$header = elgg_view('output/url', [
	'text' => $album_title,
	'href' => $album->getURL(),
	'is_trusted' => true,
	'class' => 'tidypics-heading',
]);

$container = $album->getContainerEntity();
if ($container) {
	$footer = elgg_echo('album:created_by');
	$footer .= elgg_view('output/url', [
		'text' => $album->getContainerEntity()->name,
		'href' => $album->getContainerEntity()->getURL(),
		'is_trusted' => true,
	]);
} else {
	$footer = elgg_echo('album:created_by') . ' - ';
}
$footer .= '<br>' . elgg_echo('album:num', [$album->getSize()]);

$params = [
	'footer' => elgg_format_element('div', ['class' => 'elgg-listing-summary-subtitle elgg-subtext'], $footer),
];
echo elgg_view_module('tidypics-album', $header, $album_cover, $params);
