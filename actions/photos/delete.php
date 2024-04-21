<?php
/**
 * Delete album or image
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guid = (int) get_input('guid');
$entity = get_entity($guid);
if (!$entity) {
	// unable to get Elgg entity
	return elgg_error_response(elgg_echo('tidypics:deletefailed'), REFERRER);
}

if (!$entity->canEdit()) {
	// user doesn't have permissions
	return elgg_error_response(elgg_echo('tidypics:deletefailed'), REFERRER);
}

$container = $entity->getContainerEntity();

$subtype = $entity->getSubtype();
switch ($subtype) {
	case TidypicsAlbum::SUBTYPE:
		if ($container instanceof ElggUser) {
			$forward_url = "photos/owner/$container->username";
		} else {
			$forward_url = "photos/group/$container->guid";
		}
		break;
	case TidypicsImage::SUBTYPE:
		$forward_url = $container->getURL();
		break;
	default:
		return elgg_ok_response('', '', REFERRER);
		break;
}

if (!$entity->delete()) {
	return elgg_error_response(elgg_echo('tidypics:deletefailed'), $forward_url);
}

return elgg_ok_response('', elgg_echo('tidypics:deleted'), $forward_url);
