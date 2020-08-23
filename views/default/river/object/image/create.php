<?php
/**
 * Image album view
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_require_js('tidypics/tidypics');

$item = elgg_extract('item', $vars);
if (!($item instanceof ElggRiverItem)) {
	return;
}

$subject = $item->getSubjectEntity();
if (!($subject instanceof ElggUser)) {
	return;
}

$image = $item->getObjectEntity();
if (!($image instanceof TidypicsImage)) {
	return;
}

$album = $image->getContainerEntity();
if (!($album instanceof TidypicsAlbum)) {
	return;
}

$subject_link = elgg_view('output/url', [
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics', 'tiny');

$vars['attachments'] = elgg_format_element('ul', ['class' => 'tidypics-river-list'],
	elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
		'href' => 'ajax/view/photos/riverpopup?guid=' . $image->getGUID(),
		'title' => $image->title,
		'img_class' => 'tidypics-photo',
		'link_class' => 'tidypics-river-lightbox',
	]))
);

$image_link = elgg_view('output/url', [
	'href' => $image->getURL(),
	'text' => $image->getTitle(),
	'is_trusted' => true,
]);

$album_link = elgg_view('output/url', [
	'href' => $album->getURL(),
	'text' => $album->getTitle(),
	'is_trusted' => true,
]);

$vars['summary'] = elgg_echo('river:object:image:created', [$subject_link, $image_link, $album_link]);

echo elgg_view('river/elements/layout', $vars);
