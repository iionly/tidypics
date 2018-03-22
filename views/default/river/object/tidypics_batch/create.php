<?php
/**
 * Batch river view
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 *
 */

elgg_require_js('tidypics/tidypics');

$batch = $vars['item']->getObjectEntity();

// Get images related to this batch
$images = elgg_get_entities_from_relationship([
	'relationship' => 'belongs_to_batch',
	'relationship_guid' => $batch->getGUID(),
	'inverse_relationship' => true,
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'offset' => 0,
]);

$album = $batch->getContainerEntity();
if (!$album) {
	// something went quite wrong - this batch has no associated album
	return true;
}
$album_link = elgg_view('output/url', [
	'href' => $album->getURL(),
	'text' => $album->getTitle(),
	'is_trusted' => true,
]);

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', [
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

$attachments = '';
if (count($images)) {
	$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics');
	if(!$preview_size) {
		$preview_size = 'tiny';
	}
	$attachments = '';
	foreach($images as $image) {
		$attachments .= elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
			'href' => 'photos/riverpopup/' . $image->getGUID(),
			'title' => $image->title,
			'img_class' => 'tidypics-photo',
			'link_class' => 'tidypics-river-lightbox',
		]));
	}
	$attachments = elgg_format_element('ul', ['class' => 'tidypics-river-list'], $attachments);
}

if (count($images) == 1) {
	// View the comments of the image
	$vars['item']->object_guid = $images[0]->guid;
	$responses = elgg_view('river/elements/responses', $vars);
	if ($responses) {
		$responses = elgg_format_element('div', ['class' => 'elgg-river-responses'], $responses);
	}
	$image_link = elgg_view('output/url', [
		'href' => $images[0]->getURL(),
		'text' => $images[0]->getTitle(),
		'is_trusted' => true,
	]);
	$summary = elgg_echo('image:river:created', [$subject_link, $image_link, $album_link]);
} else {
	// View the comments of the album
	$vars['item']->object_guid = $album->guid;
	$responses = elgg_view('river/elements/responses', $vars);
	if ($responses) {
		$responses = elgg_format_element('div', ['class' => 'elgg-river-responses'], $responses);
	}
	$summary = elgg_echo('image:river:created:multiple', [$subject_link, count($images), $album_link]);
}

echo elgg_view('river/elements/layout', [
	'item' => $vars['item'],
	'attachments' => $attachments,
	'summary' => $summary
]);
