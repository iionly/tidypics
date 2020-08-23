<?php
/**
 * Batch river view
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 *
 */

elgg_require_js('tidypics/tidypics');

$item = elgg_extract('item', $vars);
if (!($item instanceof ElggRiverItem)) {
	return;
}

$batch = $item->getObjectEntity();
if (!($batch instanceof TidypicsBatch)) {
	return;
}

$album = $batch->getContainerEntity();
if (!($album instanceof TidypicsAlbum)) {
	// something went quite wrong - this batch has no associated album
	return;
}

$subject = $item->getSubjectEntity();
if (!($subject instanceof ElggUser)) {
	return;
}

// Get images related to this batch
$images = elgg_get_entities([
	'relationship' => 'belongs_to_batch',
	'relationship_guid' => $batch->getGUID(),
	'inverse_relationship' => true,
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'offset' => 0,
]);

$album_link = elgg_view('output/url', [
	'href' => $album->getURL(),
	'text' => $album->getTitle(),
	'is_trusted' => true,
]);

$subject_link = elgg_view('output/url', [
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

if (count($images)) {
	$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics', 'tiny');

	$attachments = '';
	foreach ($images as $image) {
		$attachments .= elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
			'href' => 'ajax/view/photos/riverpopup?guid=' . $image->getGUID(),
			'title' => $image->title,
			'img_class' => 'tidypics-photo',
			'link_class' => 'tidypics-river-lightbox',
		]));
	}
	$vars['attachments'] = elgg_format_element('ul', ['class' => 'tidypics-river-list'], $attachments);
}

if (count($images) == 1) {
	// View the comments of the image
	$vars['item']->object_guid = $images[0]->guid;
	$responses = elgg_view('river/elements/responses', $vars);
	if ($responses) {
		$vars['responses'] = elgg_format_element('div', ['class' => 'elgg-river-responses'], $responses);
	}
	$image_link = elgg_view('output/url', [
		'href' => $images[0]->getURL(),
		'text' => $images[0]->getTitle(),
		'is_trusted' => true,
	]);
	$vars['summary'] = elgg_echo('river:object:tidypics_batch:created', [$subject_link, $image_link, $album_link]);
} else {
	// View the comments of the album
	$vars['item']->object_guid = $album->guid;
	$responses = elgg_view('river/elements/responses', $vars);
	if ($responses) {
		$vars['responses'] = elgg_format_element('div', ['class' => 'elgg-river-responses'], $responses);
	}
	$vars['summary'] = elgg_echo('river:object:tidypics_batch:multiple', [$subject_link, count($images), $album_link]);
}

echo elgg_view('river/elements/layout', $vars);
