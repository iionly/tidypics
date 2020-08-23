<?php
/**
 * Tidypics server analysis
 */

function tp_readable_size($bytes) {
	if (strpos($bytes, 'M')) {
		return $bytes . 'B';
	}

	$size = $bytes / 1024;
	if ($size < 1024) {
		$size = number_format($size, 2);
		$size .= ' KB';
	} else {
		$size = $size / 1024;
		if ($size < 1024) {
			$size = number_format($size, 2);
			$size .= ' MB';
		} else {
			$size = $size / 1024;
			$size = number_format($size, 2);
			$size .= ' GB';
		}
	}
	return $size;
}

$disablefunc = explode(',', ini_get('disable_functions'));
$exec_avail = elgg_echo('tidypics:disabled');
if (is_callable('exec') && !in_array('exec', $disablefunc)) {
	$exec_avail = elgg_echo('tidypics:enabled');
}

$rows = [];
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:php_version')),
	elgg_format_element('td', [], phpversion()),
	elgg_format_element('td', [], ''),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], 'GD'),
	elgg_format_element('td', [], extension_loaded('gd') ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled')),
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:gd_desc')),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], 'imagick'),
	elgg_format_element('td', [], extension_loaded('imagick') ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled')),
	elgg_format_element('td', [], ''),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], 'exec()'),
	elgg_format_element('td', [], $exec_avail),
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:exec_desc')),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:memory_limit')),
	elgg_format_element('td', [], tp_readable_size(ini_get('memory_limit'))),
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:memory_limit_desc')),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:peak_usage')),
	elgg_format_element('td', [], function_exists('memory_get_peak_usage') ? tp_readable_size(memory_get_peak_usage()) : ''),
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:peak_usage_desc')),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:upload_max_filesize')),
	elgg_format_element('td', [], tp_readable_size(ini_get('upload_max_filesize'))),
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:upload_max_filesize_desc')),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:post_max_size')),
	elgg_format_element('td', [], tp_readable_size(ini_get('post_max_size'))),
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:post_max_size_desc')),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:max_input_time')),
	elgg_format_element('td', [], ini_get('max_input_time') . 's'),
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:max_input_time_desc')),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:max_execution_time')),
	elgg_format_element('td', [], ini_get('max_execution_time') . 's'),
	elgg_format_element('td', [], elgg_echo('tidypics:server_info:max_execution_time_desc')),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], 'GD imagejpeg'),
	elgg_format_element('td', [], is_callable('imagejpeg') ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled')),
	elgg_format_element('td', [], ''),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], 'GD imagepng'),
	elgg_format_element('td', [], is_callable('imagepng') ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled')),
	elgg_format_element('td', [], ''),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], 'GD imagegif'),
	elgg_format_element('td', [], is_callable('imagegif') ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled')),
	elgg_format_element('td', [], ''),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], 'GD imagewebp'),
	elgg_format_element('td', [], is_callable('imagewebp') ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled')),
	elgg_format_element('td', [], ''),
]));
$rows[] = elgg_format_element('tr', [], implode('', [
	elgg_format_element('td', [], 'EXIF'),
	elgg_format_element('td', [], is_callable('exif_read_data') ? elgg_echo('tidypics:enabled') : elgg_echo('tidypics:disabled')),
	elgg_format_element('td', [], ''),
]));

$table_content = elgg_format_element('tbody', [], implode('', $rows));
	
$content = elgg_format_element('table', ['class' => 'elgg-table'], $table_content);

echo elgg_view_module('inline', elgg_echo('tidypics:server_info'), $content);
