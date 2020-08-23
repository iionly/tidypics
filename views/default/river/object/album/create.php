<?php
/**
 * Album river view
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_require_js('tidypics/tidypics');

$item = elgg_extract('item', $vars);
if (!($item instanceof ElggRiverItem)) {
	return;
}

$album = $item->getObjectEntity();
if (!($album instanceof TidypicsAlbum)) {
	return;
}

$album_river_view = elgg_get_plugin_setting('album_river_view', 'tidypics');
$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics', 'tiny');

if ($album_river_view == "set") {
	$river_album_number = (int) elgg_get_plugin_setting('river_album_number', 'tidypics', 7);
	$images = $album->getImages($river_album_number);
	if (count($images)) {
		$attachments = '';
		foreach($images as $image) {
			$attachments .= elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
				'href' => 'ajax/view/photos/riverpopup?guid=' . $image->getGUID(),
				'title' => $image->title,
				'img_class' => 'tidypics-photo',
				'link_class' => 'tidypics-river-lightbox',
			]));
		}
		$vars['attachments'] = elgg_format_element('ul', ['class' => 'tidypics-river-list'], $attachments);
	}
} else {
	$image = $album->getCoverImage();
	if ($image) {
		$vars['attachments'] = elgg_format_element('ul', ['class' => 'tidypics-river-list'], 
			elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
				'href' => 'ajax/view/photos/riverpopup?guid=' . $image->getGUID(),
				'title' => $image->title,
				'img_class' => 'tidypics-photo',
				'link_class' => 'tidypics-river-lightbox',
			]))
		);
		
		
	}
}

echo elgg_view('river/elements/layout', $vars);
