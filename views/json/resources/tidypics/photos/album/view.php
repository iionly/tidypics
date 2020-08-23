<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$album_guid = (int) get_input('guid', false);
$album = get_entity($album_guid);

if ($album instanceof TidypicsAlbum) {
	$images = $album->getImages($limit, $offset);
	echo tidypics_slideshow_json_data($images);
} else {
	echo json_encode([]);
}
