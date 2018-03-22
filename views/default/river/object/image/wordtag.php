<?php
/**
 * Image word tag river view
 */

elgg_require_js('tidypics/tidypics');

$tagger = $vars['item']->getSubjectEntity();
$tagged_image = $vars['item']->getObjectEntity();
$annotation = $vars['item']->getAnnotation();
if (!$annotation) {
	return;
}
$image = get_entity($annotation->entity_guid);
// viewer may not have permission to view image
if (!$image) {
	return;
}
$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics');
if(!$preview_size) {
	$preview_size = 'tiny';
}

$attachments = elgg_format_element('ul', ['class' => 'tidypics-river-list'], 
	elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
		'href' => 'photos/riverpopup/' . $image->getGUID(),
		'title' => $image->title,
		'img_class' => 'tidypics-photo',
		'link_class' => 'tidypics-river-lightbox',
	]))
);

$tagger_link = elgg_view('output/url', [
	'href' => $tagger->getURL(),
	'text' => $tagger->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

$image_link = elgg_view('output/url', [
	'href' => $image->getURL(),
	'text' => $image->getTitle(),
	'is_trusted' => true,
]);

$value = $annotation->value;
$tag = unserialize($value);
$tag_array = string_to_tag_array($tag->value);
$message = elgg_view('output/tags', ['value' => $tag_array]);
if (count($tag_array) > 1) {
	$summary = elgg_echo('image:river:wordtagged', [$tagger_link, $image_link]);
} else {
	$summary = elgg_echo('image:river:wordtagged_single', [$tagger_link, $image_link]);
}

echo elgg_view('river/elements/layout', [
	'item' => $vars['item'],
	'attachments' => $attachments,
	'message' => $message,
	'summary' => $summary
]);
