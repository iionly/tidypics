<?php
/**
 * EXIF sidebar module
 */

$image = elgg_extract('image', $vars);
if (!($image instanceof TidypicsImage)) {
	return;
}
 
$exif = tp_exif_formatted($image);
if ($exif) {
	$row = '';
	foreach ($exif as $key => $value) {
		$cell = elgg_format_element('td', [], elgg_view("output/text", ["value" => filter_tags($key)]));
		$cell .= elgg_format_element('td', [], elgg_view("output/text", ["value" => filter_tags($value)]));
		$row .= elgg_format_element('tr', [], $cell);
	}
	$body = elgg_format_element('table', ['class' => 'elgg-table elgg-table-alt'], $row);

	echo elgg_view_module("aside", elgg_echo('tidypics:exif_title'), $body);
}
