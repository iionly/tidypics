<?php
/**
 * Remove photo tag action
 */

$annotation = elgg_get_annotation_from_id(get_input('annotation_id'));

if (!$annotation instanceof ElggAnnotation || $annotation->name != 'phototag') {
	return elgg_error_response(elgg_echo('tidypics:phototagging:delete:error'), REFERER);
}

if (!$annotation->canEdit()) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:delete:error'), REFERER);
}

$entity_guid = $annotation->entity_guid;

$image = get_entity($entity_guid);
if (!($image instanceof TidypicsImage) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:error'), REFERER);
}

$value = $annotation->value;

if (!$annotation->delete()) {
	return elgg_error_response(elgg_echo('tidypics:phototagging:delete:error'), REFERER);
}

// KJ - now remove any user tag relationship
$tag = unserialize($value);
if ($tag->type == 'user') {
	remove_entity_relationship($tag->value, 'phototag', $entity_guid);
} else if ($tag->type == 'word') {
	$obsolete_tags = string_to_tag_array($tag->value);

	// delete normal tags if they exists
	if (is_array($image->tags)) {
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

return elgg_ok_response('', elgg_echo('tidypics:phototagging:delete:success'), REFERER);
