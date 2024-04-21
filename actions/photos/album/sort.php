<?php
/**
 * Sorting album action - takes a comma separated list of image guids
 */

$album_guid = (int) get_input('album_guid');
$album = get_entity($album_guid);
if (!($album instanceof TidypicsAlbum)) {
	return elgg_error_response(elgg_echo('album:invalid_album'), REFERRER);
}

$guids = get_input('guids');
$guids = explode(',', $guids);

if (!$album->setImageList($guids)) {
	return elgg_error_response(elgg_echo('tidypics:album:could_not_sort', [$album->getTitle()]), $album->getURL());
}

return elgg_ok_response('', elgg_echo('tidypics:album:sorted', [$album->getTitle()]), $album->getURL());
