<?php
/**
 * Batch river view; showing only the thumbnail of the first image of the badge
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

// Count images in batch
$images_count = elgg_get_entities([
	'relationship' => 'belongs_to_batch',
	'relationship_guid' => $batch->getGUID(),
	'inverse_relationship' => true,
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'offset' => 0,
	'count' => true
]);

// Get first image related to this batch
$images = elgg_get_entities([
	'relationship' => 'belongs_to_batch',
	'relationship_guid' => $batch->getGUID(),
	'inverse_relationship' => true,
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'offset' => 0,
	'limit' => 1,
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

if ($images) {
	$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics', 'tiny');
	$first_image = $images[0];
	$vars['attachments'] = elgg_format_element('ul', ['class' => 'tidypics-river-list'],
		elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($first_image, $preview_size, [
			'href' => 'ajax/view/photos/riverpopup?guid=' . $first_image->getGUID(),
			'title' => $first_image->title,
			'img_class' => 'tidypics-photo',
			'link_class' => 'tidypics-river-lightbox',
		]))
	);

	$image_link = elgg_view('output/url', [
		'href' => $first_image->getURL(),
		'text' => $first_image->getTitle(),
		'is_trusted' => true,
	]);
}

if ($images_count > 1) {
	// View the comments of the album
	$vars['item']->object_guid = $album->guid;
	$responses = elgg_view('river/elements/responses', $vars);
	if ($responses) {
		$vars['responses'] = elgg_format_element('div', ['class' => 'elgg-river-responses'], $responses);
	}
	$vars['summary'] = elgg_echo('river:object:tidypics_batch:created_single_entry', [$subject_link, $image_link, $images_count-1, $album_link]);
} else {
	// View the comments of the image
	$vars['item']->object_guid = $first_image->guid;
	$responses = elgg_view('river/elements/responses', $vars);
	if ($responses) {
		$vars['responses'] = elgg_format_element('div', ['class' => 'elgg-river-responses'], $responses);
	}
	$vars['summary'] = elgg_echo('river:object:tidypics_batch:created', [$subject_link, $image_link, $album_link]);
}

echo elgg_view('river/elements/layout', $vars);
