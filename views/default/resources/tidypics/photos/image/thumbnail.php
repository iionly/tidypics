<?php
/**
 * Image thumbnail view
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guid = (int) elgg_extract('guid', $vars);
$size = elgg_extract('size', $vars, 'small');
$image = get_entity($guid);
if (!($image instanceof TidypicsImage)) {
	return;
}

$watermark_text = elgg_get_plugin_setting('watermark_text', 'tidypics', '');
if ($watermark_text && $size == 'master') {
	$size = 'large';
}

if ($size == 'master') {
	$contents = $image->getImage();
} else {
	$contents = $image->getThumbnail($size);
}
if (!$contents) {
	echo elgg_view("tidypics/image_error_$size.png");
}

// expires every 14 days
$expires = 14*60*60*24;

// overwrite header caused by php session code so images can be cached
$mime = $image->getMimeType();
header("Content-Type: $mime");
header("Content-Length: " . strlen($contents));
header("Cache-Control: public", true);
header("Pragma: public", true);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT', true);

// Return the thumbnail and exit
echo $contents;
exit;
