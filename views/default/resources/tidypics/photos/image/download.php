<?php
/**
 * Download a photo
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guid = (int) elgg_extract('guid', $vars);
$image = get_entity($guid);

$disposition = elgg_extract('disposition', $vars);
$disposition = ($disposition == 'inline') ? 'inline' : 'attachment';

if ($image instanceof TidypicsImage) {
	$filename = $image->originalfilename;
	$mime = $image->mimetype;

	header("Content-Type: $mime");
	header("Content-Disposition: $disposition; filename=\"$filename\"");

	$contents = $image->grabFile();

	if (empty($contents)) {
		elgg_redirect_response(elgg_get_simplecache_url("tidypics/image_error_large.png"));
	} else {
		// expires every 60 days
		$expires = 60*60*60*24;

		header("Content-Length: " . strlen($contents));
		header("Cache-Control: public", true);
		header("Pragma: public", true);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT', true);

		echo $contents;
	}

	exit;
} else {
	elgg_error_response(elgg_echo("image:downloadfailed"));
}
