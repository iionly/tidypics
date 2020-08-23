<?php

// get the photo entity
$image_guid = get_input('guid', false);

if (!$image_guid) {
	return true;
}

$image = get_entity($image_guid);

if (!($image instanceof TidypicsImage)) {
	return true;
}

$image->addView();

$owner_icon = elgg_view_entity_icon($image->getOwnerEntity(), 'tiny');
$title = elgg_view_title($image->getTitle());

$params = [
	'entity' => $image,
	'title' => false,
	'metadata' => '',
];
$list_body = elgg_view('object/elements/summary', $params);

$summary = elgg_view_image_block($owner_icon, $list_body, $params);

$content = elgg_format_element('div', ['style' => 'word-wrap:break-word;'], $title);
$content .= elgg_format_element('div', ['class' => ''], $summary);
$content .= elgg_format_element('div', ['class' => 'tidypicsRiverPhotoPopup'], elgg_view_entity_icon($image, 'large', ['img_class' => 'tidypics-photo']));
$content .= elgg_format_element('div', ['align' => 'center', 'class' => 'mts mbs'], elgg_view('output/url', [
	'href' => $image->getURL() . '#comments',
	'text' => elgg_echo('generic_comments:add'),
	'is_trusted' => true,
	'class' => 'elgg-button elgg-button-action',
]));
$content = elgg_format_element('div', ['class' => 'tidypics-river-popup'], $content);
echo $content;
