<?php
/**
 * Multi-image uploader action
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$img_river_view = elgg_get_plugin_setting('img_river_view', 'tidypics');

set_input('tidypics_action_name', 'tidypics_photo_upload');

$guid = (int) get_input('guid');
$album = get_entity($guid);
if (!($album instanceof TidypicsAlbum)) {
	return elgg_error_response(elgg_echo('tidypics:baduploadform'), REFERER);
}

// post limit exceeded
if (count($_FILES) == 0) {
	trigger_error('Tidypics warning: user exceeded post limit on image upload', E_USER_WARNING);
	return elgg_error_response(elgg_echo('tidypics:exceedpostlimit'), REFERER);
}

// test to make sure at least 1 image was selected by user
$num_images = 0;
foreach ($_FILES['images']['name'] as $name) {
	if (!empty($name)) {
		$num_images++;
	}
}
if ($num_images == 0) {
	// have user try again
	return elgg_error_response(elgg_echo('tidypics:noimages'), REFERER);
}

// create the image object for each upload
$uploaded_images = [];
$not_uploaded = [];
$error_msgs = [];
foreach ($_FILES['images']['name'] as $index => $value) {
	$data = [];
	foreach ($_FILES['images'] as $key => $values) {
		$data[$key] = $values[$index];
	}

	if (empty($data['name'])) {
		continue;
	}

	$name = htmlspecialchars($data['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

	$mime = TidypicsUpload::tp_upload_get_mimetype($name);

	$image = new TidypicsImage();
	$image->title = $name;
	$image->container_guid = $album->getGUID();
	$image->setMimeType($mime);
	$image->access_id = $album->access_id;

	try {
		$result = $image->save($data);
	} catch (Exception $e) {
		$image->delete();
		$result = false;
		array_push($not_uploaded, $name);
		array_push($error_msgs, $e->getMessage());
	}

	if ($result) {
		array_push($uploaded_images, $image->getGUID());

		if ($img_river_view == "all") {
			elgg_create_river_item([
				'view' => 'river/object/image/create',
				'action_type' => 'create',
				'subject_guid' => $image->getOwnerGUID(),
				'object_guid' => $image->getGUID(),
				'target_guid' => $album->getGUID(),
			]);
		}
	}
}

if (count($uploaded_images)) {
	// Create a new batch object to contain these photos
	$batch = new TidypicsBatch();
	$batch->access_id = $album->access_id;
	$batch->container_guid = $album->getGUID();
	if ($batch->save()) {
		foreach ($uploaded_images as $uploaded_guid) {
			$uploaded_image = get_entity($uploaded_guid);
			$uploaded_image->addRelationship($batch->getGUID(), 'belongs_to_batch');
		}
	}

	$album->prependImageList($uploaded_images);

	// "added images to album" river
	if ($img_river_view == "batch" && !($album->new_album)) {
		elgg_create_river_item([
			'view' => 'river/object/tidypics_batch/create',
			'action_type' => 'create',
			'subject_guid' => $batch->getOwnerGUID(),
			'object_guid' => $batch->getGUID(),
			'target_guid' => $album->getGUID(),
		]);
	} else if ($img_river_view == "1" && !($album->new_album)) {
		elgg_create_river_item([
			'view' => 'river/object/tidypics_batch/create_single_image',
			'action_type' => 'create',
			'subject_guid' => $batch->getOwnerGUID(),
			'object_guid' => $batch->getGUID(),
			'target_guid' => $album->getGUID(),
		]);
	}

	// "created album" river
	if ($album->new_album) {
		$album->new_album = 0;
		$album->first_upload = 1;

		$album_river_view = elgg_get_plugin_setting('album_river_view', 'tidypics');
		if ($album_river_view != "none") {
			elgg_create_river_item([
				'view' => 'river/object/album/create',
				'action_type' => 'create',
				'subject_guid' => $album->getOwnerGUID(),
				'object_guid' => $album->getGUID(),
				'target_guid' => $album->getGUID(),
			]);
		}

		// "created album" notifications
		// we throw the notification manually here so users are not told about the new album until
		// there are at least a few photos in it
		if ($album->shouldNotify()) {
			elgg_trigger_event('album_first', TidypicsAlbum::SUBTYPE, $album);
			$album->last_notified = time();
		}
	} else {
		// "added image to album" notifications
		if ($album->first_upload) {
			$album->first_upload = 0;
		}

		if ($album->shouldNotify()) {
			elgg_trigger_event('album_more', TidypicsAlbum::SUBTYPE, $album);
			$album->last_notified = time();
		}
	}
}

if (count($not_uploaded) > 0) {
	if (count($uploaded_images) > 0) {
		$error = sprintf(elgg_echo("tidypics:partialuploadfailure"), count($not_uploaded), count($not_uploaded) + count($uploaded_images))  . '<br>';
	} else {
		$error = elgg_echo("tidypics:completeuploadfailure") . '<br>';
	}

	$num_failures = count($not_uploaded);
	for ($i = 0; $i < $num_failures; $i++) {
		$error .= "{$not_uploaded[$i]}: {$error_msgs[$i]} <br>";
	}

	if (count($uploaded_images) == 0) {
		//upload failed, so forward to previous page
		return elgg_error_response($error, REFERER);
	} else {
		// some images did upload so we fall through
		return elgg_error_response($error, "photos/edit/$batch->guid");
	}
}

return elgg_ok_response('', elgg_echo('tidypics:upl_success'), "photos/edit/$batch->guid");
