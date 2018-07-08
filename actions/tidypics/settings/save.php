<?php
/**
 * Save Tidypics plugin settings
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$plugin = elgg_get_plugin_from_id('tidypics');

$params = (array) get_input('params');
foreach ($params as $k => $v) {
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		register_error(elgg_echo('plugins:settings:save:fail', ['tidypics']));
		forward(REFERER);
		exit;
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
	register_error(elgg_echo('plugins:settings:save:fail', ['tidypics']));
	forward(REFERER);
}

system_message(elgg_echo('plugins:settings:save:ok', ['tidypics']));
forward(REFERER);
