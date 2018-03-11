<?php
/**
 * Photo navigation
 *
 * @uses $vars['entity']
 */

$photo = $vars['entity'];

$album = $photo->getContainerEntity();
$previous_photo = $album->getPreviousImage($photo->getGUID());
$next_photo = $album->getNextImage($photo->getGUID());
$size = $album->getSize();
$index = $album->getIndex($photo->getGUID());

if ($previous_photo && $next_photo) {
	$list_item = elgg_format_element('li', [], elgg_view('output/url', [
		'text' => elgg_view_icon('arrow-left'),
		'href' => $previous_photo->getURL(),
		'is_trusted' => true,
	]));

	$list_item .= elgg_format_element('li', [], elgg_format_element('span', [], elgg_echo('image:index', [$index, $size])));

	$list_item .= elgg_format_element('li', [], elgg_view('output/url', [
		'text' => elgg_view_icon('arrow-right'),
		'href' => $next_photo->getURL(),
		'is_trusted' => true,
	]));

	echo elgg_format_element('ul', ['class' => 'elgg-menu elgg-menu-hz tidypics-album-nav'], $list_item);
}
