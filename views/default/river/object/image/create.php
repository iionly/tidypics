<?php
/**
 * Image album view
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_require_js('tidypics/tidypics');

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', [
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics');
if(!$preview_size) {
	$preview_size = 'tiny';
}
$image = $vars['item']->getObjectEntity();

$attachments = elgg_format_element('ul', ['class' => 'tidypics-river-list'], 
	elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
		'href' => 'photos/riverpopup/' . $image->getGUID(),
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
	'href' => $image->getContainerEntity()->getURL(),
	'text' => $image->getContainerEntity()->getTitle(),
	'is_trusted' => true,
]);

echo elgg_view('river/elements/layout', [
	'item' => $vars['item'],
	'attachments' => $attachments,
	'summary' => elgg_echo('image:river:created', [$subject_link, $image_link, $album_link]),
]);
