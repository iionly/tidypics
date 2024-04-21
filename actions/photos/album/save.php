<?php
/**
 * Save album action
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */


// Get input data
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$description = get_input('description');
$tags = get_input('tags');
$access_id = (int) get_input('access_id');
$container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());
$guid = (int) get_input('guid');

elgg_make_sticky_form('tidypics');

if (empty($title)) {
	return elgg_error_response(elgg_echo('album:blank'), REFERRER);
}

if ($guid) {
	$album = get_entity($guid);
	if ($album->access_id != $access_id) {
		$images = elgg_get_entities([
			'type' => 'object',
			'subtype' => TidypicsImage::SUBTYPE,
			'container_guid' => $album->guid,
			'limit' => false,
			'batch' => true,
		]);
		foreach ($images as $image) {
			$image->access_id = $access_id;
			$image->save();
		}
		$batches = elgg_get_entities([
			'type' => 'object',
			'subtype' => TidypicsBatch::SUBTYPE,
			'container_guid' => $album->guid,
			'limit' => false,
			'batch' => true,
		]);
		foreach ($batches as $batch) {
			$batch->access_id = $access_id;
			$batch->save();
		}
	}
} else {
	$album = new TidypicsAlbum();
}

$album->container_guid = $container_guid;
$album->owner_guid = elgg_get_logged_in_user_guid();
$album->access_id = $access_id;
$album->title = $title;
$album->description = $description;
if ($tags) {
	if (is_string($tags)) {
		$album->tags = elgg_string_to_array($tags);
	} else {
		$album->tags = $tags;
	}
} else {
	$album->deleteMetadata('tags');
}

if (!$album->save()) {
	return elgg_error_response(elgg_echo('album:error'), REFERRER);
}

elgg_clear_sticky_form('tidypics');

return elgg_ok_response('', elgg_echo('album:created'), $album->getURL());
