<?php
/**
 * Deletion of a Tidypics image by GUID provided (if image entry does not get properly displayed on site and delete button can not be reached)
 *
 * iionly@gmx.de
 */

$guid = (int) get_input('guid');
$entity = get_entity($guid);

if (!($entity instanceof TidypicsImage)) {
	return elgg_error_response(elgg_echo('tidypics:delete_image:no_image'), REFERRER);
}

if (!$entity->delete()) {
	return elgg_error_response(elgg_echo('tidypics:delete_image:deletefailed'), REFERRER);
}

return elgg_ok_response('', elgg_echo('tidypics:delete_image:success'), REFERRER);
