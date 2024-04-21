<?php
/**
 * Photo navigation
 *
 * @uses $vars['entity']
 */

$image = elgg_extract('entity', $vars);

if (!($image instanceof TidypicsImage)) {
	return true;
}

$album = $image->getContainerEntity();
$previous_image = $album->getPreviousImage($image->getGUID());
$next_image = $album->getNextImage($image->getGUID());
$size = $album->getSize();
$index = $album->getIndex($image->getGUID());

if ($previous_image && $next_image) {
	$list_item = elgg_format_element('li', [], elgg_view('output/url', [
		'text' => elgg_view_icon('arrow-left', []),
		'href' => $previous_image->getURL(),
		'is_trusted' => true,
	]));

	$list_item .= elgg_format_element('li', [], elgg_format_element('span', [], elgg_echo('image:index', [$index, $size])));

	$list_item .= elgg_format_element('li', [], elgg_view('output/url', [
		'text' => elgg_view_icon('arrow-right', []),
		'href' => $next_image->getURL(),
		'is_trusted' => true,
	]));

	echo elgg_format_element('ul', ['class' => 'elgg-menu elgg-menu-hz tidypics-album-nav'], $list_item);
}
