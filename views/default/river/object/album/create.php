<?php
/**
 * Album river view
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_require_js('tidypics/tidypics');

$album = $vars['item']->getObjectEntity();

$album_river_view = elgg_get_plugin_setting('album_river_view', 'tidypics');
$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics');
if(!$preview_size) {
	$preview_size = 'tiny';
}

if ($album_river_view == "set") {
	$river_album_number = (int) elgg_get_plugin_setting('river_album_number', 'tidypics', 7);
	$images = $album->getImages($river_album_number);
	if (count($images)) {
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
} else {
	$image = $album->getCoverImage();
	if ($image) {
		$attachments = elgg_format_element('ul', ['class' => 'tidypics-river-list'], 
			elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
				'href' => 'photos/riverpopup/' . $image->getGUID(),
				'title' => $image->title,
				'img_class' => 'tidypics-photo',
				'link_class' => 'tidypics-river-lightbox',
			]))
		);
		
		
	}
}

echo elgg_view('river/elements/layout', [
	'item' => $vars['item'],
	'attachments' => $attachments,
]);
