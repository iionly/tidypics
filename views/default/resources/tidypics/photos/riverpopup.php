<?php

// get the photo entity
$image_guid = elgg_extract('guid', $vars);

if (!$image_guid) {
	return true;
}

$image = get_entity($image_guid);

if (!($image instanceof TidypicsImage)) {
	return true;
}

$image->addView();

$owner_link = elgg_view('output/url', [
	'href' => "photos/owner/" . $image->getOwnerEntity()->username,
	'text' => $image->getOwnerEntity()->name,
]);
$author_text = elgg_echo('byline', [$owner_link]);
$date = elgg_view_friendly_time($image->time_created);

$comments_count = $image->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', [
		'href' => $image->getURL() . '#comments',
		'text' => $text,
		'is_trusted' => true,
	]);
} else {
	$comments_link = '';
}

$owner_icon = elgg_view_entity_icon($image->getOwnerEntity(), 'tiny');
$subtitle = "$author_text $date $comments_link";

$title = elgg_view_title($image->getTitle());

$params = [
	'entity' => $image,
	'title' => false,
	'metadata' => '',
	'subtitle' => $subtitle,
];
$list_body = elgg_view('object/elements/summary', $params);

$summary = elgg_view_image_block($owner_icon, $list_body, $params);

$content = elgg_format_element('div', ['style' => 'word-wrap:break-word;'], $title);
$content .= elgg_format_element('div', ['class' => ''], $summary);
$content .= elgg_format_element('div', ['align' => 'center', 'class' => 'mbm'], elgg_view_entity_icon($image, 'large', ['img_class' => 'tidypics-photo']));
$content .= elgg_format_element('div', ['align' => 'center', 'class' => 'mbn'], elgg_view('output/url', [
	'href' => $image->getURL() . '#comments',
	'text' => elgg_echo('generic_comments:add'),
	'is_trusted' => true,
	'class' => 'elgg-button elgg-button-action',
]));
$content = elgg_format_element('div', ['class' => 'tidypics-river-popup'], $content);
echo $content;
