<?php

/**
 * Tidypics plugin
 *
 * Selection of album to upload new images to
 *
 * (c) iionly 2013-2015
 * Contact: iionly@gmx.de
 * Website: https://github.com/iionly
 * License: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 *
 */

$album_guid = (int) get_input('album_guid', -1);
$owner_guid = (int) get_input('owner_guid', elgg_get_logged_in_user_guid());

if ($album_guid == -1) {
	return elgg_ok_response('', '', "photos/add/$owner_guid");
}

$album = get_entity($album_guid);
if (!$album->getContainerEntity()->canWriteToContainer(elgg_get_logged_in_user_guid(), 'object', TidypicsAlbum::SUBTYPE)) {
	return elgg_ok_response('', '', REFERER);
}

return elgg_ok_response('', '', "photos/upload/$album_guid");
