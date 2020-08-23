<?php
/**
 * Image word tag river view
 */

elgg_require_js('tidypics/tidypics');

$item = elgg_extract('item', $vars);
if (!($item instanceof ElggRiverItem)) {
	return;
}

$tagger = $item->getSubjectEntity();
if (!($tagger instanceof ElggUser)) {
	return;
}

$tagged_image = $item->getObjectEntity();
if (!($tagged_image instanceof TidypicsImage)) {
	return;
}

$annotation = $item->getAnnotation();
if (!($annotation instanceof ElggAnnotation)) {
	return;
}

$image = get_entity($annotation->entity_guid);
// viewer may not have permission to view image
if (!($image instanceof TidypicsImage)) {
	return;
}

$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics', 'tiny');

$vars['attachments'] = elgg_format_element('ul', ['class' => 'tidypics-river-list'],
	elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
		'href' => 'ajax/view/photos/riverpopup?guid=' . $image->getGUID(),
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
$vars['message'] = elgg_view('output/tags', ['value' => $tag_array]);
if (count($tag_array) > 1) {
	$vars['summary'] = elgg_echo('river:object:image:wordtagged', [$tagger_link, $image_link]);
} else {
	$vars['summary'] = elgg_echo('river:object:image:wordtagged_single', [$tagger_link, $image_link]);
}

echo elgg_view('river/elements/layout', $vars);
