<?php
/**
 * Remove photo tag action
 */

$annotation = elgg_get_annotation_from_id(get_input('annotation_id'));

if (!$annotation instanceof ElggAnnotation || $annotation->name != 'phototag') {
	return elgg_error_response(elgg_echo('tidypics:phototagging:delete:error'), REFERRER);
}

if (!$annotation->canEdit()) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:delete:error'), REFERRER);
}

$entity_guid = $annotation->entity_guid;

$image = get_entity($entity_guid);
if (!($image instanceof TidypicsImage) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:error'), REFERRER);
}

$value = $annotation->value;

if (!$annotation->delete()) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:delete:error'), REFERRER);
}

// KJ - now remove any user tag relationship
$tag = unserialize($value);
if ($tag->type == 'user') {
	if ($tagging_user = get_user($tag->value)) {
		$tagging_user->removeRelationship($entity_guid, 'phototag');
	}
} else if ($tag->type == 'word') {
	if (is_string($tag->value)) {
		$obsolete_tags = elgg_string_to_array($tag->value);
	} else {
		$obsolete_tags = $tag->value;
	}
	// delete normal tags if they exists
	if (isset($image->tags) && is_array($image->tags)) {
		$tagarray = [];
		$removed_tags = [];
		foreach ($image->tags as $image_tag) {
			if ((!in_array($image_tag, $obsolete_tags)) || (in_array($image_tag, $removed_tags))) {
				$tagarray[] = $image_tag;
			} else {
				$removed_tags[] = $image_tag;
			}
		}
		$image->deleteMetadata('tags');
		if (sizeof($tagarray) > 0) {
			$image->tags = $tagarray;
		}
	} else {
		if ($tag->value === $image->tags) {
			$image->deleteMetadata('tags');
		}
	}
}

return elgg_ok_response('', elgg_echo('tidypics:phototagging:delete:success'), REFERRER);
