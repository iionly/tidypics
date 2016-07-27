<?php
/**
 * Thumbnail sizes
 */

$plugin = $vars['plugin'];

$image_sizes = unserialize($plugin->image_sizes);
$image_sizes['tiny_image_width'] = isset($image_sizes['tiny_image_width']) ? $image_sizes['tiny_image_width']: 60;
$image_sizes['tiny_image_height'] = isset($image_sizes['tiny_image_height']) ? $image_sizes['tiny_image_height']: 60;
$image_sizes['tiny_image_square'] = isset($image_sizes['tiny_image_square']) ? $image_sizes['tiny_image_square']: true;
$image_sizes['small_image_width'] = isset($image_sizes['small_image_width']) ? $image_sizes['small_image_width']: 153;
$image_sizes['small_image_height'] = isset($image_sizes['small_image_height']) ? $image_sizes['small_image_height']: 153;
$image_sizes['small_image_square'] = isset($image_sizes['small_image_square']) ? $image_sizes['small_image_square']: true;
$image_sizes['large_image_width'] = isset($image_sizes['large_image_width']) ? $image_sizes['large_image_width']: 600;
$image_sizes['large_image_height'] = isset($image_sizes['large_image_height']) ? $image_sizes['large_image_height']: 600;
$image_sizes['large_image_square'] = isset($image_sizes['large_image_square']) ? $image_sizes['large_image_square']: false;

echo '<span class="elgg-text-help mbm">' . elgg_echo('tidypics:settings:sizes:instructs') . '</span>';
echo '<table>';
$sizes = array('large', 'small', 'tiny');
foreach ($sizes as $size) {
	echo '<tr>';
	echo '<td class="pas">';
	echo "<label>" . elgg_echo("tidypics:settings:{$size}size") . "</label><br>";
	echo elgg_view('output/longtext', array('value' => elgg_echo("tidypics:settings:imagesize_defaultsize_{$size}"), 'class' => 'elgg-subtext'));
	echo '</td><td class="pas">';
	echo elgg_echo("tidypics:settings:imagesize_width");
	echo elgg_view('input/text', array(
		'name' => "{$size}_image_width",
		'value' => $image_sizes["{$size}_image_width"],
		'class' => 'tidypics-input-thin',
	));
	echo '</td><td class="pas">';
	echo elgg_echo("tidypics:settings:imagesize_height");
	echo elgg_view('input/text', array(
		'name' => "{$size}_image_height",
		'value' => $image_sizes["{$size}_image_height"],
		'class' => 'tidypics-input-thin',
	));
	echo '</td><td class="pas">';
	echo elgg_view('input/checkbox', array(
		'name' => "{$size}_image_square",
		'value' => true,
		'checked' => (bool)$image_sizes["{$size}_image_square"],
	));
	echo " " . elgg_echo("tidypics:settings:imagesize_square");
	echo '</td>';
	echo '</tr>';
}
echo '</table>';
