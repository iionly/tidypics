<?php
/**
 * Set album cover image
 */

// Get input data
$album_guid = (int) get_input('album_guid');
$image_guid = (int) get_input('image_guid');

$album = get_entity($album_guid);

if (!($album instanceof TidypicsAlbum)) {
	return elgg_error_response(elgg_echo('album:invalid_album'), REFERRER);
}

if (!$album->setCoverImageGuid($image_guid)) {
	return elgg_error_response(elgg_echo('album:cannot_save_cover_image'), REFERRER);
}

return elgg_ok_response('', elgg_echo('album:save_cover_image'), REFERRER);
