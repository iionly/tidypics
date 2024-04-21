<?php
/**
 * Save image action
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

// Get input data
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$description = get_input('description');
$tags = get_input('tags');
$guid = (int) get_input('guid');

elgg_make_sticky_form('tidypics');

if (empty($title)) {
	return elgg_error_response(elgg_echo('image:blank'), REFERRER);
}

$image = get_entity($guid);

$image->title = $title;
$image->description = $description;
if ($tags) {
	if (is_string($tags)) {
		$image->tags = elgg_string_to_array($tags);
	} else {
		$image->tags = $tags;
	}
} else {
	$image->deleteMetadata('tags');
}

if (!$image->save()) {
	return elgg_error_response(elgg_echo('image:error'), REFERRER);
}

elgg_clear_sticky_form('tidypics');

return elgg_ok_response('', elgg_echo('image:album:saved'), $image->getURL());
