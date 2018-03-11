<?php
/**
 * Tidypics ImageMagick Location Test
 *
 */

$im_location = get_input('im_location');

$command = $im_location . "/" . "convert -version";

$result_array = [];
$result = exec($command, $result_array, $return_val);

if ($return_val == 0) {
	$output = json_encode([
		'result' => implode(' ', $result_array),
	]);
} else {
	$output = '';
}

return elgg_ok_response($output, '');
