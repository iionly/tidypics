<?php
/**
 * View the tags for this image
 *
 * @uses $vars['entity']
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$image = elgg_extract('entity', $vars);
if (!($image instanceof TidypicsImage)) {
	return;
}

$tags = $image->getPhotoTags();
foreach ($tags as $tag) {
	echo elgg_view('photos/tagging/tag', ['tag' => $tag]);
}
