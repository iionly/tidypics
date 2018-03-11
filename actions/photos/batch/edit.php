<?php
/**
 * Edit the images in a batch
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guids = (array) get_input('guid');
$titles = (array) get_input('title');
$captions = (array) get_input('caption');
$tags = (array) get_input('tags');

$not_updated = [];
foreach ($guids as $key => $guid) {
	$image = get_entity($guid);

	if ($image->canEdit()) {

		// set title appropriately
		if ($titles[$key]) {
			$title = htmlspecialchars($titles[$key], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
			$image->title = $title;
		} else {
			$title = substr($image->originalfilename, 0, strrpos($image->originalfilename, '.'));
			$title = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
			// remove any possible bad characters from the title
			$image->title = preg_replace('/\W/', '', $title);
		}

		// set description appropriately
		$image->description = $captions[$key];
		$image->tags = string_to_tag_array($tags[$key]);

		if (!$image->save()) {
			array_push($not_updated, $image->getGUID());
		}
	}
}

if (count($not_updated) > 0) {
	return elgg_error_response(elgg_echo('images:notedited'), $image->getContainerEntity()->getURL());
}

return elgg_ok_response('', elgg_echo('images:edited'), $image->getContainerEntity()->getURL());
