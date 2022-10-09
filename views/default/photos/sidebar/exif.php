<?php
/**
 * EXIF sidebar module
 */

$image = elgg_extract('image', $vars);
if (!($image instanceof TidypicsImage)) {
	return;
}
 
$exif = TidypicsExif::tp_exif_formatted($image);
if ($exif) {
	$row = '';
	foreach ($exif as $key => $value) {
		$cell_content = elgg_view("output/text", ["value" => elgg_sanitize_input($key)]) . "<br>";
		$cell_content .= elgg_view("output/text", ["value" => elgg_sanitize_input($value)]);
		$cell = elgg_format_element('td', [], $cell_content);
		$row .= elgg_format_element('tr', [], $cell);
	}
	$body = elgg_format_element('table', ['class' => 'elgg-table elgg-table-alt'], $row);

	echo elgg_view_module("aside", elgg_echo('tidypics:exif_title'), $body);
}
