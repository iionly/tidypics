<?php
/**
 * Tidypics settings form
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

if (tidypics_is_upgrade_available()) {
	echo elgg_format_element('div', ['class' => 'elgg-admin-notices'], elgg_autop(elgg_view('output/url', [
		'text' => elgg_echo('tidypics:upgrade'),
		'href' => 'action/photos/admin/upgrade',
		'is_action' => true,
	])));
}

// show navigation tabs
echo elgg_view('photos/tabs', ['tab' => 'settings']);

// Main settings for Tidypics
$content_main = '';

// enable/disable some options
$checkboxes = ['tagging', 'restrict_tagging', 'view_count', 'uploader', 'exif', 'download_link' , 'slideshow', 'extended_sidebar_menu'];
foreach ($checkboxes as $checkbox) {
	$content_main .= elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo("tidypics:settings:$checkbox"),
		'name' => "params[$checkbox]",
		'value' => true,
		'checked' => (bool) $plugin->$checkbox,
	]);
}

// site menu entry links to photos or albums
$content_main .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('tidypics:settings:site_menu_link'),
	'name' => 'params[site_menu_link]',
	'options_values' => [
		'photos' => elgg_echo('tidypics:settings:site_menu_photos'),
		'albums' => elgg_echo('tidypics:settings:site_menu_albums'),
	],
	'value' => $plugin->site_menu_link,
]);

// max image size in MB
$maxfilesize = (int) $plugin->maxfilesize;
if (!$maxfilesize) {
	$maxfilesize = 5;
} else if ($maxfilesize < 1) {
	$maxfilesize = 1;
} else if ($maxfilesize > 50) {
	$maxfilesize = 50;
}
$content_main .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('tidypics:settings:maxfilesize'),
	'name' => 'params[maxfilesize]',
	'value' => $maxfilesize,
	'min' => 1,
	'max' => 50,
	'step' => 1,
]);

// watermark text
$content_main .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('tidypics:settings:watermark'),
	'name' => 'params[watermark_text]',
	'value' => $plugin->watermark_text,
]);

// quota size
$quota = (int) $plugin->quota;
if (!$quota) {
	$quota = 0;
} else if ($quota < 0) {
	$quota = 0;
}
$content_main .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('tidypics:settings:quota'),
	'#help' => elgg_echo('tidypics:settings:quota_help'),
	'name' => 'params[quota]',
	'value' => $quota,
	'min' => 0,
	'step' => 1,
]);

// max number of image allowed in one upload
$max_uploads = (int) $plugin->max_uploads;
if (!$max_uploads) {
	$max_uploads = 10;
} else if ($max_uploads < 1) {
	$max_uploads = 1;
} else if ($max_uploads > 50) {
	$max_uploads = 50;
}
$content_main .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('tidypics:settings:max_uploads'),
	'#help' => elgg_echo('tidypics:settings:max_uploads_explanation'),
	'name' => 'params[max_uploads]',
	'value' => $max_uploads,
	'min' => 1,
	'max' => 50,
	'step' => 1,
]);

echo elgg_view_module('inline', elgg_echo('tidypics:settings:main'), $content_main);


// Image library settings for Tidypics
$content_img_lib = '';

// which image library to be used from the options available on the server?
$content_img_lib .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('tidypics:settings:image_lib'),
	'name' => 'params[image_lib]',
	'options_values' => tidypics_get_image_libraries(),
	'value' => $plugin->image_lib,
]);

// for ImageMagick command line tools only set path to executables
$content_img_lib .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('tidypics:settings:im_path'),
	'name' => 'params[im_path]',
	'value' => $plugin->im_path,
]);

// optimize thumbnail quality/filesize?
$content_img_lib .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('tidypics:settings:thumbnail_optimization'),
	'#help' => elgg_echo('tidypics:settings:thumbnail_optimization_explanation'),
	'name' => 'params[thumbnail_optimization]',
	'options_values' => [
		'none' => elgg_echo('tidypics:settings:optimization:none'),
		'simple' => elgg_echo('tidypics:settings:optimization:simple'),
		'complex' => elgg_echo('tidypics:settings:optimization:complex'),
	],
	'value' => $plugin->thumbnail_optimization,
]);

echo elgg_view_module('inline', elgg_echo('tidypics:settings:heading:img_lib'), $content_img_lib);


// River integration of Tidypics
$content_activity = '';

$content_activity .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('tidypics:settings:album_river_view'),
	'name' => 'params[album_river_view]',
	'options_values' => [
		'cover' => elgg_echo('tidypics:option:cover'),
		'set' => elgg_echo('tidypics:option:set'),
		'none' => elgg_echo('tidypics:option:none'),
	],
	'value' => $plugin->album_river_view,
]);

$content_activity .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('tidypics:settings:river_album_number'),
	'name' => 'params[river_album_number]',
	'value' => $plugin->river_album_number,
	'min' => 1,
	'max' => 25,
	'step' => 1,
]);

$content_activity .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('tidypics:settings:img_river_view'),
	'name' => 'params[img_river_view]',
	'options_values' => [
		'all' => elgg_echo('tidypics:option:all'),
		'1' => elgg_echo('tidypics:option:single'),
		'batch' =>  elgg_echo('tidypics:option:batch'),
		'none' => elgg_echo('tidypics:option:none'),
	],
	'value' => $plugin->img_river_view,
]);

$content_activity .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('tidypics:settings:river_comments_thumbnails'),
	'name' => 'params[river_comments_thumbnails]',
	'options_values' => [
		'show' => elgg_echo('tidypics:option:river_comments_include_preview'),
		'none' => elgg_echo('tidypics:option:river_comments_no_preview'),
	],
	'value' => $plugin->river_comments_thumbnails,
]);

$content_activity .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('tidypics:settings:river_thumbnails_size'),
	'name' => 'params[river_thumbnails_size]',
	'options_values' => [
		'small' => elgg_echo('tidypics:option:river_comments_thumbnails_small'),
		'tiny' => elgg_echo('tidypics:option:river_comments_thumbnails_tiny'),
	],
	'value' => $plugin->river_thumbnails_size,
]);

$content_activity .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('tidypics:settings:river_tags'),
	'name' => 'params[river_tags]',
	'options_values' => [
		'show' => elgg_echo('tidypics:option:river_tags'),
		'none' => elgg_echo('tidypics:option:none'),
	],
	'value' => $plugin->river_tags,
]);

echo elgg_view_module('inline', elgg_echo('tidypics:settings:heading:river'), $content_activity);


// Thumbnail sizes used by Tidypics
$content_thumbnails = '';

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

$content_thumbnails .= elgg_view('output/longtext', [
	'value' => elgg_echo('tidypics:settings:sizes:instructs'),
	'class' => 'elgg-subtext',
]);

$sizes = ['large', 'small', 'tiny'];
foreach ($sizes as $size) {
	$content_thumbnails .=  elgg_view_field([
		'#type' => 'fieldset',
		'#label' => elgg_echo("tidypics:settings:{$size}size"),
		'#help' => elgg_echo("tidypics:settings:imagesize_defaultsize_{$size}"),
		'align' => 'horizontal',
		'fields' => [
			[
				'#type' => 'number',
				'#label' => elgg_echo('tidypics:settings:imagesize_width'),
				'name' => "{$size}_image_width",
				'value' => $image_sizes["{$size}_image_width"],
				'min' => 1,
				'step' => 1,
			],
			[
				'#type' => 'number',
				'#label' => elgg_echo('tidypics:settings:imagesize_height'),
				'name' => "{$size}_image_height",
				'value' => $image_sizes["{$size}_image_height"],
				'min' => 1,
				'step' => 1,
			],
			[
				'#type' => 'checkbox',
				'#label' => elgg_echo('tidypics:settings:imagesize_square'),
				'name' => "{$size}_image_square",
				'value' => true,
				'checked' => (bool) $image_sizes["{$size}_image_square"],
			],
		],
	]);
}

$content_thumbnails .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('tidypics:settings:client_resizing'),
	'#help' => elgg_echo('tidypics:settings:client_resizing_help'),
	'name' => 'params[client_resizing]',
	'value' => true,
	'checked' => (bool) $plugin->client_resizing,
]);

$content_thumbnails .=  elgg_view_field([
	'#type' => 'fieldset',
	'#label' => elgg_echo('tidypics:settings:resizing_max'),
	'#help' => elgg_echo('tidypics:settings:resizing_max_help'),
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'number',
			'#label' => elgg_echo('tidypics:settings:imagesize_width'),
			'name' => 'params[client_image_width]',
			'value' => $plugin->client_image_width,
			'min' => 1,
			'step' => 1,
		],
		[
			'#type' => 'number',
			'#label' => elgg_echo('tidypics:settings:imagesize_height'),
			'name' => 'params[client_image_height]',
			'value' => $plugin->client_image_height,
			'min' => 1,
			'step' => 1,
		],
	],
]);

$content_thumbnails .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('tidypics:settings:remove_exif'),
	'#help' => elgg_echo('tidypics:settings:remove_exif_help'),
	'name' => 'params[remove_exif]',
	'value' => true,
	'checked' => (bool) $plugin->remove_exif,
]);

echo elgg_view_module('inline', elgg_echo('tidypics:settings:heading:sizes'), $content_thumbnails);
