<?php
/**
 * Full view of an image
 *
 * @uses $vars['entity'] TidypicsImage
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$image = $photo = $vars['entity'];
$album = $image->getContainerEntity();

$owner_link = elgg_view('output/url', [
	'href' => "photos/owner/" . $photo->getOwnerEntity()->username,
	'text' => $photo->getOwnerEntity()->name,
]);
$author_text = elgg_echo('byline', [$owner_link]);
$date = elgg_view_friendly_time($image->time_created);
$categories = elgg_view('output/categories', $vars);

$owner_icon = elgg_view_entity_icon($photo->getOwnerEntity(), 'tiny');

$metadata = elgg_view_menu('entity', [
	'entity' => $vars['entity'],
	'handler' => 'photos',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
]);

$subtitle = "$author_text $date $categories";

$params = [
	'entity' => $photo,
	'title' => false,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'tags' => $tags,
];
$list_body = elgg_view('object/elements/summary', $params);

$params = ['class' => 'mbl'];
$summary = elgg_view_image_block($owner_icon, $list_body, $params);

echo $summary;

$body = '';
if ($album->getSize() > 1) {
	$body .= elgg_view('object/image/navigation', $vars);
}
$body .= elgg_view('photos/tagging/help', $vars);
$body .= elgg_view('photos/tagging/select', $vars);

$watermark_text = elgg_get_plugin_setting('watermark_text', 'tidypics', '');
if ($watermark_text) {
	$body .= elgg_view_entity_icon($image, 'large', [
		'href' => false,
		'img_class' => 'tidypics-photo',
		'link_class' => 'tidypics-lightbox',
	]);
} else {
	$body .= elgg_view_entity_icon($image, 'large', [
		'href' => $image->getIconURL('master'),
		'img_class' => 'tidypics-photo',
		'link_class' => 'tidypics-lightbox',
	]);
}
$body .= elgg_view('photos/tagging/tags', $vars);

echo elgg_format_element('div', ['class' => 'tidypics-photo-wrapper center'], $body);

if ($photo->description) {
	echo elgg_view('output/longtext', [
		'value' => $photo->description,
		'class' => 'mbl',
	]);
}

echo elgg_view_comments($photo);
