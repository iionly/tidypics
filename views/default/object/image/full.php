<?php
/**
 * Full view of an image
 *
 * @uses $vars['entity'] TidypicsImage
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$image = elgg_extract('entity', $vars);

if (!($image instanceof TidypicsImage)) {
	return true;
}

$album = $image->getContainerEntity();

$owner_icon = elgg_view_entity_icon($image->getOwnerEntity(), 'tiny');

$params = [
	'entity' => $image,
	'title' => false,
];
$list_body = elgg_view('object/elements/summary', $params);

$summary = elgg_view_image_block($owner_icon, $list_body, ['class' => 'mbl']);

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

if ($image->description) {
	echo elgg_view('output/longtext', [
		'value' => $image->description,
		'class' => 'mbl',
	]);
}

echo elgg_view_comments($image);
