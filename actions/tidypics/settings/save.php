<?php
/**
 * Save Tidypics plugin settings
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$params = (array) get_input('params');
$plugin = elgg_get_plugin_from_id('tidypics');
if (!$plugin) {
	return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_id]));
}
$plugin_name = $plugin->getDisplayName();

$result = false;

foreach ($params as $k => $v) {
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_name]));
	}
}

// image sizes
$image_sizes = [];
$image_sizes['large_image_width'] = (int) get_input('large_image_width');
$image_sizes['large_image_height'] = (int) get_input('large_image_height');
$image_sizes['large_image_square'] = (bool) get_input('large_image_square');
$image_sizes['small_image_width'] = (int) get_input('small_image_width');
$image_sizes['small_image_height'] = (int) get_input('small_image_height');
$image_sizes['small_image_square'] = (bool) get_input('small_image_square');
$image_sizes['tiny_image_width'] = (int) get_input('tiny_image_width');
$image_sizes['tiny_image_height'] = (int) get_input('tiny_image_height');
$image_sizes['tiny_image_square'] = (bool) get_input('tiny_image_square');

$result = $plugin->setSetting('image_sizes', serialize($image_sizes));
if (!$result) {
	return elgg_error_response(elgg_echo('plugins:settings:save:fail', [$plugin_name]));
}

return elgg_ok_response('', elgg_echo('plugins:settings:save:ok', [$plugin_name]));
