<?php
/**
 * Add photo tag action
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$coordinates_str = get_input('coordinates');
$username = htmlspecialchars(get_input('username', '', false), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$image_guid = (int) get_input('guid');

if ($image_guid == 0) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:error'), REFERER);
}

$image = get_entity($image_guid);
if (!($image instanceof TidypicsImage)) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:error'), REFERER);
}

if (empty($username)) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:error'), REFERER);
}

$album = get_entity($image->getContainerGUID());
if (!($album instanceof TidypicsAlbum)) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:error'), REFERER);
}

$user = get_user_by_username($username);
if ($user instanceof ElggUser) {
	$relationships_type = 'user';
	$value = $user->guid;
} else {
	// plain tag
	$relationships_type = 'word';
	$value = $username;
}

$tag = new stdClass();
$tag->coords = $coordinates_str;
$tag->type = $relationships_type;
$access_id = $image->getAccessID();

$existing_tags = false;
if ($tag->type === 'word') {
	$new_tags = string_to_tag_array($value);
	// check to see if the photo has this tag and add if not
	if(!isset($image->tags)) {
		$image->tags = $new_tags;
	} else if (!is_array($image->tags)) {
		if (in_array($image->tags, $new_tags)) {
			$existing_tags = true;
			$value = '';
			$tagarray = string_to_tag_array($image->tags);
			foreach ($new_tags as $new_tag) {
				if (!in_array($newtag, $tagarray)) {
					$tagarray[] = $newtag;
					$value .= ', ' . $newtag;
				}
			}
			if (strlen($value) > 0) {
				$value = substr($value, 2);
			}
			$image->deleteMetadata('tags');
			$image->tags = $tagarray;
		} else {
			$tagarray = string_to_tag_array($image->tags);
			$image->deleteMetadata('tags');
			$image->tags = array_merge($tagarray, $new_tags);
		}
	} else {
		$tagarray = $image->tags;
		$value = '';
		foreach ($new_tags as $newtag) {
			if (!in_array($newtag, $tagarray)) {
				$tagarray[] = $newtag;
				$value .= ', ' . $newtag;
			} else {
				$existing_tags = true;
			}
		}
		if (strlen($value) > 0) {
			$value = substr($value, 2);
		}
		$image->deleteMetadata('tags');
		$image->tags = $tagarray;
	}
}

if (strlen($value) < 1) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:nosuccess'), REFERER);
}

$tag->value = $value;

$annotation_id = $image->annotate('phototag', serialize($tag), $access_id);
$river_tags = elgg_get_plugin_setting('river_tags', 'tidypics');
if ($annotation_id) {
	// if tag is a user id, add relationship for searching (find all images with user x)
	if ($tag->type === 'user') {
		if (!check_entity_relationship($tag->value, 'phototag', $image_guid)) {
			add_entity_relationship($tag->value, 'phototag', $image_guid);

			$tagger = elgg_get_logged_in_user_entity();

			if ($river_tags == "show") {
				// also add this to the river - subject is tagger, object is the tagged user
				elgg_create_river_item([
					'view' => 'river/object/image/tag',
					'action_type' => 'tag',
					'subject_guid' => $tagger->guid,
					'object_guid' => $user->guid,
					'target_guid' => $album->getGUID(),
					'access_id' => $access_id,
					'annotation_id' => $annotation_id,
				]);
			}

			// notify user of tagging as long as not self
			if ($tagger->guid != $user->guid) {
				notify_user($user->guid,
					$tagger->guid,
					elgg_echo('tidypics:tag:subject'),
					elgg_echo('tidypics:tag:body', [$image->getTitle(), $tagger->name, $image->getURL()])
				);
			}
		}
	} else if ($tag->type === 'word') {
		if ($river_tags == "show") {
			// also add this to the river - subject is tagger, object is the tagged image
			$tagger = elgg_get_logged_in_user_entity();
			elgg_create_river_item([
				'view' => 'river/object/image/wordtag',
				'action_type' => 'wordtag',
				'subject_guid' => $tagger->guid,
				'object_guid' => $image->guid,
				'target_guid' => $album->getGUID(),
				'access_id' => $access_id,
				'annotation_id' => $annotation_id,
			]);
		}
	}
}

if ($existing_tags) {
	return elgg_ok_response('', elgg_echo('tidypics:phototagging:success_partly'), REFERER);
}

return elgg_ok_response('', elgg_echo('tidypics:phototagging:success'), REFERER);
